<?php 
namespace App\Http\Controllers;
use App\Models\Order;
use LOG;
use App\Models\Restaurant;
use \App\Models\Search;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\CreateValidation;
use Mail;
use DB;
use Response;

/**
*
*/
class ApiController extends Controller {

    public  $merchant_key;
    public  $salt;
    public  $payu_payment_url;
    public  $payu_surl;
    public  $payu_furl;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->merchant_key = env('APP_ENV') =='local' ? env('PAYU_MERCHANT_KEY_TEST','') : env('PAYU_MERCHANT_KEY','');
        $this->salt = env('APP_ENV') =='local' ? env('PAYU_SALT_TEST','') : env('PAYU_SALT','');
        $this->payu_payment_url = env('APP_ENV') =='local' ? env('PAYU_PAY_URL_TEST','') : env('PAYU_PAY_URL','');
        $this->payu_surl = env('APP_ENV') =='local' ? 'http://'.$_SERVER['HTTP_HOST'].'/payu_payment_success' : 'https://'.$_SERVER['HTTP_HOST'].'/payu_payment_success';
        $this->payu_furl = env('APP_ENV') =='local' ? 'http://'.$_SERVER['HTTP_HOST'].'/payu_payment_failure' : 'https://'.$_SERVER['HTTP_HOST'].'/payu_payment_failure';
	}

    
	/**
	* Show the application dashboard to the user.
	*
	* @return Response
	*/

    public function index()
    {
        
        $user_previous_city = isset($_COOKIE['user_city']) ? $_COOKIE['user_city'] : '';
        $restaurant = new Restaurant();
        $cities = $restaurant->getCities();
        $orderDetails=Self::cartDetails();
        return view('ind')->with('orderDetails',$orderDetails)->with('cities',$cities)->with('user_previous_city',$user_previous_city);
    }

	public function omniSearch($city,$text,$lat,$lng)
	{
        
        $lat=str_replace('@','.',$lat);
        $lng=str_replace('@','.',$lng);
		$search=new Search($text,$lat,$lng);
        $orderDetails=Self::cartDetails();
        $Request = new Request();
        $type = 'distance' ;
        $sortorder= (null != $Request::input('sortorderval') && '' != $Request::input('sortorderval')) ? $Request::input('sortorderval') : 'asc' ;
        $min_price= (null != $Request::input('min_price') && '' != $Request::input('min_price')) ? $Request::input('min_price') : 0 ;
        $max_price= (null != $Request::input('max_price') && '' != $Request::input('max_price')) ? $Request::input('max_price') : 20000 ;
        $cusinesInput = null !== $Request::input('restcusines') ? $Request::input('restcusines') : [] ;
        $restaurantDetails['restcusines'] =  $search->cusineList();

        $results =  $search->sortedSearch($city,$type, $sortorder,$min_price,$max_price,$cusinesInput);
        //return $results;
        return view('search')->with('restaurantInfo',$results)->with('orderDetails',$orderDetails)->with('city',$city)->with('restaurantDetails', $restaurantDetails)->with('lng', $lng)->with('lat', $lat);
	}


    public function sortBy($city, $lat, $lng, $type,$sortorder){
        $title="";
        $keywords="";
        $description="";
        $lat=str_replace('@','.',$lat);
        $lng=str_replace('@','.',$lng);
        $search=new Search('',$lat,$lng);
        $orderDetails=Self::cartDetails();
        $Request =  new Request();
        $restaurantDetails['restcusines'] =  $search->cusineList();
        $min_price= (null != $Request::input('min_price') && '' != $Request::input('min_price')) ? $Request::input('min_price') : 0 ;
        $max_price= (null != $Request::input('max_price') || '' != $Request::input('max_price')) ? $Request::input('max_price') : 20000 ;
        $cusinesInput = null !== $Request::input('restcusines') ? $Request::input('restcusines') : [] ;
        
        $results=$search->sortedSearch($city,$type, $sortorder,$min_price,$max_price,$cusinesInput);
        
        return view('search')->with('restaurantInfo',$results)->with('orderDetails',$orderDetails)->with('city',$city)->with('restaurantDetails', $restaurantDetails)->with('lng', $lng)->with('lat', $lat)->with('title',$title)->with('keywords',$keywords)->with('description',$description);
    }

    public function restaurantPageByUrlName($city,$url_name)
    {
        $restaurant = new Restaurant();
	    $Request =  new Request();
	    $min_price= (null !== $Request::input('min_price')  && '' != $Request::input('min_price')  )? $Request::input('min_price') : 0 ;
	    $max_price= (null !== $Request::input('max_price')  && '' != $Request::input('max_price') ) ? $Request::input('max_price') : 20000 ;
	    $cusinesInput = null !== $Request::input('cusines') ? $Request::input('cusines') : [] ;
         $restaurantDetails['details']=$restaurant->getDetails($url_name);
         $restaurantDetails['dishes']=$restaurant->getDishes($url_name,$min_price,$max_price,$cusinesInput);
	     $restaurantDetails['cusines']=$restaurant->getCategories($url_name);
	     $orderDetails=Self::cartDetails();
        $meta_name = !empty($restaurantDetails['details'][0]->name) ? $restaurantDetails['details'][0]->name : '';
        $meta_area_name = !empty($restaurantDetails['details'][0]->area_name) ? $restaurantDetails['details'][0]->area_name : '';
        $meta_address = !empty($restaurantDetails['details'][0]->address) ? $restaurantDetails['details'][0]->address : '';
        $meta_city_name =  !empty($restaurantDetails['details'][0]->city_name) ? $restaurantDetails['details'][0]->city_name : '';
        $meta_keywords = !empty($restaurantDetails['details'][0]->meta_keywords) ? $restaurantDetails['details'][0]->meta_keywords : '';
        $meta_description = !empty($restaurantDetails['details'][0]->meta_description) ? $restaurantDetails['details'][0]->meta_description : '';

        $title = $meta_name.", ".$meta_address.$meta_area_name.", ".$meta_city_name." - Zapdel";
        $keywords = $meta_keywords;
        $description =  $meta_description;
	     return view('restaurant')->with('restaurantDetails',$restaurantDetails)->with('orderDetails',$orderDetails)->with('city',$city)->with('min_price',$min_price)->with('max_price',$max_price)->with('cusines', $cusinesInput)->with('title',$title)->with('keywords',$keywords)->with('description',$description);
    }

    public function addToCart($id,$quantity){
        if($quantity >=0) {
            $restDishId=$id;
            $order=new Order();
            if(!Session::has('id'))
            {
                $orderId=$order->generateOrderId($restDishId,$quantity);
                Session::put('id', $orderId);
            }else{
                $orderId=$order->addToCart(Session::get('id'),$restDishId,$quantity);
            }
        }
        return Self::cartDetails();
    }

    public function cartDetails(){
        if(Session::has('id'))
        {

            $order = new Order();
            $orderDetails['restaurant_details'] = $order->getRestaurantDetails(Session::get('id'));
            $orderinfo = $order->getOrderDetailsForId(Session::get('id')) ; 

            $orderDetails['dish_details'] = $orderinfo['orderDishes'];
            $orderDetails['total'] = $orderinfo['total'];
            $orderDetails['orderinfo'] = $orderinfo['order'];
            $orderDetails['ordertaxes'] = $orderinfo['orderTaxes'];
            Session::put('orderInfo',$orderinfo) ;
            //dd($orderDetails);
            return $orderDetails;
        }   
        else
        {
            $orderDetails['message'] = "No Session Id found" ;
            $orderDetails['dish_details'] = null;
            $orderDetails['total'] = null;
            $orderDetails['orderinfo'] = null;
            $orderDetails['ordertaxes'] = null;
            return $orderDetails;
        }
    }
    public function confirmCheckout(){
        // $orderDetails=Self::cartDetails();

       // return view('confirm_checkout')->with('orderDetails',$orderDetails);

    }

    public function userDetails()
    {
        if(date('H') >= 10 && date('H') <= 22) 
        {
            $title="";
            $keywords="";
            $orderDetails=Self::cartDetails();
            $specialintruct = Input::has('specialinstructions') ? Input::get('specialinstructions') : ''  ;
            $order_id= Session::get('id');  
            $order = new Order();
            $order->updateSpecialInstructions($order_id,$specialintruct);
            $order->calculateTaxes($order_id);
            $min_amount = 200;
            $is_zappmeal_order = $order->isZappmealOrder($order_id);
            if(!empty($is_zappmeal_order)) {
                $min_amount = 99;
            }
            if($orderDetails['orderinfo']->total_amount >=$min_amount) {
                 return view('confirm_checkout')->with('orderDetails',$orderDetails)->with('title',$title)->with('keywords',$keywords);
            } else {
                if(back()->gettargetUrl() == URL().'/confirm_checkout') {
                    return redirect('/');
                } else {
                    return back()->with('order_value_status', 'For order values less than ₹ '.$min_amount.', please use our mobile app.');
                }
            }
        } else {
            return back()->with('close_status', 'Oops! you got us at the wrong time. Please visit again between 10:30AM and 10:30PM');
        }
    }

    // public function verifyMobile(){
    //     return view('verify_mobile');
    // }

    public function logout(){
        Session::flush();
    }

    public function status(){
        echo "true";
        return view('success');
    }

    public function makePayment(CreateValidation $request)
    {
        $title="";
        $keywords="";
        $order_id= Session::get('id');
        $txnId=uniqid($order_id);
        $name=Input::get('name');
        $product_info= "Zapdel";
        $email=Input::get('email');
        $phone=Input::get('phone');
        $area=Input::get('area');
        $building=Input::get('building');
        $address=Input::get('address');
        $spl_instructs=Input::get('specialinstructions');
        $merchant_key = $this->merchant_key;
        $salt = $this->salt;
        $paymentMode = Input::get('delivery');
        $donation = Input::get('donation');
        $order= new Order();
        $orderDetailRow = $order->getOrderDishDetails($order_id);
        if(!empty($orderDetailRow)) {
            $order->updateOrderDetailsGuest($order_id, $email, $name, $address, $phone, $txnId, 0, $paymentMode,$donation,$spl_instructs);
            $results = DB::table('users')->where('phone','=',Input::get('phone'))->where('is_phone_verified','=', 1)->select('phone','id')->first();
            if(!empty($results)) {
                $orderDetails['id']= Session::get('id');
                $orderDetails['address']=$address;
                $finaldeductable = $order->getOrderInfo($order_id)->total_with_tax + $donation;
                if($paymentMode != 'cod')
                {
                    $checksum=hash('sha512',"$merchant_key|$txnId|$finaldeductable|$product_info|$name|$email|||||||||||$salt");
                    $payment['order_id']= $txnId;
                    $payment['amount']= $finaldeductable;
                    $payment['first_name']= $name;
                    $payment['product_info']= $product_info;
                    $payment['email']= $email;
                    $payment['phone']= $phone;
                    $payment['merchant_key']= $merchant_key;
                    $payment['salt']= $salt;
                    $payment['checksum']= $checksum;
                    $payment['surl']= $this->payu_surl;
                    $payment['furl']= $this->payu_furl;
                    $payment['payu_payment_url']= $this->payu_payment_url;
                    return view('payment_redirect',$payment)->with('title',$title)->with('keywords',$keywords);
                }
                $orderDetails['id']= Session::get('id');
                $orderDetails['address']=$address;
                $orderDetails['status']= 'success';
                $orderDetails['dona']=$donation;
                $orderDetails['total_with_tax']=$finaldeductable;
                $order->updatePaymentStatus(Session::get('id'),'success') ;
                $order->updateOrderStatus(Session::get('id'),'pending') ;
                $this->sendOrderConfirmationEmail(Session::get('id'));
                Session::forget('id');
                return view('success')->with('orderDetails',$orderDetails)->with('title',$title)->with('keywords',$keywords);
            } else {
                $this->sendOTP(Input::get('phone'));
                return back()->withInput(Input::all())->with('mobile_verification_status', 401);
            }
        } else {
            return back()->with('tray_empty_status', 'Sorry! your order can not be placed. Please add some food item(s) into your tray.');
        }
        
    }

    /* Payment successful - logic goes here. */
    public  function payuPaymentSuccess() {
        //echo "Payment Success" . "<pre>" . print_r( $_POST, true ) . "</pre>";
        $title="";
        $keywords="";
        $paymentDetails = $_POST;
        $order = new Order();
        $order->updatePaymentDetails($paymentDetails);
        $order_id = Session::get('id');
        $order_info = $order->getOrderInfo($order_id);
        $finaldeductable = $order_info->total_with_tax + $order_info->donation;
        $orderDetails['id']= Session::get('id');
        $orderDetails['status']= 'success';
        $orderDetails['address']= $order->getAddressForOrder(Session::get('id'));
        $orderDetails['total_with_tax'] = $finaldeductable;
        $orderDetails['dona'] = $order_info->donation;
        $order->updateOrderStatus(Session::get('id'),'pending');
        $this->sendOrderConfirmationEmail(Session::get('id'));
        Session::forget('id');
        return view('success')->with('orderDetails',$orderDetails)->with('title',$title)->with('keywords',$keywords);
    }
    
    /* Payment failed - logic goes here. */
    public  function payuPaymentFailure() {
        // echo "Payment Failure" . "<pre>" . print_r( $_POST, true ) . "</pre>";
        $title="";
        $keywords="";
		$paymentDetails= $_POST;
        $order = new Order();
        $order->updatePaymentDetails($paymentDetails);
        $order->updateOrderStatus(Session::get('id'),'await_payment') ;
        $orderDetails['id']= Session::get('id');
        $orderDetails['status']= 'failed';
        $orderDetails['address']= $order->getAddressForOrder(Session::get('id'));
        Session::forget('id');
        return view('success')->with('orderDetails',$orderDetails)->with('title',$title)->with('keywords',$keywords);
    }

    public function getCartDetails(){
        $order = new Order();
        $orderDetails['restaurant_details'] = $order->getRestaurantDetails(Session::get('id'));
        $orderDetails['dish_details'] = $order->getOrderDishDetails(Session::get('id'));
        $orderDetails['total'] = $order->getTotalPrice(Session::get('id'));

        return $orderDetails;
    }
	
	public function mylogin(Request $request){
        $title="";
        $keywords="";
		$name = $request->input('email');
		$password = $request->input('password');
		$customer = new Customer();
		$result = $customer->login($name, $password);
		if($result != null)
		{
			Session::put('user', $result);
			return view('auth.login')->with('user', $result)->with('status', 'login successful')->with('title',$title)->with('keywords',$keywords);
		}
		else
		{
			return view('auth.login')->with('status', 'login failed either user name password is wrong or user not exists')->with('title',$title)->with('keywords',$keywords);
		}
		
	}
	
	public function sendOrderConfirmationEmail($order_id) {
		
		$order_info = array();
		$user_info = array();
		$order = new Order();
                $order_info['address']= $order->getAddressForOrder($order_id);
		$order_info['customer']= $order->getCustomerInfoByOrderId($order_id);
		$order_info['order_info']= $order->getOrderDetailsForId($order_id);
        //return view('emails.order_place',compact('user_info','order','order_info'));
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


    /* Payment successful - logic goes here. */

    public  function paytmPaymentResponse() {
        
        //echo "Payment Success" . "<pre>" . print_r( $_POST, true ) . "</pre>";
        $title="";
        $keywords="";
        $paymentDetails= $_POST;
        $orderDetails['address'] = 'subarea, Sector 126, Noida, Uttar Pradesh 201313, India';
        $orderDetails['status'] = 'success';
        return view('success')->with('orderDetails',$orderDetails)->with('title',$title)->with('keywords',$keywords);
    }
    
    public function sitemap() {
        $title = "";
        $keywords = "";
        $restaurant = new Restaurant();
        $cities = $restaurant->getCities(false);
        $i = 0;
        foreach($cities as $city) {
            $results = DB::table('restaurant_details')
                    ->join('rest_cuisine_list', 'restaurant_details.id', '=', 'rest_cuisine_list.restaurant_id')
                    ->join('cuisine', 'cuisine.id', '=', 'rest_cuisine_list.cuisine_id')
                    ->join('city', 'city.id', '=', 'restaurant_details.city_id')
                    ->where('city.id', '=', $city->city_id)
                    ->where('restaurant_details.is_active', '=', 'open')
                    ->select(DB::raw("restaurant_details.name, restaurant_details.address, restaurant_details.url_name, group_concat(cuisine_name separator ', ') as cuisine_name"))
                    ->groupBy('rest_cuisine_list.restaurant_id')
                    ->orderBy('restaurant_details.name') 
                    ->get();
            $cities[$i]->restaurants = $results;
            $i++;
        }
        return view('sitemap')->with('cities',$cities)->with('title',$title)->with('keywords',$keywords);
    }

    public function sendOTP($mob)
    {
        $results = array();
        DB::table('otps')->where('mobile', '=', $mob)->delete();
        $otp = mt_rand(100000, 999999);
        $sendSMSResponse = $this->sendSMS($mob, $otp);
        //dd($sendSMSResponse);
        if(!empty($sendSMSResponse) && trim($sendSMSResponse[0]) == 'success') {
            $results = DB::table('otps')->insert(['mobile' => $mob, 'otp' => $otp, 'expire_on'=>24*60*60]);
        }
        return $results;
    }
    
    public function verify_otp($mobile, $verify_code)
    {
        $response_ar = array();
        $response_ar['verify_status'] = 0;
        if(!empty($mobile) && !empty($verify_code)) {
            $result = DB::table('otps')->where('otp','=',$verify_code)->where('mobile','=',$mobile)->first();
            if(!empty( $result)) {
                DB::table('users')->where('phone', $mobile)->update(['is_phone_verified' => 1]);
                $response_ar['verify_status'] = 1;
                $response_ar['row'] = $result;
            }
        }
        return $response_ar;

    }
    
    public function showZapdelHomely()
    {
        return view('zapdel_homely');
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

    public function userAccount()
    {
        return view('account');
    }



    /* SEO Pages */
    public function seoPagesFoodDeliveryRestaurants()
    {
        $city_url_name = "Noida";
        $title="Pizza Delivery | Online Food Order in Noida";
        $keywords="online food order in noida,online food delivery in noida,home delivery restaurants in noida,home delivery restaurants in greater noida,pizza delivery noida,order food online greater noida,online pizza order in noida,Free home delivery in noida";
        $description="Now online food order from the best home delivery restaurants in greater Noida only at Zapdel.We offers free home delivery services in Noida.";
        $city = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->first();
        
        $result = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('name','url_name','city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->get();
         return view('fooddeliveryrestaurant',compact('result','city'))->with('title',$title)->with('keywords',$keywords)->with('description',$description);    
    }

    public function seoPagesHomeDelivery()
    {
        $city_url_name = "Surat";
        $title="Get Home Delivery Food in Surat | Zapdel";
        $keywords="home delivery food in surat";
        $description="Now get free Home Delivery Food in Surat from trusted online food portal Zapdel.We help you to order food online from your favorite restaurant.";
        $city = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->first();

         $result = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('name','url_name','city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->get();
         return view('homedelivery',compact('result','city'))->with('title',$title)->with('keywords',$keywords)->with('description',$description);    
    }

    public function seoPagesOnlineOrder()
    {
       $city_url_name = "Lucknow";
       $title="Online Food Order in Lucknow | Zapdel";
        $keywords="online food order in lucknow";
        $description="Now place online food order in Lucknow from your favorite restaurant only at Zapdel.We provide testy food at home for foodies who love food delivered at their doorstep.";
        $city = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->first();

         $result = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('name','url_name','city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->get();
         return view('onlineorder',compact('result','city'))->with('title',$title)->with('keywords',$keywords)->with('description',$description);  
    }

     public function seoFoodDelivery()
    {
        $city_url_name = "Indore";
        $title="Food Order |Online Food Delivery in Indore";
        $keywords="Online food delivery in indore,Online food order in indore";
        $description="Get Online Food Order in Indore from the leading online food ordering marketplace Zapdel.We provide fresh & yummy food with fastest Online Food Delivery service in Indore.";
        $city = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->first();

         $result = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('name','url_name','city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->get();
         return view('fooddelivery',compact('result','city'))->with('title',$title)->with('keywords',$keywords)->with('description',$description);
    }

    public function seoFoodOrder()
    {
        $city_url_name = "Vadodara";
        $title="Food Delivery | Online Food Order in Vadodara";
        $keywords="food delivery in vadodara,Online food order in vadodara";
        $description="Get Online Food Order in Vadodara from the leading Online Food Ordering marketplace Zapdel.We provide fastest Food Delivery service in vadodara.";
        $city = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->first();

         $result = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('name','url_name','city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->get();
         return view('foodorder',compact('result','city'))->with('title',$title)->with('keywords',$keywords)->with('description',$description);    
    }

    public function seoHomeDeliveryRestaurant()
    {
        $city_url_name = "Ahmedabad";
        $title="Home Delivery Restaurants | Online Food Delivery in Ahmedabad";
        $keywords="online food order in ahmedabad,home delivery restaurants in ahmedabad,online food delivery in ahmedabad";
        $description="Zapdel help you to Online Food Order in Ahmedabad from your favorite Home Delivery Restaurant at very attractive price.";
        $city = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->first();

         $result = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('name','url_name','city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->get();
         return view('homedeliveryrestaurants',compact('result','city'))->with('title',$title)->with('keywords',$keywords)->with('description',$description);    
    }

    public function seoPizzaOrder()
    {
        $city_url_name = "Ghaziabad";
        $title="Online Pizza Order in Ghaziabad | Zapdel";
        $keywords="online pizza order in ghaziabad";
        $description="Now online Pizza Order in Ghaziabad from the leading online food ordering marketplace Zapdel.Order your Pizza now and enjoy the fastest Pizza delivery service in the city.";
        $city = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->first();

         $result = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('name','url_name','city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->get();
         return view('pizzaorder',compact('result','city'))->with('title',$title)->with('keywords',$keywords)->with('description',$description);
    }

    public function seoFoodDeliveryagra()
    {
        $city_url_name = "Agra";
        $title="Online Food Delivery in Agra | Zapdel";
        $keywords="Online food delivery in agra";
        $description="Get Online Food Delivery in Agra from trusted online food ordering marketplace Zapdel.We help you to order food online from your favorite restaurant.";
        $city = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->first();

         $result = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('name','url_name','city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->get();
         return view('fooddeliveryagra',compact('result','city'))->with('title',$title)->with('keywords',$keywords)->with('description',$description);
         
    }

    public function seoFoodOrderchandigarh()
    {
        $city_url_name = "chandigarh";
        $title="Online Food Order in Chandigarh | Zapdel";
        $keywords="Online food order in Chandigarh";
        $description="Get Online Food Order in Chandigarh from your favorite restaurant only at Zapdel.We provide testy food at home for foodies who love food delivered at their doorstep.";
        $city = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->first();

         $result = DB::table('restaurant_details')
        ->join('city','city.id','=','restaurant_details.city_id')
        ->select('name','url_name','city_name')
        ->where('city_name',$city_url_name)
        ->where('is_active','open')
        ->get();
         return view('foodorderchandigarh',compact('result','city'))->with('title',$title)->with('keywords',$keywords)->with('description',$description);  
    }
}