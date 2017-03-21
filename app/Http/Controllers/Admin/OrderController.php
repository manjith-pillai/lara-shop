<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Input;
use Illuminate\Contracts\Auth\Guard;
use DB;
use Illuminate\Http\Request;
use Event;
use App\Events\OrderHistoryWasUpdated;
use App\Models\DeliveryBoy;
use PushNotification;


class OrderController extends Controller {
	
    protected $auth;
	
    public function __construct(Guard $auth) {
		$this->middleware('auth');
		$this->auth = $auth;
    }
    
    public function getOrderList() {
		if($this->auth->user()->is_admin) {
			$orderObject  = new Order();
			$orders = $orderObject->getOrders();
			return view('admin.orders.order_list', ['orders' => $orders]);
		}
    }
	
	
    public function getOrderDetail($order_id) {
            if($this->auth->user()->is_admin) {
                    $orderObject  = new Order();
                    $deliveryBoyObject = new DeliveryBoy();
                    $delivery_boy_list = new \stdClass();
                    if(!empty($order_id)) {
                        $orderObject::where('id', $order_id)
                                ->update(['is_viewed' => 1]);
                    }
                    $order_detail = $orderObject->getFullOrderDetailsByOrderId($order_id);
                    //dd($order_detail);
                    $assigned_delivery_boy_id = 0;
                    if(!empty($order_detail['order'][0])) {
                            $order_detail['order'] = $order_detail['order'][0];
                            $delivery_boy_list = $deliveryBoyObject->getDeliveryBoys($order_detail['order']->city_id);
                            $assigned_delivery_boy_id = $deliveryBoyObject->getAssignDeliveryBoyId($order_id);
                            $delivery_boys_info = $orderObject->getDeliveryBoyInformation($order_id);
                    }
                    $order_status_list = $orderObject->getOrderStatusList();
                    
                    return view('admin.orders.order_detail', ['order_detail' => $order_detail, 'order_status_list' => $order_status_list, 'delivery_boy_list' => $delivery_boy_list, 'assigned_delivery_boy_id' => $assigned_delivery_boy_id, 'delivery_boys_info' => $delivery_boys_info]);

            }
    }


	
	
	public function orderHistoryUpdate($order_id) {
		if($this->auth->user()->is_admin) {
			$orderObject  = new Order();
                        $deliveryBoyObject = new DeliveryBoy();
                        $delivery_boy_id = Input::get('delivery_boy_id');
                        $status = Input::get('statlist');
                        $statComment = Input::get('statComment');
                        $stat_notify = Input::get('notify',0);
			$order_detail = $orderObject->updateOrderHistory($order_id, $status, $statComment, $stat_notify);
                        if($stat_notify) {
                            $orderObject->order_id = $order_id;
                            $orderObject->statComment = $statComment;
                            $orderObject->history_status = $status;
                            Event::fire(new OrderHistoryWasUpdated($orderObject));
                        }
                        $assignment_message = '';
                        if(!empty($delivery_boy_id)) {
                            $deliveryBoyInfo = $deliveryBoyObject->getDeliveryBoyInfo($delivery_boy_id);
                            $is_delivery_boy_available = $deliveryBoyObject->isdeliveryBoysAvailable($delivery_boy_id);
                            if($is_delivery_boy_available) {
                                if(!empty($deliveryBoyInfo->push_token)) {
                                    $order_assignment_id = $deliveryBoyObject->orderAssignment($delivery_boy_id, $order_id);
                                    if($order_assignment_id) {
                                        $order_assign_time = date('d/m/Y h:i A', time());
                                        $order_info = $orderObject->getOrderDetailsForId($order_id); 
                                        $rest_list = $orderObject->getAllRestaurants($order_id);
                                        $rest_id = $orderObject->getAllRestaurants($order_id);
                                        $delivery_boy_location = DB::table('delivery_boy_location')
                                                                  ->select('latitude as lat', 'longitude as lng')
                                                                  ->where('delivery_boy_id', $delivery_boy_id)
                                                                  ->orderBy('location_time', 'DESC')
                                                                  ->take(1)
                                                                  ->first();
                                        //dd($delivery_boy_location);
                                        $values = array();
                                        foreach ($rest_id as $value) {
                                            $values[] = $value->id;
                                           }

                                            $nearest_rest = DB::select("SELECT * , (3959 * 2 * ASIN(SQRT( POWER(SIN(( $delivery_boy_location->lat - lat) * pi()/180 / 2), 2) +COS( $delivery_boy_location->lat * pi()/180) * COS(lat * pi()/180) * POWER(SIN(( $delivery_boy_location->lng - lng) * pi()/180 / 2), 2) ))*1.60934) as distance from restaurant_details where id In ('" . implode("','",$values) . "') having distance order by distance asc limit 1"); 
                                        foreach ($nearest_rest as $rest_detail) {
                                             $rest_id = $rest_detail->id;
                                            
                                        }
                                        $nearest_restaurant = DB::table('restaurant_details')
                                                                ->select('restaurant_details.id as id','restaurant_details.name', 'restaurant_details.address as add', 'restaurant_details.phone', 'restaurant_details.lat', 'restaurant_details.lng')
                                                                ->where('id', $rest_id)
                                                                ->first();
                                        
                                        $customer_lat = !empty($order_info['order']->latitude) ? $order_info['order']->latitude : '77.123658';
                                        $customer_lng = !empty($order_info['order']->longitude) ? $order_info['order']->longitude : '28.25887';
                                        $order_data = ['uid'=> $order_id, 'visited' => array(), 'dist'=>$rest_detail->distance, 'order_assign_time' => $order_assign_time,
                                                  'customer'=> ['name'=> $order_info['order']->customer_name, 'add'=> $order_info['order']->address, 'lat'=> $customer_lat, 'lng'=> $customer_lng, 'mobile'=> $order_info['order']->cust_contact],
                                                  'restaurant' => [$nearest_restaurant], 'status'=> 'generated', 'picker'=> 'none'];
                                        try {
                                            $collection = PushNotification::app('appNameAndroid')
                                                          ->to($deliveryBoyInfo->push_token)
                                                          ->send('ok', $order_data);
                                            $assignment_message = "Order assigned successfully to selected delivery boy.";
                                        } catch (Exception $e) {
                                            
                                        }

                                    } else {
                                        $assignment_message = "This order is already in-progress / completed.";
                                    }
                                    
                                } else {
                                    $assignment_message = "Please ask to delivery boy login in app.";
                                }
                            } else {
                                $assignment_message = "Delivery boy is not available for duty.";
                            }
                            
                        }
			return redirect('admin/order/order-detail/'.$order_id)->with('message', 'Order status updated')->with('assignment_message', $assignment_message);
		}
        }
    
    
        public function migrate_restaurants() {
            ini_set('memory_limit','1024M');
            $b_restaurants = DB::connection('mysql_boiban')->table('bb_resturant')
                        ->join('bb_resturant_description', 'bb_resturant.resturant_id', '=', 'bb_resturant_description.resturant_id')
                        ->where('status',1)
                        ->orderBy('bb_resturant.resturant_id')
                        ->get();
            echo "<pre>";
            if(!empty($b_restaurants)) {
                $restaurant = new Restaurant();
                foreach($b_restaurants as $b_restaurant) {
                    $restaurant_city_id = $b_restaurant->city_id;
                    if($restaurant_city_id == 0 && $b_restaurant->area_id != 0) {
                        $restaurant_city_id = $restaurant->getCityIdByAreaId($b_restaurant->area_id);
                    }
                    if(($restaurant_city_id != 0) && (!$restaurant->isRestaurantExist($b_restaurant->name, $restaurant_city_id, $b_restaurant->area_id))) {
                        print_r($b_restaurant);

                        $restaurant_url = $this->generateRestaurantUrl($b_restaurant->name, $b_restaurant->area_id, $restaurant_city_id);
                            $last_restaurant_id = DB::table('restaurant_details')
                                                ->insertGetId([
                                                    'name' => $b_restaurant->name,
                                                    'address' => $b_restaurant->address_1,
                                                    'city_id' => $restaurant_city_id,
                                                    'area_id' => $b_restaurant->area_id,
                                                    'contact_person' => $b_restaurant->contact_person_name,
                                                    'phone' => $b_restaurant->resturant_phone,
                                                    'email' => $b_restaurant->email,
                                                    'contact_person_phone' => $b_restaurant->contact_person_phone,
                                                    'timing' => $b_restaurant->restaurant_timing,
                                                    'img' => $b_restaurant->image,
                                                    'tax_id' => ($b_restaurant->tax_class_id !=0) ? $b_restaurant->tax_class_id : 1,
                                                    'url_name' => $restaurant_url,
                                                    'cost_two_people' => 0
                                                ]);
                            if($last_restaurant_id) {
                                $this->updateRestaurantTaxes($b_restaurant->tax_class_id, $last_restaurant_id);
                                $b_rest_cuisines = DB::connection('mysql_boiban')->table('bb_resturant_cuisine')
                                        ->join('bb_cuisine', 'bb_resturant_cuisine.cuisine_id', '=', 'bb_cuisine.cuisine_id')
                                        ->where('resturant_id', $b_restaurant->resturant_id)
                                        ->get();

                                if(!empty($b_rest_cuisines)) {
                                    foreach ($b_rest_cuisines as $b_rest_cuisine) {
                                        $cuise_id_for_rest = $restaurant->getCuisineIdByName($b_rest_cuisine->name);
                                        $last_cuisine_restaurant_id = DB::table('rest_cuisine_list')
                                                ->insertGetId([
                                                    'restaurant_id' => $last_restaurant_id,
                                                    'cuisine_id' => $cuise_id_for_rest
                                         ]);
                                    }
                                }
                                $b_rest_categories = DB::connection('mysql_boiban')->table('bb_resturant_category')
                                        ->join('bb_category', 'bb_resturant_category.category_id', '=', 'bb_category.category_id')
                                        ->join('bb_category_description', 'bb_category.category_id', '=', 'bb_category_description.category_id')
                                        ->join('bb_product', 'bb_resturant_category.resturant_category_id', '=', 'bb_product.resturant_category_id')
                                        ->join('bb_product_description', 'bb_product.product_id', '=', 'bb_product_description.product_id')
                                        ->where('bb_resturant_category.resturant_id', $b_restaurant->resturant_id)
                                        ->select('*','bb_category_description.name as category_name','bb_product_description.description as product_desc')
                                        ->get();
                                if(!empty($b_rest_categories)) {
                                    foreach ($b_rest_categories as $b_rest_category) {
                                        $category_id_rest = $restaurant->getCategoryIdByName($b_rest_category->category_name);
                                        if($category_id_rest) {
                                            $dish_id_rest = $restaurant->getDishIdByName($b_rest_category->name, $category_id_rest, $b_rest_category->category_name, $b_rest_category->product_desc);
                                            if($dish_id_rest) {
                                                DB::table('restaurant_dishes')
                                                        ->insertGetId([
                                                            'restaurant_id' => $last_restaurant_id,
                                                            'dish_id' => $dish_id_rest,
                                                            'price' => $b_rest_category->price,
                                                            'package_charge' => $b_rest_category->packaging_charge
                                                  ]);
                                            }
                                        }
                                    }
                                }
                            }
                       }
                    }
                }
                exit;
            }
                

    public function generateRestaurantUrl($restaurant_name, $restaurant_area_id, $restaurant_city_id) {
                    $restaurant = new Restaurant();
                    if(!empty($restaurant_area_id)) {
                        $restaurant_area = $restaurant->getAreaByAreaId($restaurant_area_id);
                    }
                    if(empty($restaurant_area)) {
                        $restaurant_area = $restaurant->getCityByCityId($restaurant_city_id);
                    }
                    $restaurant_url = strtolower(str_replace(' ', '_', $restaurant_name.'_'.$restaurant_area));
                    return $restaurant_url;
    }
    
    public function migrate_restaurant_taxes() {
        echo '<pre>';
        $b_restaurants = DB::connection('mysql_boiban')->table('bb_resturant')
                        ->join('bb_resturant_description', 'bb_resturant.resturant_id', '=', 'bb_resturant_description.resturant_id')
                        ->where('status',1)
                        ->orderBy('bb_resturant.resturant_id')
                        ->get();
                
        if(!empty($b_restaurants)) {
            $restaurant = new Restaurant();
            foreach($b_restaurants as $b_restaurant) {
                print_r($b_restaurant);
                $restaurant_city_id = $b_restaurant->city_id;
                if($restaurant_city_id == 0 && $b_restaurant->area_id != 0) {
                    $restaurant_city_id = $restaurant->getCityIdByAreaId($b_restaurant->area_id);
                }
                $restaurant_id = $restaurant->isRestaurantExist($b_restaurant->name, $restaurant_city_id, $b_restaurant->area_id);
                if($restaurant_id) {
                    var_dump($restaurant_id);
                    
                    $b_restaurant_taxes = DB::connection('mysql_boiban')->table('bb_tax_class')
                        ->join('bb_tax_rule', 'bb_tax_class.tax_class_id', '=', 'bb_tax_rule.tax_class_id')
                        ->join('bb_tax_rate', 'bb_tax_rule.tax_rate_id', '=', 'bb_tax_rate.tax_rate_id')
                        ->where('bb_tax_class.tax_class_id', $b_restaurant->tax_class_id)
                        ->orderBy('bb_tax_class.tax_class_id')
                        ->get();
                    
                    if(!empty($b_restaurant_taxes)) {
                        print_r($b_restaurant_taxes);
                        $restaurent_taxes_data = array();
                        foreach ($b_restaurant_taxes as $b_restaurant_tax) {
                            if (preg_match("/Service Tax/", $b_restaurant_tax->name)) {
                                $restaurent_taxes_data['service_tax_percent'] = $b_restaurant_tax->rate;
                            }
                            if (preg_match("/Restaurant VAT/", $b_restaurant_tax->name)) {
                                $restaurent_taxes_data['vat_percent'] = $b_restaurant_tax->rate;
                            }
                            if (preg_match("/S T &amp; CESS/", $b_restaurant_tax->name)) {
                                $restaurent_taxes_data['cess'] = $b_restaurant_tax->rate;
                            }
                            print_r($restaurent_taxes_data);
                        }
                        if(!empty($restaurent_taxes_data)) {
                            $restaurant->updateRestTax($restaurant_id, $restaurent_taxes_data);
                        }
                        //print_r($restaurent_taxes_data);
                    }
                    
                    
                }
            }
        }
    }
    
    
    public function checkLatestOrder(Request $request) {
        $current_time = date('Y-m-d H:i:00', time($request->input('current_time')));
        $found = 0;
        $orderInfo = array();
        if($current_time) {
            $orderInfo = DB::table('order')
                        ->join('order_status', 'order_status.order_status_id', '=', 'order.order_status_id')
                        ->select('order.id','order_status.name as order_status')
                        ->where('order.order_place_time','>=', $current_time)
                        ->where('order_status.name','=', 'Pending')
                        ->orderBy('order.order_place_time')
                        ->first();
            if(!empty($orderInfo)) {
                $found = 1;
            }
        }
        return array('found' => $found, 'orderInfo' => $orderInfo);
    }
    
    public function updateRestaurantTaxes($tax_class_id, $restaurant_id) {
        if(!empty($restaurant_id)) {
            $restaurant = new Restaurant();
            $b_restaurant_taxes = DB::connection('mysql_boiban')->table('bb_tax_class')
                        ->join('bb_tax_rule', 'bb_tax_class.tax_class_id', '=', 'bb_tax_rule.tax_class_id')
                        ->join('bb_tax_rate', 'bb_tax_rule.tax_rate_id', '=', 'bb_tax_rate.tax_rate_id')
                        ->where('bb_tax_class.tax_class_id', $tax_class_id)
                        ->orderBy('bb_tax_class.tax_class_id')
                        ->get();   
            if(!empty($b_restaurant_taxes)) {
                print_r($b_restaurant_taxes);
                $restaurent_taxes_data = array();
                foreach ($b_restaurant_taxes as $b_restaurant_tax) {
                    if (preg_match("/Service Tax/", $b_restaurant_tax->name)) {
                        $restaurent_taxes_data['service_tax_percent'] = $b_restaurant_tax->rate;
                    }
                    if (preg_match("/Restaurant VAT/", $b_restaurant_tax->name)) {
                        $restaurent_taxes_data['vat_percent'] = $b_restaurant_tax->rate;
                    }
                    if (preg_match("/S T &amp; CESS/", $b_restaurant_tax->name)) {
                        $restaurent_taxes_data['cess'] = $b_restaurant_tax->rate;
                    }
                    print_r($restaurent_taxes_data);
                }
                if(!empty($restaurent_taxes_data)) {
                    $restaurant->updateRestTax($restaurant_id, $restaurent_taxes_data);
                }
            }
        }       
    }
    
    
    public function deliveryBoysLocation($city_url_name) {
        if($this->auth->user()->is_admin) {
            $restaurant = new Restaurant();
            $cities = $restaurant->getCities();
            $deliveryBoy = new DeliveryBoy();
            $boys_locations = $deliveryBoy->getLatestBoysLocation($city_url_name);
            $cityLocation = $deliveryBoy->getCityLocation($city_url_name);
            $title = "Zapdel - Track Delivery Boys";
            return view ('track_boys_location',compact('title','boys_locations','cities','city_url_name', 'cityLocation'));
        }
    }



    public function deliveryBoysReports($city_url_name = 'All'){
        if($this->auth->user()->is_admin) {
            $DeliveryBoy  = new DeliveryBoy();
            $reports = $DeliveryBoy->deliveryBoysReports($city_url_name);
            $restaurant = new Restaurant();
            $cities = $restaurant->getCities();
            return view('admin.orders.delivery_boy_reports', ['reports' => $reports, 'cities' => $cities, 'city_url_name' => $city_url_name]);
        }
    }


}
