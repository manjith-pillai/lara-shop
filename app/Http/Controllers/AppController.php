<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Restaurant;
use \App\Models\Search;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Mail;
use Validator;
use App\Models\UserDevice;
use App\Models\DeliveryBoyReferral;
use DB;


class AppController extends Controller {

    public  $merchant_key = "gtKFFx";
    public  $salt = "eCwWELxi";
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api.signature');
        $this->response = array();
    }

    public function omniSearch($city, $text, $lat, $lng)
    {
        $lat=str_replace('@','.',$lat);
        $lng=str_replace('@','.',$lng);
        $search=new Search($text,$lat,$lng);
        $orderDetails=Self::cartDetails();
        $Request =  new Request();
        // $type = 'distance' ;
        $type = (null != $Request::input('type') && '' != $Request::input('type')) ? $Request::input('type') : 'distance' ;
        $sortorder= (null != $Request::input('sortorderval') && '' != $Request::input('sortorderval')) ? $Request::input('sortorderval') : 'asc' ;
        
        $min_price= (null != $Request::input('min_price') && '' != $Request::input('min_price')) ? $Request::input('min_price') : 0 ;
        $max_price= (null != $Request::input('max_price') && '' != $Request::input('max_price')) ? $Request::input('max_price') : 20000 ;
        $cusinesInput = null !== $Request::input('restcusines') ? $Request::input('restcusines') : [] ;
        $restaurantDetails['restcusines'] =  $search->cusineList();

        $results =  $search->sortedSearch($city,$type, $sortorder,$min_price,$max_price,$cusinesInput);
        $restaurantDetails['restList'] =  $results ;
        $response['status'] = 200 ;
        $response['value'] = $restaurantDetails ;
        return json_encode($response);
    }

    public function restaurantPageByUrlName($city,$url_name) {
        
        $restaurant=new Restaurant();
        $Request =  new Request();
        $min_price= (null !== $Request::input('min_price')  && '' != $Request::input('min_price')  )? $Request::input('min_price') : 0;
        $max_price= (null !== $Request::input('max_price')  && '' != $Request::input('max_price') ) ? $Request::input('max_price') : 20000;
        $cusinesInput = null !== $Request::input('categories') ? $Request::input('categories') : [];
        $restaurantDetails['details']=$restaurant->getDetails($url_name);
        $restaurantDetails['dishes']=$restaurant->getDishes($url_name,$min_price,$max_price,$cusinesInput);
        $restaurantDetails['categories']=$restaurant->getCategories($url_name);
        $response['status'] = 200;
        $response['value'] = $restaurantDetails ;
        return json_encode($response);
    }

    public function addToCart($id,$quantity) {
        $restDishId = $id;
        $order=new Order();
        if(!Session::has('id'))
        {
            $orderId=$order->generateOrderId($restDishId,$quantity);
            Session::put('id', $orderId);
        } else {
            $orderId=$order->addToCart(Session::get('id'),$restDishId,$quantity);
        }
        $order->calculateTaxes($orderId);
        $response['status'] = 200;
        $response['value'] = $orderId;
        return json_encode($response);
    }



    /**
    *       INPUT 
    *        {
    *           orderId : orderIdIfPreviouslyBooked --  Optional
    *           orders:  -- Optional 
    *           [
    *                orderDetail : -- Mandatory 
    *                {
    *                   restDishId :   -- Mandatory 
    *                   quantity: -- Mandatory  always > 0 
    *                }, ......
    *           ]
    *           customer_name:  ,  --Optional
    *           cust_contact:   ,  --Optional
    *           cust_email:     ,  --Optional
    *           customer_id:    ,  --Optional
    *           spl_instructs:  ,  --Optional
    *           address:        ,  --Optional
    *           paymentmode:    ,  --Optional 
    *           paymentstatus:  ,  --Optional
    *           donation:       ,  --Optional
    *           delivery_type:  ,  --Optional
    *           order_status    ,  --Optional
    *        }
    *
    *        output
    *        {
    *           orderId generated
    *           total amount:   ,  --Optional
    *           total_vat:      ,  --Optional
    *           total_servtax:  ,  --Optional
    *           total_sercharge:,  --Optional
    *           total_delCharge:,  --Optional
    *           individual_taxes:
    *           [
    *                restTaxes : -- Mandatory 
    *                {
    *                   resturantId :   -- Mandatory 
    *                   rest_amount:   ,  --Optional
    *                   vat:      ,  --Optional
    *                   servtax:  ,  --Optional
    *                   sercharge:,  --Optional
    *                }, ......
    *          ]
    *        }
    */
    public function addOrUpdateCart()
    {
        $orderId = Input::get('orderId');
        $order=new Order();
        $orders = Input::get('orders');
        if(!$orderId)
        {
            $restDishId = !empty($orders['0']['orderDetail']['restDishId']) ? $orders['0']['orderDetail']['restDishId'] : 0;
            $quantity = !empty($orders['0']['orderDetail']['quantity']) ? $orders['0']['orderDetail']['quantity'] : 0;
            $orderId = $order->generateOrderId($restDishId,$quantity);
            Session::put('id', $orderId);    
        }
        if($orders)
        {
            $order->deletepreviousdish($orderId);    
            foreach($orders as $orderDetails)
            {
                $orderDetail=$orderDetails['orderDetail'];
                $restDishId = $orderDetail['restDishId'] ;
                $quantity = $orderDetail['quantity'] ;
                $order->addToCart($orderId,$restDishId,$quantity);
            }
        }
        $order->calculateTaxes($orderId);
        $customer_name = Input::get('customer_name') ;
        $cust_contact = Input::get('cust_contact') ;
        $cust_email = Input::get('cust_email') ;
        $customer_id = Input::get('customer_id') ;
        $spl_instructs = Input::get('spl_instructs') ;
        $address = Input::get('address') ;
        $paymentmode = Input::get('paymentmode') ;
        $paymentstatus = Input::get('paymentstatus') ;
        $donation = Input::get('donation') ;
        $delivery_type = Input::get('delivery_type') ;
        $order_status = Input::get('order_status') ;
        $order_source = (null != Input::get('order_source')) ? Input::get('order_source') : 'android_app';
        $address_name = (null != Input::get('address_name')) ? Input::get('address_name') : '' ;
        $address_id = (null != Input::get('address_id')) ? Input::get('address_id') : '' ;
        $orderDetailRow = $order->getOrderDishDetails($orderId);
        if(!empty($orderDetailRow)) {
            $order->updateOrder($orderId, $cust_email, $customer_name, $cust_contact, $customer_id,
                $spl_instructs, $address, $paymentmode, $paymentstatus, $donation, $delivery_type, $order_status, $order_source, $address_name, $address_id);

            if(!empty($order_status) && strtolower($order_status) == "pending") {
               $this->sendOrderConfirmationEmail($orderId);
            }
            $orderDetail = $order->getOrderDetailsForId($orderId);
            $response['status'] = 200;
            $response['value'] = $orderDetail;
        } else {
            $response['status'] = 404 ;
            $response['message'] = 'No Details found for the order' ;
        }
        return json_encode($response);
    }

    
    /**
    *   INPUT 
    *       orderId 
    *   Output
    *       {
    *           status 
    *           value
    *       }
    *
    *
    *
    */
    public function cartDetails()
    {
        $orderId = Input::get('orderId') ;

        if($orderId)
        {
            $order = new Order();
            $orderDetails['restaurant_details'] = $order->getRestaurantDetails(Session::get('id'));
            if($orderDetails['restaurant_details'])
            {
                $orderDetails['dish_details'] = $order->getOrderDishDetails(Session::get('id'));
                $orderDetails['total'] = $order->getTotalPrice(Session::get('id'));
                $response['status'] = 200 ;
                $response['value'] = $orderDetails ;
                return json_encode($response);    
            }
            else
            {
                $response['status'] = 404 ;
                $response['message'] = 'No Details found for the order' ;
                return json_encode($response);    
            }
        }   
        else
        {
            $response['status'] = 404 ;
            $response['message'] = 'Order ID is invalid' ;
            return json_encode($response);    
        }
    }

    
    public function verifyMobile()
    {
        $contact_no = Input::get('contact_no') ;
        $verification_code = Input::get('verification_code') ;
        // Verify the Code is available and validif yes
        $response['status'] = 200 ;
        $response['message'] = 'Mobile Verified' ;
        return json_encode($response) ;
    }

    public function logout(){
        Session::flush();
         
    }

    public function getOrderDetailsForId($id)
    {
        $orderObject  = new Order();
        $results = $orderObject->getOrderDetailsForId($id) ;
        $response['status'] = 200 ;
        $response['value'] = $results ;
        return json_encode($response) ;
    }

    
    public function sortBy($city, $lat, $lng, $type,$sortorder)
    {
        $lat=str_replace('@','.',$lat);
        $lng=str_replace('@','.',$lng);
        $text = '' ;
        $search=new Search($text,$lat,$lng);
        $orderDetails=Self::cartDetails();
        $Request =  new Request();
        // $type = 'distance' ;
        //dd($type);
        
        $type = (null != $type && '' != $type) ? $type : 'distance';
        $sortorder = (null != $sortorder && '' != $sortorder) ? $sortorder : 'asc';
        if($type == 'rating') {
            $sortorder = 'desc';
        }
        $min_price= (null != $Request::input('min_price') && '' != $Request::input('min_price')) ? $Request::input('min_price') : 0 ;
        $max_price= (null != $Request::input('max_price') && '' != $Request::input('max_price')) ? $Request::input('max_price') : 20000 ;
        $cusinesInput = null !== $Request::input('restcusines') ? $Request::input('restcusines') : [] ;
        $restaurantDetails['restcusines'] =  $search->cusineList();

        $results =  $search->sortedSearch($city,$type, $sortorder,$min_price,$max_price,$cusinesInput);
        $restaurantDetails['restList'] =  $results ;


        $response['status'] = 200 ;
        $response['value'] = $restaurantDetails ;
        return json_encode($response) ;
    
   }


   public function service_cities()
    {
        $restaurant = new Restaurant();
        $cities = $restaurant->getCities(false);
        $response['status'] = 200;
        $response['cities'] = $cities;
        return $response;
    }
    
    public function sendOrderConfirmationEmail($order_id) {
		
		$order_info = array();
		$user_info = array();
		$order = new Order();
                $order_info['address']= $order->getAddressForOrder($order_id);
		$order_info['customer']= $order->getCustomerInfoByOrderId($order_id);
		$order_info['order_info']= $order->getOrderDetailsForId($order_id);
		Mail::send('emails.order_place', array('order_info' => $order_info), function($message) use ($order_info) {
                    $message->to($order_info['customer']->email, $order_info['customer']->name);
                    if(env('APP_ENV') != 'local') {
                        //As per the Sudhir email
                        $message->cc('order@zapdel.com');
                        //$message->cc('kanojia.a@boibanit.com');
                        $message->bcc('prakash.s@boibanit.com', 'Sudhir Prakash');
                        $message->bcc('orderzapdel@gmail.com', 'Zapdel');
                    }
                    $message->subject('Order Placed');
		});
	}
        
        
    public function saveDeviceInfo() {
        try {
            $Request =  new Request();
            $validation_rules = array(
                'device_unique_id'     =>  'required',
                'push_token'   =>  'required',
                'os'   =>  'required'        
            );
            $validator = Validator::make($Request::all(), $validation_rules);
            if ($validator->fails()) { 
                $this->response['message'] = $validator->errors()->first();
                $this->response['status'] = 201;
                return $this->response;
            } else {
                $user_device = UserDevice::where('device_unique_id', $Request::input('device_unique_id'))->first();
                if(empty($user_device)) {
                 $user_device = new UserDevice;   
                }
                $user_device->device_unique_id = $Request::input('device_unique_id');
                $user_device->push_token = $Request::input('push_token');
                $user_device->os = $Request::input('os');
                if(!empty($Request::input('model'))) {
                    $user_device->model = $Request::input('model');
                }
                if(!empty($Request::input('os_version'))) {
                    $user_device->os_version = $Request::input('os_version');
                }
                if(!empty($Request::input('app_version'))) {
                    $user_device->app_version = $Request::input('app_version');
                }
                if(!empty($Request::input('app_version_code'))) {
                    $user_device->app_version_code = $Request::input('app_version_code');
                }
                if(!empty($Request::input('latitude'))) {
                    $user_device->latitude = $Request::input('latitude');
                }
                if(!empty($Request::input('longitude'))) {
                    $user_device->longitude = $Request::input('longitude');
                }
                if(!empty($Request::input('user_id'))) {
                    $user_device->user_id = $Request::input('user_id');
                }
                if($user_device->save()) {
                    $this->response['message'] = 'Device info updated';
                    $this->response['status'] = 200;
                } else {
                    $this->response['message'] = 'Some problem to saving device info';
                    $this->response['status'] = 201;
                }
            }
        } catch (Exception $e) {
            
        }
        return $this->response;
    }


    
    
    public function saveDeliveryBoyReferral() {
        
        try {
            $Request =  new Request();
            $validation_rules = array(
                'emp_code' => 'required|exists:delivery_boy_users',
                'device_unique_id' => 'required',
                'push_token' => 'required',
                'os' => 'required'
            );
            $validator = Validator::make($Request::all(), $validation_rules);
            if ($validator->fails()) { 
                $this->response['message'] = $validator->errors()->first();
                $this->response['status'] = 201;
                return $this->response;
            } else {
                $user_device = UserDevice::where('device_unique_id', $Request::input('device_unique_id'))->first();
                if(empty($user_device)) {
                 $user_device = new UserDevice;   
                }
                $user_device->device_unique_id = $Request::input('device_unique_id');
                $user_device->push_token = $Request::input('push_token');
                $user_device->os = $Request::input('os');
                if(!empty($Request::input('model'))) {
                    $user_device->model = $Request::input('model');
                }
                if(!empty($Request::input('os_version'))) {
                    $user_device->os_version = $Request::input('os_version');
                }
                if(!empty($Request::input('app_version'))) {
                    $user_device->app_version = $Request::input('app_version');
                }
                if(!empty($Request::input('app_version_code'))) {
                    $user_device->app_version_code = $Request::input('app_version_code');
                }
                if(!empty($Request::input('latitude'))) {
                    $user_device->latitude = $Request::input('latitude');
                }
                if(!empty($Request::input('longitude'))) {
                    $user_device->longitude = $Request::input('longitude');
                }
                if(!empty($Request::input('user_id'))) {
                    $user_device->user_id = $Request::input('user_id');
                }
                if($user_device->save()) {
                    $delivery_boy_user_id = DB::table('delivery_boy_users')
                                            ->where('delivery_boy_users.emp_code', $Request::input('emp_code'))
                                            ->first()->id;
                    
                    $delivery_boy_referral = DeliveryBoyReferral::where('delivery_boy_user_id', $delivery_boy_user_id)
                                            ->where('user_device_id', $user_device->id)
                                            ->first();
                    if(empty($delivery_boy_referral)) {
                       $delivery_boy_referral = new DeliveryBoyReferral;
                    }
                    $delivery_boy_referral->delivery_boy_user_id = $delivery_boy_user_id;
                    $delivery_boy_referral->user_device_id = $user_device->id;
                    $delivery_boy_referral->save();
                    $this->response['message'] = 'Your referral applied successfully';
                    
                    $this->response['status'] = 200;
                } else {
                    $this->response['message'] = 'Some problem to saving referral info';
                    $this->response['status'] = 201;
                }
            }
        } catch (Exception $e) {
            
        }
        return $this->response;
    }
    
     /**
     * Forget password
     * @param unknown_type $request
     */
    
     public function forgetpassword(Request $request) {
        try {
            $validation_rules = array(
                'email' => 'required'
            );

            $validator = Validator::make(
                            $request->all(), $validation_rules
            );

            if ($validator->fails()) {
                $this->response['response_code'] = "201"; // Exception error
                $this->response['message'] = $validator->messages()->first();
            } else {
                $password = $this->apiModel->forgetpassword($request);
                if ($password == '1') {
                    $this->response['status'] = "0";
                    $this->response['response_code'] = "1006"; //Email not exist
                    $this->response['message'] = $this->error->getapplicationerror("1006");
                } else if ($password == '2') {
                    $this->response['status'] = "0";
                    $this->response['response_code'] = "1007"; //User account is not activated
                    $this->response['message'] = $this->error->getapplicationerror("1007");
                } else {
                    $this->response['message'] = "A new password has been sent to your respective email address.";
                }
            }
        } catch (Exception $e) {
            $this->response['response_code'] = "1001"; //Exception error
            $this->response['message'] = $e->message;
        }
        return $this->response;
    }
    
    
    //code for add address api
    public function addAddress() {
        $request=new request();
        $email_id=request::input('email_id');
        $address_name=request::input('address_name');
        $address=request::input('address');
        $is_default = request::input('is_default') ? request::input('is_default') : 0;
        
        $validation_rules = array();
        $validation_rules['email_id'] = 'required';
        $validation_rules['address_name'] = 'required';
        $validation_rules['address'] = 'required';
        //$validation_rules['is_default'] = 'required';

        $validator = Validator::make($request::all(), $validation_rules);
        if ($validator->fails()) {
            $response['status'] = 406 ;
            $response['message'] = $validator->messages()->first();
            return json_encode($response) ;
        } else {
            $user_info = DB::table('users')->where('email' , $email_id)->first();
            //dd($user_id);
            if(!empty($user_info)) {
                $last_address_id = DB::table('address')->insertGetId(['customer_id'=>$user_info->id,'address_name' => $address_name, 'address' => $address, 'is_default' => $is_default]);
                if($last_address_id) {
                    $response['status'] = 200;
                    $response['message'] = "Your address save successfully.";
                    $response['address'] = DB::table('address')->where(['customer_id' => $user_info->id, 'id' => $last_address_id])->first();
                } else {
                    $response['status'] = 406 ;
                    $response['message'] = 'Unable to save address, Please try again.';
                }
                return json_encode($response);
            } else {
                $response['status'] = 406 ;
                $response['message'] = 'Please enter valid email.';
                return json_encode($response) ;

            }
            
        }
   
        }
    
        public function updateAddress(){
            $request = new request();
            $email_id = request::input('email_id');
            $id = request::input('address_id');
            $address_name = request::input('address_name');
            $address = request::input('address');
            $is_default = request::input('is_default');

            $validation_rules = array();
            $validation_rules['email_id'] = 'required';
            $validation_rules['address_id'] = 'required';
            $validation_rules['address_name'] = 'required';
            $validation_rules['address'] = 'required';
            
            $validator = validator::make($request::all(), $validation_rules);
            if ($validator->fails())
            {
                $response['status'] = 406 ;
                $response['message'] = $validator->messages()->first();
                 return json_encode($response) ;
            } else {
                $user_info = DB::table('users')->where('email' , $email_id)->first();

            if (!empty($user_info)) {
                DB::table('address')->where('customer_id', $user_info->id)->where('id', $id)->update(['address_name' => $address_name, 'address' => $address, 'is_default' => $is_default]);
                $response['status'] = 200 ;
                $response['message'] = "Your address updated successfully.";
                $response['address'] = DB::table('address')->where(['customer_id' => $user_info->id, 'id' => $id])->first();
                return json_encode($response) ;
                                  
             } else{
                 $response['status'] = 406 ;
                 $response['message'] = 'Please enter valid email.';
                 return json_encode($response) ;
                   }
                }
        }


        public function deleteAddress() {
            $request = new request();
            $email_id=request::input('email_id');
            $id = request::input('address_id');

            $validation_rules = array();
            $validation_rules['email_id'] = 'required';
            $validation_rules['address_id'] = 'required';

            $validator = validator::make($request::all(), $validation_rules);
            if ($validator->fails())
            {
                $response['status'] = 406 ;
                $response['message'] = $validator->messages()->first();
                 return json_encode($response) ;
            }
              else
            {
                $user_info = DB::table('users')->where('email' , $email_id)->first();

                if(!empty($user_info)){
                DB::table('address')->where('id', $id)->where('customer_id', $user_info->id)->delete();
                $response['status'] = 200 ;
                $response['message'] = "Your address has been removed.";
                return json_encode($response) ;
                                     
               } else {
                 $response['status'] = 406 ;
                 $response['message'] = 'Please enter valid email.';
                 return json_encode($response) ;
             }

            }
        }
        
        public function updateProfile($user_id) {
            
            $request = new request();
            $email_id = request::input('email');
            $phone = request::input('mobile');
            $name = request::input('name');
            $is_phone_verified = request::input('is_phone_verified');
            $validation_rules = array();
            $validation_rules['email'] = 'required|email|unique:users,email,'.$user_id;
            $validation_rules['name'] = 'required';
            $validation_rules['mobile'] = 'required|unique:users,phone,'.$user_id;
            $validator = validator::make($request::all(), $validation_rules);
            if ($validator->fails())
            {       
                $response['status'] = 406 ;
                $response['message'] = $validator->messages()->first();
                 return json_encode($response) ;
            } else {
                $user_info = DB::table('users')->where('id' , $user_id)->first();
                if (!empty($user_info)) {
                    DB::table('users')->where('id', $user_id)->update(['email' => $email_id, 'phone' => $phone, 'name' => $name, 'is_phone_verified' => $is_phone_verified]);
                    $response['status'] = 200 ;
                    $response['message'] = "Your profile updated successfully.";
                    $response['value'] = DB::table('users')->where('id', $user_id)->select('id','name', 'email', 'is_active', 'phone', 'is_phone_verified')->first();
                    $response['value']->user_addresses = DB::table('address')->where('customer_id', $user_id)->get();
                    return json_encode($response);
                } else {
                    $response['status'] = 406;
                    $response['message'] = 'Please send valid user id.';
                    return json_encode($response);
                   }
            }
        }

        public function sendOTP()
        {

            $request = new request();
            $email_id = request::input('email');
            $mob = request::input('mobile');
            $validation_rules = array();
            $validation_rules['email'] = 'required_without:mobile|email';
            $validation_rules['mobile'] = 'required';
            $validator = validator::make($request::all(), $validation_rules);
            if ($validator->fails())
            {
                $response['status'] = 406 ;
                $response['message'] = $validator->messages()->first();
                 return json_encode($response) ;
            } else {
                $user_info = DB::table('users')->where('email' , $email_id)->orWhere('phone', $mob)->first();
                if (!empty($user_info)) {
                    DB::table('otps')->where('mobile', '=', $mob)->delete();
                    $otp = mt_rand(100000, 999999);
                    $sendSMSResponse = $this->sendSMS($mob, $otp);
                    if(!empty($sendSMSResponse) && trim($sendSMSResponse[0]) == 'success') {
                        $results = DB::table('otps')->insert(['mobile' => $mob, 'otp' => $otp, 'expire_on'=>24*60*60]);
                        $response['status'] = 200 ;
                        $response['message'] = "An OTP sent to you successfully.";
                        $response['value'] = DB::table('users')->where('id', $user_info->id)->select('id','name', 'email', 'is_active', 'phone', 'is_phone_verified')->first();
                        $response['value']->user_addresses = DB::table('address')->where('customer_id', $user_info->id)->get();
                    } else {
                        $response['status'] = 406;
                        $response['message'] = 'Unable to send OTP, Please enter valid mobile number.';
                        
                    }
                    return json_encode($response);
                } else {
                    $response['status'] = 406;
                    $response['message'] = 'Please send valid user id.';
                    return json_encode($response);
                }
            }
        }
    
    public function verify_otp()
    {
            $request = new request();
            $mobile = request::input('mobile');
            $verify_code = request::input('verify_code');
            $email = request::input('email');
            $validation_rules = array();
            $validation_rules['verify_code'] = 'required';
            $validation_rules['email'] = 'required_without:mobile|email';
            $validation_rules['mobile'] = 'required';
            $validator = validator::make($request::all(), $validation_rules);
            if ($validator->fails())
            {
                $response['status'] = 406 ;
                $response['message'] = $validator->messages()->first();
                 return json_encode($response) ;
            } else {
                $user_info = DB::table('users')->where('email' , $email)->orwhere('phone' , $mobile)->first();        
                if(!empty($user_info)) {
                    $result = DB::table('otps')->where('otp','=',$verify_code)->where('mobile','=',$mobile)->first();
                    if(!empty( $result)) {
                        DB::table('users')->where('phone', $mobile)->orWhere('email', $email)->update(['is_phone_verified' => 1]);
                        $response['status'] = 200 ;
                        $response['message'] = "Your mobile verified successfully.";
                        $response['value'] = DB::table('users')->where('id', $user_info->id)->select('id','name', 'email', 'is_active', 'phone', 'is_phone_verified')->first();
                        $response['value']->user_addresses = DB::table('address')->where('customer_id', $user_info->id)->get();
                    } else {
                        $response['status'] = 406;
                        $response['message'] = 'Please enter valid OTP.';
                    }
                    return json_encode($response);
                } else {
                    $response['status'] = 406;
                    $response['message'] = 'Please send valid email address and mobile.';
                    return json_encode($response);
                }
        }
    }

    private function sendSMS($mobile, $otp) {

        // http://enterprise.smsgupshup.com/GatewayAPI/rest?
        // msg=Hello+this+is+9882+is+your+OTP
        // &v=1.1
        // &userid=2000152437
        // &password=2gSrke7t8
        // &send_to=9911714588
        // &msg_type=text
        // &method=sendMessage


        // We want to read the public stream. 
        // Set all the parameters for the API call    
            $method = 'sendMessage';                     
            $send_to = $mobile;
            //$msg='Hello+this+is+'.$otp.'+is+your+OTP';
            $msg=$otp.'+is+your+OTP+for+mobile+verification+at+Zapdel';
         
        // Construct the URL now
           $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?msg=$msg&send_to=$send_to&method=$method&v=1.1&userid=2000152437&password=2gSrke7t8&msg_type=text";
         
        // Using curl to call the URL and read the response
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $curl_scraped_page = curl_exec($ch);
            curl_close($ch);
         
        // Outputting the response
            //dd(explode('|', $curl_scraped_page));
            return (explode('|', $curl_scraped_page));
    }


     public function orders_feedback(){

           $request = new request();
           $email = $request::input('email');
           $order_id = $request::input('order_id');
           $restaurant_ratings = $request::input('restaurant_ratings');
           $delivery_rating = $request::input('delivery_rating');
           $comment= $request::input('comment');
           $validation_rules = array();
           $validation_rules['email'] = 'required|email';
           $validation_rules['order_id'] = 'required';
           $validation_rules['delivery_rating'] = 'required|integer';
           $validation_rules['comment'] = 'required';
           $validator = validator::make($request::all(), $validation_rules);
           if ($validator->fails())
            {
                $response['status'] = 406 ;
                $response['message'] = $validator->messages()->first();
                return json_encode($response) ;
           } else {
                $res = ['email' => $email];
                $result = DB::table('users')->where($res)->get();
           if($result)
           {
            
                 $feedback_save_status = DB::table('orders_feedback')->insert(['order_id' => $order_id, 'delivery_rating' => $delivery_rating, 'comment' => $comment]);
            if($feedback_save_status) {
                $order_feedback_id = DB::table('orders_feedback')->where('order_id',$order_id)->first();
                foreach ($restaurant_ratings as $value) {
                      // dd($value['rating']);
                 DB::table('order_feedback_restaurants')->insert(['order_feedback_id' =>$order_feedback_id->id, 'restaurant_id' => $value['restaurant_id'], 'rating' => $value['rating']]);
                  }
                
                $response['status'] = 200 ;
                $response['message'] = 'Thanks for your feedback.';
                
            }
            
           return json_encode($response) ;
           } else
                $response['status'] = 406 ;
                $response['message'] = 'Please enter valid email.';
                return json_encode($response) ;
           }

    } 

    public function orderList(){
        $request = new request();
        $email = $request::input('email');
        $validation_rules = array();
        $validation_rules['email'] = 'required|email';
        $validator = validator::make($request::all(), $validation_rules);
        if ($validator->fails())
          {
            $response['status'] = 406 ;
            $response['message'] = $validator->messages()->first();
            return json_encode($response) ;
          } else {
                $user_info = DB::table('users')->where('email' , $email)->first();
                // dd($user_info->id);
                if ($user_info) {
                    $result = DB::table('order')->where('customer_id', $user_info->id)->select('id','customer_name','total_amount','order_place_time')->first();
                    //dd($result);
                    $response['status'] = 200 ;
                    $response['message'] = "List of orders.";
                    $response['value'] = $result;
                    return json_encode($response);
                    
                    } else {
                        $response['status'] = 400 ;
                        $response['message'] = "Enter a valid email";
                        return json_encode($response);
                    }
                }
       }

       public function OrderDetail(){
            $request = new request();
            $email = $request::input('email');
            $order_id = $request::input('order_id');
            $validation_rules = array();
            $validation_rules['email'] = 'required|email';
            $validation_rules['order_id'] = 'required';
            $validator = validator::make($request::all(), $validation_rules);
            if ($validator->fails())
              {
                $response['status'] = 406 ;
                $response['message'] = $validator->messages()->first();
                return json_encode($response) ;
              } else {
                    $user_info = DB::table('users')->where('email' , $email)->first();
                    if ($user_info) {
                        $result = DB::table('order')->where('customer_id', $user_info->id)->where('id', $order_id)->first();
                      } elseif ($result) {
                            $restaurant_info = DB::table('order_dish_details')->where('order_id', $order_id)->first();
                             }
                             elseif ($restaurant_info) {
                                 $orderdetail = DB::table('restaurant_dishes')->where('dish_id', $restaurant_info->rest_dish_id)->where('restaurant_id', $restaurant_info->rest_id);
                                 $response['status'] = 200 ;
                                 $response['message'] = "orders_detail";
                                 $response['value'] = DB::table('dish_details')->where('id', $orderdetail->dish_id)->select('dish_name');
                                 return json_encode($response) ;

                                 } else {
                                        $response['status'] = 400 ;
                                        $response['message'] = "Enter valid email or order_id";
                                        return json_encode($response) ;
                                 }
                    }

                }

            public function reOrder(){
                $request = new request();
                $order_id = $request::input('order_id');
                $payment_status = $request::input('payment_status');
                $payment_mode = $request::input('payment_mode');
                $donation = $request::input('donation');
                $order_status_id = $request::input('order_status_id');
                $delivery_type = $request::input('delivery_type');
                $order_place_time = $request::input('order_place_time');
                $order_source = $request::input('order_source'); 
                $is_viewed = $request::input('is_viewed');
                $updated_at = $request::input('updated_at');
                $validation_rules = array();
                $validation_rules['order_id'] = 'required';
                $validation_rules['payment_status'] = 'required';
                $validation_rules['payment_mode'] = 'required';
                // $validation_rules['donation'] = 'required';
                // $validation_rules['order_source'] = 'required';
                $validator = validator::make($request::all(), $validation_rules);
                if ($validator->fails())
                 {
                    $response['status'] = 406 ;
                    $response['message'] = $validator->messages()->first();
                    return json_encode($response) ;
                 } else {
                           $reorder = DB::table('order')->where('id', $order_id)->first();
                        if ($reorder) {
                           $order_getid= DB::table('order')->insertGetId(['created_time' => date('Y-m-d H:i:s') , 'customer_id' => $reorder->customer_id, 'customer_name' => $reorder->customer_name, 'city_id' => $reorder->city_id, 'address_id' => $reorder->address_id, 'total_amount' => $reorder->total_amount, 'txn_id' => $reorder->txn_id, 'spl_instruct' => $reorder->spl_instruct, 'cust_contact' => $reorder->cust_contact, 'total_vat' =>$reorder->total_vat, 'total_servtax' => $reorder->total_servtax, 'total_sercharge' => $reorder->total_sercharge, 'total_delCharge' => $reorder->total_delCharge, 'total_packcharge' => $reorder->total_packcharge, 'total_with_tax' => $reorder->total_with_tax, 'payment_status' => $payment_status, 'payment_mode' => $payment_mode, 'donation' => $donation, 'order_status_id' => $order_status_id, 'delivery_type' => $delivery_type, 'order_place_time' => $order_place_time, 'order_source' => $order_source, 'is_viewed' => $is_viewed, 'updated_at' => $updated_at]);
                            
                           if ($order_getid) {
                               $insert = DB::table('order_dish_details')->where('order_id', $order_id)->first();
                               
                               $response['value']= DB::table('order_dish_details')->insert(['order_id' => $order_getid, 'rest_dish_id' => $insert->rest_dish_id, 'quantity' => $insert->quantity, 'price' => $insert->price, 'rest_id' => $insert->rest_id]);
                           }
                               return json_encode($response) ;
                                } else {
                                        $response['status'] = 400 ;
                                        $response['message'] = "Enter valid email or order_id";
                                        return json_encode($response) ;

                                }
                        }
                    }
             
            public function orderHistory(){
                $request = new request();
                $user_id = $request::input('user_id');
                $email = $request::input('email');
                $validation_rules = array();
                $validation_rules['user_id'] = 'required';
                $validation_rules['email'] = 'required';
                $validator = validator::make($request::all(), $validation_rules);
                if ($validator->fails())
                 {
                    $response['status'] = 406 ;
                    $response['message'] = $validator->messages()->first();
                    return json_encode($response) ;
                 } else {
                        $order_info = DB::table('order')
                                         ->where('customer_id', $user_id)
                                         ->get();
                        if (!empty($order_info)) {
                            $orderModel = new Order(); 
                            foreach ($order_info as $value) {
                                $restaurant_address = $orderModel->getAllRestaurants($value->id);
                                $dish_details = $orderModel->getFullOrderDishDetails($value->id);
                                $order_taxes = $orderModel->getOrderTaxes($value->id);
                                $pending_list[] = ['orderDishes' =>$dish_details, 'orderTaxes'=> $order_taxes];
                                   }
                                    $this->response['orders'] = $pending_list;
                                    //$this->response['total_orders'] = $order_count;
                                    $this->response['status'] = 200;
                                    return json_encode($this->response);
                                     } else {
                                         $this->response['message'] = "There is no past order";
                                         $this->response['status'] = 201;
                                         return $this->response;
                                         } 
        }
                            
    }
   
   public function resetPassword(){
    $request = new request();
    $mobile = $request::input('mobile');
    $password = $request::input('password');
    $validation_rules = array();
    $validation_rules['mobile'] = 'required';
    $validation_rules['password'] = 'required';
    $validator = validator::make($request::all(), $validation_rules);
    if ($validator->fails())
     {
        $response['status'] = 406 ;
        $response['message'] = $validator->messages()->first();
        return json_encode($response) ;
     } else {
            $store_pass = DB::table('users')
                            ->where('phone', $mobile)
                            ->update(['password' =>bcrypt($password)]);
            $values = DB::table('users')
                            ->where('phone', $mobile)
                            ->get();   
            if ($store_pass) {
                $response['status'] = 200 ;
                $response['message'] = "password updated successfully" ;
                $response['value'] = DB::table('users')->where('phone', $mobile)->select('id','name', 'email', 'is_active', 'phone', 'is_phone_verified')->first() ;
                $response['value']->user_addresses = DB::table('address')->where('customer_id', $values['0']->id)->get();
                return json_encode($response) ;
               }  else {
                    $this->response['message'] = "Mobile number not valid";
                    $this->response['status'] = 201;
                    return $this->response;
              }                               
        }
    }

    public function contactUs(){
        $request = new request();
        $name = $request::input('name');
        $mobile = $request::input('mobile');
        $email = $request::input('email');
        $message = $request::input('message');
        $contact_type = $request::input('contact_type');
        $validation_rules = array();
        $validation_rules['name'] = 'required';
        $validation_rules['mobile'] = 'required';
        $validation_rules['email'] = 'required|email';
        $validation_rules['message'] = 'required';
        $validator = validator::make($request::all(), $validation_rules);
        if ($validator->fails())
         {
            $response['status'] = 406 ;
            $response['message'] = $validator->messages()->first();
            return json_encode($response) ;
         } else {
             $result = DB::table('contact_us')->insert(['name' => $name, 'phone' => $mobile, 'email' => $email, 'message' => $message, 'contact_type' => $contact_type ]);
             if ($result) {
                 $this->response['message'] = "Your record has been added, we will contact you soon";
                 $this->response['status'] = 200;
                 return $this->response;
               } else {
                     $this->response['message'] = "Something wrong";
                     $this->response['status'] = 201;
                     return $this->response;
                  }
            }
    }


    public function ongoingOrders(){
        $request = new request();
        $user_id = $request::input('user_id');
        $validation_rules = array();
        $validation_rules['user_id'] = 'required';
        $validator = validator::make($request::all(), $validation_rules);
        if ($validator->fails())
         {
            $response['status'] = 406 ;
            $response['message'] = $validator->messages()->first();
            return json_encode($response) ;
         } else { 
              $ongoing_order = DB::table('order')
                                  ->join('order_status','order.order_status_id', '=', 'order_status.order_status_id')
                                  ->where('customer_id', $user_id)
                                  ->whereIn('order_status.name',['processing','pending','prepared','pickedup'])
                                  ->select('order.id as order_id','order.total_amount as total_amount','order.order_place_time as order_time','order_status.name')
                                  ->get();
            }
              if (!empty($ongoing_order)) {
                 foreach ($ongoing_order as $value) {
                            $date_time = strtotime($value->order_time);
                            $date = date('dS F Y', $date_time);
                               
                 $response['status'] = 200;
                 $response['value'][]= ['order_id' => $value->order_id, 'total_amount' => $value->total_amount, 'date' => $date, 'status' => $value->name];
                 } 
                   } else {
                         $response['status'] = 201;
                         $response['message'] = "There is no any ongoing order";
                        }
                         return json_encode($response);
        }

      public function pastOrder(){
        $request = new request();
        $user_id = $request::input('user_id');
        $validation_rules = array();
        $validation_rules['user_id'] = 'required';
        $validator = validator::make($request::all(), $validation_rules);
        if ($validator->fails())
         {
            $response['status'] = 406 ;
            $response['message'] = $validator->messages()->first();
            return json_encode($response) ;
         } else {
               $past_order = DB::table('order')
                                  ->join('order_status','order.order_status_id', '=', 'order_status.order_status_id')
                                  ->where('customer_id', $user_id)
                                  ->whereIn('order_status.name',['Complete','Canceled','Voided','Refunded','Canceled Reversal','Denied'])
                                  ->select('order.id as order_id','order.total_amount as total_amount','order.order_place_time as order_time','order_status.name')
                                  ->get();
            }
               if (!empty($past_order)) {
                 foreach ($past_order as $value) {
                            $date_time = strtotime($value->order_time);
                            $date = date('dS F Y', $date_time);
                               
                 $response['status'] = 200;
                 $response['value'][]= ['order_id' => $value->order_id, 'total_amount' => $value->total_amount, 'date' => $date, 'status' => $value->name];
                 } 
                   } else {
                         $response['status'] = 201;
                         $response['message'] = "There is no any past order";
                        }
                         return json_encode($response);
        }      

}
