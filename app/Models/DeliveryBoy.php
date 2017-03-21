<?php
/**s
 * User: Rishabh
 * Date: 9/18/15
 * Time: 11:28 PM
 */

namespace App\Models;
use Illuminate\Support\Facades\DB;

class DeliveryBoy extends BaseModel {

	public function login($name, $password){
		$result = DB::table('delivery_boy_users')
			->where('email', $name)
			->where('password', $password)
			->select('*')
			->first();
		return $result;
	}

    public function updateLocation($user_id, $lat, $lng, $time){
        $id = DB::table('delivery_boy_location')
            ->insertGetId([
            	"delivery_boy_id" => $user_id,
            	"latitude" => $lat,
            	"longitude" => $lng,
            	"location_time" =>$time
            	]);
		return $id;
    }
    
    
    public function getLatestBoysLocation($city_url_name) {
        
//            $results = DB::table('delivery_boy_location')
//                   ->join('delivery_boy_users', 'delivery_boy_location.delivery_boy_id', '=', 'delivery_boy_users.id')
//                   ->join('city', 'city.city_name', '=', 'delivery_boy_users.city')
//                   ->where('city.city_url_name', '=', $city_url_name)
//                   ->select(DB::raw("delivery_boy_users.name,delivery_boy_users.phone,delivery_boy_users.city, delivery_boy_users.address, delivery_boy_users.email, t1.latitude, t1.longitude, t1.delivery_boy_id FROM delivery_boy_location t1
//  JOIN (SELECT delivery_boy_id, MAX(time) timestamp FROM delivery_boy_location GROUP BY delivery_boy_id) t2
//    ON t1.delivery_boy_id = t2.delivery_boy_id AND t1.time = t2.timestamp"))
//                   //->groupBy("delivery_boy_location.delivery_boy_id")
//                   //->orderBy('last_locaton','desc')
//                   ->get();
            $before10min = date('Y-m-d H:i:s', time()-(10*60));
            $current_time = date('Y-m-d H:i:s', time());
            //dd($before10min);
            $results = DB::select('select delivery_boy_users.name,
                                    delivery_boy_users.phone,
                                    delivery_boy_users.city,
                                    delivery_boy_users.address,
                                    delivery_boy_users.email,
                                    t1.latitude,
                                    t1.longitude,
                                    t1.delivery_boy_id
                                    FROM delivery_boy_location t1
                                    JOIN 
                                    (SELECT delivery_boy_id, MAX(location_time) timestamp FROM delivery_boy_location GROUP BY delivery_boy_id) t2
                                    ON t1.delivery_boy_id = t2.delivery_boy_id AND t1.location_time = t2.timestamp
                                    inner join 
                                    `delivery_boy_users` on t1.`delivery_boy_id` = `delivery_boy_users`.`id`
                                    inner join
                                    `city` on `city`.`city_name` = `delivery_boy_users`.`city` 
                                    where `city`.`city_url_name` = :city_url_name and t1.location_time BETWEEN :before10min and :current_time' , ['city_url_name' => $city_url_name, 'before10min' => $before10min, 'current_time' => $current_time]);
            
            //dd($results);
            return $results;
    }

    public function getDeliveryBoys($city_id) {

            $results = DB::select('select dbdl.delivery_boy_id, dbdl.log_time, dbdl.duty_log_type, delivery_boy_users.id, delivery_boy_users.name, delivery_boy_users.phone
            from delivery_boy_duty_logs dbdl
            inner join (select delivery_boy_id, max(log_time) as max_log_time, duty_log_type from delivery_boy_duty_logs group by delivery_boy_id) tm on dbdl.delivery_boy_id = tm.delivery_boy_id and dbdl.log_time = tm.max_log_time
            inner join delivery_boy_users on dbdl.delivery_boy_id = delivery_boy_users.id
            inner join city on city.city_name = delivery_boy_users.city
            where date(dbdl.log_time) = :on_today_date and dbdl.duty_log_type = :log_type_on and city.id = :given_city_id' , ['on_today_date' => date("Y-m-d"),'log_type_on' => 'ON', 'given_city_id' => $city_id]);
            return $results;
    }

     public function orderAssignment($delivery_boy_id, $order_id) {
            $date = date("Y-m-d H:i:s");
            $time = strtotime($date);
            $time1 = $time - (3 * 60);
            $checktime = date("Y-m-d H:i:s", $time1);
            $results = 0;
            $time_of_assignment = date('Y-m-d H:i:s');
            $validate_delivery_boy = DB::table('delivery_boy_order_assignments')
                                         ->where('order_id', $order_id)
                                         ->orderBy('id', 'DESC')
                                         ->first();
           if (!empty($validate_delivery_boy) && $validate_delivery_boy->is_accept ==4 ) {
                return 0;
           } else if (!empty($validate_delivery_boy) && $validate_delivery_boy->is_accept ==2 ) {
                $results = DB::table('delivery_boy_order_assignments')
                                ->insertGetId(['delivery_boy_id' => $delivery_boy_id, 'order_id' => $order_id, 'time_of_assignment' => date('Y-m-d H:i:s')]);

            } else if(!empty($validate_delivery_boy) &&  $validate_delivery_boy->is_accept == 0 && $checktime >= $validate_delivery_boy->time_of_assignment) {
                $results = DB::table('delivery_boy_order_assignments')
                           ->insertGetId(['delivery_boy_id' => $delivery_boy_id, 'order_id' => $order_id, 'time_of_assignment' => $time_of_assignment]);

                if ($results) {
                  DB::table('delivery_boy_order_assignments')
                    ->where('order_id', $order_id)
                    ->where('is_accept', 0)
                    ->where('time_of_assignment', '<', $time_of_assignment)
                    ->update(array('is_accept' => 3, 'time_of_acceptance' => date('Y-m-d H:i:s')));
                }
            } else if ((empty($validate_delivery_boy) || (!empty($validate_delivery_boy) && $validate_delivery_boy->is_accept ==3))) {
                $results = DB::table('delivery_boy_order_assignments')
                                ->insertGetId(['delivery_boy_id' => $delivery_boy_id, 'order_id' => $order_id, 'time_of_assignment' => date('Y-m-d H:i:s')]);
            }
            return $results;
    }
    
    public function getDeliveryBoyInfo($delivery_boy_id) {

            $results = DB::table('delivery_boy_users')
                           ->join('delivery_boy_devices', 'delivery_boy_users.id', '=', 'delivery_boy_devices.delivery_boy_id')
                           ->where('delivery_boy_users.id', $delivery_boy_id)
                           ->select('delivery_boy_users.id','delivery_boy_users.name','delivery_boy_users.phone', 'delivery_boy_devices.push_token')
                           ->first();
            return $results;
    }
    
    
    public function getAssignDeliveryBoyId($order_id) {
            $delivery_boy_id = 0;
            $validate_delivery_boy = DB::table('delivery_boy_order_assignments')
                                         ->where('order_id', $order_id)
                                         ->orderBy('id', 'DESC')
                                         ->first();
            if(!empty($validate_delivery_boy)) {
                $delivery_boy_id = $validate_delivery_boy->delivery_boy_id;
            }
            return $delivery_boy_id;
    }



    public function getReachedRestaurant($order_id){
            $visited = DB::table('delivery_boy_order_status')
                          ->join('restaurant_details', 'delivery_boy_order_status.restaurant_id', '=', 'restaurant_details.id')
                          ->where('order_id', $order_id)
                          ->where('type', '=', 'reached')
                          ->select('restaurant_id', 'name', 'address', 'lat', 'lng', 'phone')
                          ->groupBy('delivery_boy_order_status.restaurant_id')
                          ->get();
                     return $visited;     
            }



    public function deliveryBoysReports($city_url_name = 'All'){

           $delivery_query = DB::table('delivery_boy_users')
                          ->join('delivery_boy_location', 'delivery_boy_users.id', '=', 'delivery_boy_location.delivery_boy_id')
                          ->join('city', 'delivery_boy_users.city', '=', 'city.city_name');
            if(strtolower($city_url_name) != 'all') {
              $delivery_query->where('city.city_url_name', '=', $city_url_name);
            }
            $delivery_query->orderBy('city.city_name');
            $delivery_query->groupBy('delivery_boy_location.delivery_boy_id');
            $delivery_boys_report = $delivery_query->paginate(10);
            return $delivery_boys_report;
    }

    public function getOrderStatus($order_id) {
      $order_status = 'Pending';
      $status_row = DB::table('delivery_boy_order_status')
                             ->where('order_id', $order_id)
                             ->orderBy('id', 'desc')
                             ->first();
      if(!empty($status_row->type)) {
        if($status_row->type == 'reached') {
            $order_status = 'In Progress';
        } elseif ($status_row->type == 'delivered') {
            $order_status = 'Billing';
        } elseif ($status_row->type == 'billed') {
            $order_status = 'Completed';
          }

      } return $order_status;

    }

    public function isdeliveryBoysAvailable($delivery_boy_id) {
            $is_available = 0;
            $results = DB::select('select dbdl.delivery_boy_id, dbdl.log_time, dbdl.duty_log_type, delivery_boy_users.id, delivery_boy_users.name, delivery_boy_users.phone
            from delivery_boy_duty_logs dbdl
            inner join (select delivery_boy_id, max(log_time) as max_log_time, duty_log_type from delivery_boy_duty_logs group by delivery_boy_id) tm on dbdl.delivery_boy_id = tm.delivery_boy_id and dbdl.log_time = tm.max_log_time
            inner join delivery_boy_users on dbdl.delivery_boy_id = delivery_boy_users.id
            where date(dbdl.log_time) = :on_today_date and dbdl.duty_log_type = :log_type_on and dbdl.delivery_boy_id = :p_delivery_boy_id' , ['on_today_date' => date("Y-m-d"),'log_type_on' => 'ON', 'p_delivery_boy_id' => $delivery_boy_id]);
            if(!empty($results)) {
                 $is_available = 1;
            }
            return $is_available;
    }


    public function markOrdersAsNotResponded(){
          $date = date("Y-m-d H:i:s");
          $time = strtotime($date);
          $time1 = $time - (3 * 60);
          $checktime = date("Y-m-d H:i:s", $time1);
          $result = DB::table('delivery_boy_order_assignments')
                      ->where('is_accept', '=', 0)
                      ->where('time_of_assignment', '<=', $checktime)
                       ->update(['is_accept' => 3, 'time_of_acceptance' => date('Y-m-d H:i:s')]);

              return $result;
        }
   public function checkNewOrderAssignmentStatus($order_id){
          $date = date("Y-m-d H:i:s");
          $time = strtotime($date);
          $time1 = $time - (3 * 60);
          $checktime = date("Y-m-d H:i:s", $time1);
          $result = DB::table('delivery_boy_order_assignments')
                      ->where('is_accept', '=', 0)
                      ->where('time_of_assignment', '<=', $checktime)
                      ->where('order_id', $order_id)
                      ->update(['is_accept' => 3, 'time_of_acceptance' => date('Y-m-d H:i:s')]);
                      
          return $result;
        }

        public function getCityLocation($city_url_name){
          $result = DB::table('city')
                      ->where('city_url_name', $city_url_name)
                      ->select('default_lat', 'default_lng')
                      ->first();
          return $result;
        }


      }