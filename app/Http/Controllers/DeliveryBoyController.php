<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\DeliveryBoy;
use App\Models\DeliveryBoyDevice;
use App\Models\Order;
use Validator;
use Illuminate\Http\Request;
use DB;
use DateTime;
class DeliveryBoyController extends Controller {
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index() {
        //
    }

    public function login(Request $request) {
        $name = $request->input('email');
        $password = $request->input('password');
        $deliveryBoy = new DeliveryBoy();
        $result = $deliveryBoy->login($name, $password);
        if($result != null) {
            $count = DB::table('delivery_boy_devices')
                        ->where('delivery_boy_id', $result->id)
                        ->select('id')
                        ->get();
            if(count($count) > 0) {
                DB::table('delivery_boy_devices')->where('delivery_boy_id', $result->id)->delete();
                return json_encode(array('status' => "200", 'message' => "On logged into this device you will be automatically logged out  from other devices.", 'acessTocken' => $result->id, 'info' => $result));
            }
            return json_encode(array('status' => "200", 'message' => 'successfully login', 'acessTocken' => $result->id, 'info' => $result));
        } else {
            return json_encode(array('status' => "300", 'message' => 'Name or Password is Incorrect.', 'error' => "Name or Password is Incorrect."));
        }
    }

    public function updateLocation(Request $request){
        $user_id = $request->input("userId");
        $lat = $request->input("lat");
        $lng = $request->input("lng");
        $time = $request->input('time');
                $validation_rules = array(
                    'userId'     =>  'required',
                    'lat'   =>  'required',
                    'lng'   =>  'required',
                    'time'   =>  'required'        
                );
                $validator = Validator::make($request->all(), $validation_rules);
                if ($validator->fails()) { 
                    $this->response['message'] = $validator->errors()->first();
                    $this->response['status'] = 201;
                    return $this->response;
                } else {
                    $deliveryBoy = new DeliveryBoy();
                    $result = $deliveryBoy->updateLocation($user_id, $lat, $lng, $time);
                    return json_encode(array('status' => "Location Updated"));
                }
        }


    public function deliveryBoyDeviceInfo(Request $request) {
        try {
            //$Request =  new Request();
            $validation_rules = array(
                'device_unique_id'     =>  'required',
                'push_token'   =>  'required',
                'os'   =>  'required'        
            );
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) { 
                $this->response['message'] = $validator->errors()->first();
                $this->response['status'] = 201;
                return $this->response;
            } else {
                $delivery_boy_device = DeliveryBoyDevice::where('device_unique_id', $request->input('device_unique_id'))->first();
                if(empty($delivery_boy_device)) {
                 $delivery_boy_device = new DeliveryBoyDevice;   
                }
                $delivery_boy_device->device_unique_id = $request->input('device_unique_id');
                $delivery_boy_device->push_token = $request->input('push_token');
                $delivery_boy_device->os = $request->input('os');
                if(!empty($request->input('model'))) {
                    $delivery_boy_device->model = $request->input('model');
                }
                if(!empty($request->input('os_version'))) {
                    $delivery_boy_device->os_version = $request->input('os_version');
                }
                if(!empty($request->input('app_version'))) {
                    $delivery_boy_device->app_version = $request->input('app_version');
                }
                if(!empty($request->input('app_version_code'))) {
                    $delivery_boy_device->app_version_code = $request->input('app_version_code');
                }
                if(!empty($request->input('latitude'))) {
                    $delivery_boy_device->latitude = $request->input('latitude');
                }
                if(!empty($request->input('longitude'))) {
                    $delivery_boy_device->longitude = $request->input('longitude');
                }
                if(!empty($request->input('delivery_boy_id'))) {
                    $delivery_boy_device->delivery_boy_id = $request->input('delivery_boy_id');
                }
                if($delivery_boy_device->save()) {
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

     public function newOrder(Request $request){
            $order_id = $request->input('order_id');
            $delivery_boy_id = $request->input('delivery_boy_id');
            // $is_accept = $request->input('is_accept');
            $time_of_assignment = date('Y-m-d H:i:s');
            $validation_rules = array(
                'order_id'     =>  'required',
                'delivery_boy_id'   =>  'required',
                // 'is_accept'   =>  'required'        
            );
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) { 
                $this->response['message'] = $validator->errors()->first();
                $this->response['status'] = 201;
                return $this->response;
            } else {
                $orderModel = new Order();
                // $order_info = DB::table('order')
             //                ->join('order_dish_details','order.id','=','order_dish_details.order_id')
             //                ->join('restaurant_dishes','order_dish_details.rest_id','=','restaurant_dishes.id')
             //                ->join('dish_details','restaurant_dishes.dish_id','=','dish_details.id')
             //                ->where('order.id', $order_id)
             //                ->first();

                $order_info = $orderModel->getOrderDetailsForId($order_id); 
                //dd($order_info['orderDishes']);
                $items = array();
                foreach ($order_info['orderDishes'] as $value) {
                    $val = $value->dish_id;


                }
                $delivery_boy_info = DB::table('delivery_boy_users')->where('id', $delivery_boy_id)
                                    ->first();
                // $order_info = DB::table()
                if(!empty($order_info) AND !empty($delivery_boy_info)) {
                    $result = DB::table('delivery_boy_order_assignments')
                                  ->insert(['order_id' => $order_id, 'delivery_boy_id' => $delivery_boy_id, 'time_of_assignment' => $time_of_assignment]);
                    if($result) {

                        $this->response['status'] = 200;
                        $this->response['order'] = ['order_id'=> $order_id, 'distance'=>'4 k.m away','customer'=> ['order'=> $order_info['order']->customer_name, 'address'=> $order_info['order']->address, 'lat'=> 124.123658, 'longitude'=> 1255.25887], 'status'=> 'generated', 'picker'=> 'none', 'bill'=>['total_amount'=> $order_info['order']->total_amount,'items'=> $order_info['orderDishes'], 'service_charge'=> $order_info['order']->total_sercharge]];
                        
                    }
                    return json_encode($this->response);
                    } else {
                        $this->response['status'] = 201;
                        $this->response['message'] = "order id or delivery boy id isn't valid.";
                        return json_encode($this->response);
                    }
                }
     }

     public function deliverySuccessful(Request $request){
            $order_id = $request->input('order_id');
            $delivery_boy_id = $request->input('delivery_boy_id');
            $delivery_completed_at = date('Y-m-d H:i:s');
            $validation_rules = array(
                'order_id'     =>  'required', 
                'delivery_boy_id'     =>  'required'      
            );
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) { 
                $this->response['message'] = $validator->errors()->first();
                $this->response['status'] = 201;
                return $this->response;
            } else {
                $validate = DB::table('delivery_boy_order_assignments')->where('order_id', $order_id)->where('delivery_boy_id', $delivery_boy_id)->first();
                if ($validate) {
                    $result = DB::table('delivery_boy_order_assignments')->where('order_id', $order_id)->where('delivery_boy_id', $delivery_boy_id)->update(['delivery_completed_at' => $delivery_completed_at]);
                    if ($result) {
                        $this->response['status'] = 200;
                        $this->response['message'] = "Order Successful.";
                        }
                    return ($this->response);
                    // } else {json_encode
                        $this->response['status'] = 201;
                        $this->response['message'] = "please enter valid order id or delivery boy id";
                        return json_encode($this->response);
                        }
                }
        }

        
       public function getPending(Request $request) {

           $delivery_boy_id = $request->input('authToken');
           $email = $request->input('email');
           $validation_rules = array('email' => 'required', 'authToken' => 'required'); 
           $validator = Validator::make($request->all(), $validation_rules);
           if ($validator->fails()) { 
               $this->response['message'] = $validator->errors()->first();
               $this->response['status'] = 201;
               return $this->response;
            } else {
                $order_count = DB::table('delivery_boy_order_assignments')
                                    ->join('order', 'delivery_boy_order_assignments.order_id', '=', 'order.id')
                                    ->join('order_status', 'order.order_status_id', '=', 'order_status.order_status_id')
                                    ->join('users', 'order.customer_id', '=', 'users.id')
                                    ->join('address', 'order.address_id', '=', 'address.id')
                                    ->where('delivery_boy_order_assignments.delivery_boy_id', $delivery_boy_id)
                                    ->where('delivery_boy_order_assignments.is_accept', 1)
                                    ->whereRaw('delivery_boy_order_assignments.delivery_completed_at < delivery_boy_order_assignments.time_of_assignment')
                                    ->count();

                $order_info = DB::table('delivery_boy_order_assignments')
                                    ->join('order', 'delivery_boy_order_assignments.order_id', '=', 'order.id')
                                    ->join('order_status', 'order.order_status_id', '=', 'order_status.order_status_id')
                                    ->join('users', 'order.customer_id', '=', 'users.id')
                                    ->join('address', 'order.address_id', '=', 'address.id')
                                    ->where('delivery_boy_order_assignments.delivery_boy_id', $delivery_boy_id)
                                    ->where('delivery_boy_order_assignments.is_accept', 1)
                                    ->whereRaw('delivery_boy_order_assignments.delivery_completed_at < delivery_boy_order_assignments.time_of_assignment')
                                    ->select('delivery_boy_order_assignments.time_of_assignment','delivery_boy_order_assignments.order_id','order.payment_status','order.delivery_type','order.donation', 'order.payment_mode','total_with_tax', 'address.address', 'users.phone','address.latitude', 'address.longitude', 'users.name')
                                    ->get();                    
                if (!empty($order_info)) {
                    $orderModel = new Order();
                    $DeliveryBoy = new DeliveryBoy();
                   foreach ($order_info as $value) {
                        $dish_details = $orderModel->getFullOrderDishDetails($value->order_id);
                        $order_taxes = $orderModel->getOrderTaxes($value->order_id);
                        $visited = $DeliveryBoy->getReachedRestaurant($value->order_id);
                        $customer_lat = !empty($value->latitude) ? $value->latitude : '77.123658';
                        $customer_lng = !empty($value->longitude) ? $value->longitude : '28.25887';
                        $order_assign_time = date('d/m/Y h:i A', strtotime($value->time_of_assignment));
                        $order_status = $DeliveryBoy->getOrderStatus($value->order_id);
                        $rest_id = $orderModel->getAllRestaurants($value->order_id);
                        $delivery_boy_location = DB::table('delivery_boy_location')
                                                                  ->select('latitude as lat', 'longitude as lng')
                                                                  ->where('delivery_boy_id', $delivery_boy_id)
                                                                  ->orderBy('location_time', 'DESC')
                                                                  ->take(1)
                                                                  ->first();
                                        //dd($delivery_boy_location);
                                        $values = array();
                                        foreach ($rest_id as $ids) {
                                            $values[] = $ids->id;
                                           }
                                           $restaurant_address = DB::select("SELECT id,name,address as 'add', phone, lat,lng , (3959 * 2 * ASIN(SQRT( POWER(SIN(( $delivery_boy_location->lat - lat) * pi()/180 / 2), 2) +COS( $delivery_boy_location->lat * pi()/180) * COS(lat * pi()/180) * POWER(SIN(( $delivery_boy_location->lng - lng) * pi()/180 / 2), 2) ))*1.60934) as distance from restaurant_details where id In ('" . implode("','",$values) . "') having distance order by distance asc ");
                        if ($order_status == 'In Progress') {
                            if (count($visited) == count($restaurant_address)) {
                                $order_status = 'Delivering';
                            }
                        }
                        $pending_list[] = ['bill' => ['items' =>$dish_details, 'orderTaxes'=> $order_taxes,'payment_status'=> $value->payment_status, 'delivery_type' => $value->delivery_type,'donation' => $value->donation,'payment_mode' => $value->payment_mode,'delivery_charge' =>'free', 'total' => $value->total_with_tax],  'customer'=>['add' => $value->address,'mobile' => $value->phone, 'lat' => $customer_lat, 'lng' => $customer_lng, 'name' => $value->name], 'order_assign_time' => $order_assign_time, 'dist' =>'7 k.m away', 'picker' => 1, 'restaurant' => $restaurant_address, 'status' => $order_status, 'uid' => $value->order_id, 'visited' => $visited];
                    }
                    $this->response['items'] = $pending_list;
                    $this->response['total_orders'] = $order_count;
                    $this->response['status'] = 200;
                    return json_encode($this->response);
                } else {
                    $this->response['message'] = "There is no pending order";
                    $this->response['status'] = 201;
                    return $this->response;
                } 
            }
        } 
     public function getPast(Request $request) {

           $delivery_boy_id = $request->input('authToken');
           $email = $request->input('email');
           $validation_rules = array('email' => 'required', 'authToken' => 'required'); 
           $validator = Validator::make($request->all(), $validation_rules);
           if ($validator->fails()) { 
               $this->response['message'] = $validator->errors()->first();
               $this->response['status'] = 201;
               return $this->response;
            } else {
                $order_count = DB::table('delivery_boy_order_assignments')
                                    ->join('order', 'delivery_boy_order_assignments.order_id', '=', 'order.id')
                                    ->join('order_status', 'order.order_status_id', '=', 'order_status.order_status_id')
                                    ->join('users', 'order.customer_id', '=', 'users.id')
                                    ->join('address', 'order.address_id', '=', 'address.id')
                                    ->where('delivery_boy_order_assignments.delivery_boy_id', $delivery_boy_id)
                                    ->where('delivery_boy_order_assignments.is_accept', 4)
                                    ->whereRaw('delivery_boy_order_assignments.delivery_completed_at > delivery_boy_order_assignments.time_of_assignment')
                                    ->count();                    
                $order_info = DB::table('delivery_boy_order_assignments')
                                    ->join('order', 'delivery_boy_order_assignments.order_id', '=', 'order.id')
                                    ->join('order_status', 'order.order_status_id', '=', 'order_status.order_status_id')
                                    ->join('users', 'order.customer_id', '=', 'users.id')
                                    ->join('address', 'order.address_id', '=', 'address.id')
                                    ->where('delivery_boy_order_assignments.delivery_boy_id', $delivery_boy_id)
                                    ->where('delivery_boy_order_assignments.is_accept', 4)
                                    ->whereRaw('delivery_boy_order_assignments.delivery_completed_at > delivery_boy_order_assignments.time_of_assignment')
                                    ->select('delivery_boy_order_assignments.order_id','order.payment_status','order.delivery_type','order.donation', 'order.payment_mode','total_with_tax', 'address.address', 'users.phone','address.latitude', 'address.longitude', 'users.name', 'time_of_assignment', 'delivery_completed_at')
                                    ->orderBy('delivery_boy_order_assignments.id', 'desc')
                                    ->get();                    
                if (!empty($order_info)) {
                    $orderModel = new Order();
                    foreach ($order_info as $value) {
                        $order_date = date('d/m/Y', strtotime($value->time_of_assignment));
                        $time_of_assignment = date('H:i A', strtotime($value->time_of_assignment));
                        $delivery_completed_at = date('H:i A', strtotime($value->delivery_completed_at));
                        $assigned_time= date('H:i:s', strtotime($value->time_of_assignment));
                        $completed_time = date('H:i:s', strtotime($value->delivery_completed_at));
                        $restaurant_address = $orderModel->getAllRestaurants($value->order_id);
                        $dish_details = $orderModel->getFullOrderDishDetails($value->order_id);
                        $order_taxes = $orderModel->getOrderTaxes($value->order_id);
                        $start = new DateTime($assigned_time);
                        $end = new DateTime($completed_time);
                        $time_taken = $start->diff($end);
                        // dd($order_info['orderDishes']);
                        $pending_list[] = ['bill' => ['items' =>$dish_details, 'orderTaxes'=> $order_taxes,'payment_status'=> $value->payment_status, 'delivery_type' => $value->delivery_type,'donation' => $value->donation,'payment_mode' => $value->payment_mode,'delivery_charge' =>'free', 'total' => $value->total_with_tax],'comment' => 'Delivered on time',  'customer'=>['add' => $value->address,'mobile' => $value->phone, 'lat' => $value->latitude, 'lng' => $value->longitude, 'name' => $value->name], 'distanceTraveled' =>'7 kms travelled', 'picker' => 1, 'restaurant' => $restaurant_address,'status' => 'completed', 'order_date' =>$order_date , 'assign_complete_time' => $time_of_assignment. ' - ' .$delivery_completed_at , 'timeTaken' => $time_taken->format("%H:%I") . " Hours", 'uid' => $value->order_id, 'visited' => [] ];
                    }
                    $this->response['items'] = $pending_list;
                    $this->response['status'] = 200;
                    $this->response['total_orders'] = $order_count;
                    return json_encode($this->response);
                } else {
                    $this->response['message'] = "There is no past order";
                    $this->response['status'] = 201;
                    return $this->response;
                } 
            }
        } 
        


        


        public function billingActivity(Request $request) {
        
            $order_id = $request->input('order_id');
            $validation_rules = array('order_id' => 'required'); 
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) { 
                $this->response['message'] = $validator->errors()->first();
                $this->response['status'] = 201;
                return $this->response;
            } else {
                
            }
        }
        
        public function isAccept(Request $request) {
            try {
                $validation_rules = array(
                    'email'     =>  'required',
                    'authToken'   =>  'required',
                    'order_id'   =>  'required',
                    'is_accept'   =>  'required'
                );
                $validator = Validator::make($request->all(), $validation_rules);
                if ($validator->fails()) {
                    $this->response['message'] = $validator->errors()->first();
                    $this->response['status'] = 201;
                    return $this->response;                    
                } else {
                    $order_id = $request->input('order_id');
                    $user_info = DB::table('delivery_boy_users')->where('id' , $request->input('authToken'))->where('email' , $request->input('email'))->first();

                    if (!empty($user_info)) {
                        $order_info = DB::table('delivery_boy_order_assignments')
                                        ->join('order','delivery_boy_order_assignments.order_id','=','order.id')
                                        ->where('delivery_boy_order_assignments.order_id' , $request->input('order_id'))
                                        ->where('delivery_boy_order_assignments.delivery_boy_id' , $request->input('authToken'))
                                        ->where('delivery_boy_order_assignments.is_accept' , 0)
                                        ->first();
                        if(!empty($order_info)) {
                            $time_of_acceptance = date('Y-m-d H:i:s');
                            $acceptance_status = DB::table('delivery_boy_order_assignments')
                                                ->where('delivery_boy_order_assignments.order_id' , $request->input('order_id'))
                                                ->where('delivery_boy_order_assignments.delivery_boy_id' , $request->input('authToken'))
                                                ->where('delivery_boy_order_assignments.is_accept' , 0)
                                                ->update(['is_accept' => $request->input('is_accept'), 'time_of_acceptance' => $time_of_acceptance]);
                            if($acceptance_status) {
                                $orderObject = new Order();
                                $order_info_full = $orderObject->getOrderDetailsForId($order_id); 
                                $rest_list = $orderObject->getAllRestaurants($order_id);
                                 $dish_details = $orderObject->getFullOrderDishDetails($order_id);
                                $customer_lat = !empty($order_info_full['order']->latitude) ? $order_info['order']->latitude : '77.123658';
                                $customer_lng = !empty($order_info_full['order']->longitude) ? $order_info_full['order']->longitude : '28.25887';
                                $order_data = ['uid'=> $order_id, 'visited' => array(), 'dist'=>'4 km away', 'order_assign_time' => $order_info->time_of_assignment,
                                      'customer'=> ['name'=> $order_info_full['order']->customer_name, 'add'=> $order_info_full['order']->address, 'lat'=> $customer_lat, 'lng'=> $customer_lng, 'mobile'=> $order_info_full['order']->cust_contact],'bill'=>['delivery_charge' =>'free', 'total'=> $order_info_full['order']->total_with_tax, 'serviceCharge'=> $order_info_full['order']->total_sercharge, 'payment_status'=> $order_info_full['order']->payment_status, 'delivery_type' => $order_info_full['order']->delivery_type,'donation' => $order_info_full['order']->donation,'payment_mode' => $order_info_full['order']->payment_mode, 'orderTaxes'=> $order_info_full['orderTaxes'], 'items'=> $dish_details],
                                      'restaurant' => $rest_list, 'status'=> 'generated', 'picker'=> 'none'];
                                $response['items'] = [$order_data];
                                $response['status'] = 200;
                                $response['message'] = "Order accepted succesfully";       
                                return json_encode($response);
                            } else {
                                $response['status'] = 201 ;
                                $response['message'] = "There is some problem in acceptance, please try again later";
                                return json_encode($response);
                            }
                        } else {
                            $response['status'] = 400 ;
                            $response['message'] = "It seems this order not assigned to you";
                            return json_encode($response);
                        }

                    } else {
                        $response['status'] = 400 ;
                        $response['message'] = "Please send valid email and authtoken";
                        return json_encode($response);
                    }
            }
        } catch (Exception $e) {
            
            
        }
        return $this->response;
     }



     public function updateOrder(Request $request) {

            $order_id = $request->input('order_id');
            $delivery_boy_id = $request->input('delivery_boy_id');
            $type = $request->input('type');
            $restaurant_id = $request->input('restaurant_id');
            if ($type == 'reached') {
                $validation_rules = array('order_id' => 'required', 'delivery_boy_id' => 'required', 'type' => 'required', 'restaurant_id' => 'required'); 
            } else {
                $validation_rules = array('order_id' => 'required', 'delivery_boy_id' => 'required', 'type' => 'required'); 
            }
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) { 
               $this->response['message'] = $validator->errors()->first();
               $this->response['status'] = 201;
               return $this->response;
            } else {
                $order_info = DB::table('delivery_boy_order_assignments')
                                    ->join('order', 'delivery_boy_order_assignments.order_id', '=', 'order.id')
                                    ->join('order_status', 'order.order_status_id', '=', 'order_status.order_status_id')
                                    ->join('users', 'order.customer_id', '=', 'users.id')
                                    ->join('address', 'order.address_id', '=', 'address.id')
                                    ->where('delivery_boy_order_assignments.delivery_boy_id', $delivery_boy_id)
                                    ->where('order.id', $order_id)
                                    ->select('delivery_boy_order_assignments.order_id','order.payment_status','order.delivery_type','order.donation', 'order.payment_mode','total_with_tax', 'address.address', 'users.phone','address.latitude', 'address.longitude', 'users.name')
                                    ->first();
                                   
                 if (!empty($order_info)) {
                    $orderModel = new Order();
                    $deliveryBoy = new DeliveryBoy();
                    if ($type == 'reached') {
                        $validate_restaurant_id = DB::table('delivery_boy_order_status')
                            ->where('order_id', $order_id)
                            ->where('restaurant_id', $restaurant_id)
                            ->where('type', '=', 'reached')
                            ->first();
                         if (empty($validate_restaurant_id)) {
                                $result = DB::table('delivery_boy_order_status')
                                     ->insert(['order_id' => $order_id, 'delivery_boy_id' => $delivery_boy_id, 'type' => $type, 'restaurant_id' => $restaurant_id, 'arrived_time' => date('Y-m-d H:i:s')]);
                        
                            }   
                        $order_status_update = 'In Progress';
                        
                    } elseif ($type == 'delivered') {
                        $validate_delivered = DB::table('delivery_boy_order_status')
                            ->where('order_id', $order_id)
                            ->where('type', '=', 'delivered')
                            ->first();
                        
                        if (empty($validate_delivered)) {
                            $result = DB::table('delivery_boy_order_status')
                                  ->insert(['order_id' => $order_id, 'delivery_boy_id' => $delivery_boy_id, 'type' => $type, 'arrived_time' => date('Y-m-d H:i:s')]);
                        
                        }
                        $order_status_update = 'Billing';
                    } elseif ($type == 'billed') {
                        $validate_billed = DB::table('delivery_boy_order_status')
                            ->where('order_id', $order_id)
                            ->where('type', '=', 'billed')
                            ->first();
                        if (empty($validate_billed)) {
                            $result = DB::table('delivery_boy_order_status')
                                  ->insert(['order_id' => $order_id, 'delivery_boy_id' => $delivery_boy_id, 'type' => $type, 'arrived_time' => date('Y-m-d H:i:s')]);
                        }
                        $order_status_update = 'Completed';
                        $delivery_completed_at = date('Y-m-d H:i:s');
                        $acceptance_status = DB::table('delivery_boy_order_assignments')
                                                ->where('delivery_boy_order_assignments.order_id' , $order_id)
                                                ->where('delivery_boy_order_assignments.delivery_boy_id' , $delivery_boy_id)
                                                ->where('delivery_boy_order_assignments.is_accept', 1)
                                                ->update(['delivery_completed_at' => $delivery_completed_at, 'is_accept' => 4]);
                            
                    }
                    $restaurant_address = $orderModel->getAllRestaurants($order_id);
                    $dish_details = $orderModel->getFullOrderDishDetails($order_id);
                    $order_taxes = $orderModel->getOrderTaxes($order_id);
                    $get_visited = $deliveryBoy->getReachedRestaurant($order_id);
                    if ($type == 'reached') {
                        if (count($get_visited) == count($restaurant_address)) {
                                $order_status_update = 'Delivering';
                        }
                    }
                    $pending_list = ['bill' => ['items' =>$dish_details, 'orderTaxes'=> $order_taxes,'payment_status'=> $order_info->payment_status, 'delivery_type' => $order_info->delivery_type,'donation' => $order_info->donation,'payment_mode' => $order_info->payment_mode, 'total' => $order_info->total_with_tax],'comment' => 'Delivered on time',  'customer'=>['add' => $order_info->address,'mobile' => $order_info->phone, 'lat' => $order_info->latitude, 'lng' => $order_info->longitude, 'name' => $order_info->name], 'distanceTraveled' =>'7 kms travelled', 'picker' => 1, 'restaurant' => $restaurant_address,'status' => $type, 'timeTaken' => '2 Compansation hour', 'uid' => $order_info->order_id, 'visited' => $get_visited ];
                    $this->response['items'] = $pending_list;
                    $this->response['order_status'] = $order_status_update;
                    $this->response['status'] = '200';
                    return $this->response;
                } else {
                    $this->response['message'] = 'It seems this order not related to you';
                    $this->response['status'] = '201';
                    return $this->response;
                }
            }
        }
        
        
        public function logout(Request $request) {
        $name = $request->input('email');
        $authToken = $request->input('authToken');
        $result = DB::table('delivery_boy_users')
            ->where('email', $name)
            ->where('id', $authToken)
            ->select('*')
            ->first();
                if(!empty($result)) {
                    $delivery_boy_device = DeliveryBoyDevice::where('delivery_boy_id', $authToken)->delete();
                    DB::table('delivery_boy_duty_logs')
                                    ->insert(['delivery_boy_id' => $authToken, 'duty_log_type' => 'OFF', 'log_time' => date('Y-m-d H:i:s')]);
                    return json_encode(array('status' => "200", 'message' => 'You are successfully logout'));
                } else {
                    return json_encode(array('status' => "300", 'message' => 'There is some issue with logout, please try again later'));
                }
    }

    public function dutyLog(Request $request){
            $delivery_boy_id = $request->input('delivery_boy_id');
            $email = $request->input('email');
            $duty_log_type = $request->input('duty_log_type');
            $validation_rules = array('delivery_boy_id' => 'required', 'email' => 'required', 'duty_log_type' => 'required');
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) { 
                $this->response['message'] = $validator->errors()->first();
                $this->response['status'] = 201;
                return $this->response;
            } else {
                $result = DB::table('delivery_boy_users')
                         ->where('email', $email)
                         ->where('id', $delivery_boy_id)
                         ->first();
                             if (!empty($result)) {
                                 $delivery_boy_duty_logs = DB::table('delivery_boy_duty_logs')
                                    ->insert(['delivery_boy_id' => $delivery_boy_id, 'duty_log_type' => $duty_log_type, 'log_time' => date('Y-m-d H:i:s')]);
                                $this->response['message'] = 'successfully';
                                $this->response['status'] = '200';
                                return $this->response;                 
                             } else {
                                $this->response['message'] = 'There is some issue';
                                $this->response['status'] = '201';
                                return $this->response; 
                             }
                    }
        }
      

    public function deliveryBoyDistanceTraveled(Request $request){
        $delivery_boy_id = $request->input('delivery_boy_id');
        $validation_rules = array('delivery_boy_id' => 'required');
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) { 
                $this->response['message'] = $validator->errors()->first();
                $this->response['status'] = 201;
                return $this->response;
            } else {
                $result = DB::table('delivery_boy_location')
                            ->where('delivery_boy_id', $delivery_boy_id)
                            ->select('latitude','longitude')
                            ->get();
                   foreach ($result as $value) {
                       $this->response['items'][] = $value;
                       
                   }  
                  return $this->response;
            }
    }

    public function markOrdersAsNotResponded(){
        $deliveryBoy = new DeliveryBoy();
        $markOrdersAsNotResponded = $deliveryBoy->markOrdersAsNotResponded();
        $this->response['items'] = $markOrdersAsNotResponded;
        return $this->response;

    }

    public function checkNewOrderAssignmentStatus(Request $request){
         $order_id = $request->input('order_id');
         $deliveryBoy = new DeliveryBoy();
         $checkNewOrderAssignmentStatus = $deliveryBoy->checkNewOrderAssignmentStatus($order_id);
         //dd($checkNewOrderAssignmentStatus);
         $found = 0;
         $orderInfo = array();
         if (!empty($checkNewOrderAssignmentStatus)) {
             $found = 1;
         }
         return array('found' => $found, 'orderInfo' => $orderInfo);

    }

    public function changePassword(Request $request){
        $email = $request->input('email');
        $current_password = $request->input('password');
        $new_password = $request->input('new_password');
        $validation_rules = array('email' => 'required', 'password' => 'required', 'new_password' => 'required');
        $validator = Validator::make($request->all(), $validation_rules);
         if ($validator->fails()) { 
                $this->response['message'] = $validator->errors()->first();
                $this->response['status'] = 201;
                return $this->response;
            } else {
                $result = DB::table('delivery_boy_users')
                            ->where('email', $email)
                            ->where('password', $current_password)
                            ->update(['password' => $new_password]);
                if ($result) {
                $this->response['message'] = "Password Changed";
                $this->response['status'] = 200;
                return $this->response;
                } else {
                    $this->response['message'] = "Incorrect password";
                    $this->response['status'] = 201;
                    return $this->response;
                
                }
        }
    }
}
