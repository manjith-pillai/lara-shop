<?php
/**s
 * User: Rishabh
 * Date: 9/18/15
 * Time: 11:28 PM
 */

namespace App\Models;
use Illuminate\Support\Facades\DB;

class Order extends BaseModel {
    
    protected $table = 'order';
    
    public function generateOrderId($restDishId,$quantity){
        
        $restaurant_model = new Restaurant();
        $city_id = $restaurant_model->getCityIdByRestDishId($restDishId);
        $orderId = DB::table('order')->insertGetId([
            'customer_id'=>null,
            'address_id'=>null,
            'city_id'=>$city_id,
            'total_amount'=>0,
            'payment_status'=>'pending'
        ]);
        $orderId=self::addToCart($orderId,$restDishId,$quantity);
        return $orderId;
    }

    /*
    *   Calculates the necessary taxes using the simple logic 
    *   1) Calcuate all applicable vat for all dishes of the resturant.
    *   2) Calculate service tax for all applicable resturants
    *   3) Calcualte total applicable taxes for the order.
    */
    public function calculateTaxes($orderId) 
    {
        DB::table('order_rest_tax_details')->where('order_id','=',$orderId)->delete();

        $rows = DB::insert(DB::raw(
        "insert  into order_rest_tax_details (order_id, rest_id, rest_amount, vat, sercharge, servtax, package_charge) (select order_id, restaurant_id , sum(rest_amount) as rest_cost , if( sum(vat) > 0 , sum(vat)  ,  sum(rest_amount) * vat_percent / 100 ) as rest_vat,  (sum(rest_amount) * service_charge_percent / 100) as rest_charge , (sum(rest_amount) * service_tax_percent / 100) as rest_service, if( sum(pack_charge) > 0 , sum(pack_charge) ,  packaging_charge) as packaging_charge from  (select b.order_id , a.restaurant_id , ((a.price * b.quantity * a.vat_percent)/100) as vat , a.price*b.quantity as rest_amount ,a.package_charge * b.quantity as pack_charge  from  restaurant_dishes a, order_dish_details b where b.order_id = :orderId  and a.id = b.rest_dish_id) c, restaurant_details d , rest_tax_details e  where c.restaurant_id =  d.id and e.tax_id = d.tax_id  group by restaurant_id)"), 
            array('orderId' => $orderId));

        $rows = DB::select(DB::raw("select order_id , sum(rest_amount) as total_amount,  sum(vat) as total_vat, sum(servtax) as total_servtax, sum(sercharge) as  total_sercharge,  sum(package_charge) as total_packcharge from order_rest_tax_details where order_id = :orderId"), array('orderId' => $orderId)) ;
        foreach ($rows as $row) 
        {
            $order_id = !empty($row->order_id) ? $row->order_id : $orderId;
            $total_amount = $row->total_amount ;
            $total_vat = $row->total_vat ;
            $total_servtax = $row->total_servtax ;
            $total_sercharge = $row->total_sercharge ;
            $total_packcharge = $row->total_packcharge ;
             DB::table('order')
                    ->where('id', $order_id)
                    ->update(['total_amount' => $total_amount,
                        'total_vat'=>$total_vat,
                        'total_servtax' => $total_servtax,
                        'total_sercharge'=>$total_sercharge,
                        'total_packcharge' => $total_packcharge,
                        'total_with_tax' => $total_amount+$total_vat+$total_servtax+
                                $total_sercharge+$total_packcharge]);
        }


//      Update the Order table with all taxes 

    }

    public function addToCart($orderId,$restDishId,$quantity){

        if($quantity==0)
        {
            DB::delete("DELETE FROM `order_dish_details` WHERE `order_id` = '$orderId' AND `rest_dish_id` = '$restDishId'");
            self::calculateTaxes($orderId);
            return $orderId;
        }
        else
        {
            $rows=DB::table('restaurant_dishes')
                ->select('price', 'restaurant_id')
                ->where('id',$restDishId)
                ->get();

            foreach($rows as $row)
            {
                $price= $row->price;
                $rest_id= $row->restaurant_id;
            }

            $flag=DB::table('order_dish_details')
                ->where('order_id', $orderId)
                ->where('rest_dish_id',$restDishId)
                ->select('*')
                ->get();
            
            if(count($flag)!=0){
                DB::table('order_dish_details')
                    ->where('order_id', $orderId)
                    ->where('rest_dish_id',$restDishId)
                    ->update(['quantity' => $quantity,
                        'price'=>($price)*$quantity]);
            }else{
                DB::table('order_dish_details')->insert([
                    'order_id'=>$orderId,
                    'rest_dish_id'=>$restDishId,
                    'quantity'=>$quantity,
                    'rest_id' => $rest_id,
                    'price'=>($price)*$quantity
                ]);
            }
        }
        self::calculateTaxes($orderId);
        return $orderId;
    }

    public function getRestaurantDetails($orderId){
        $orderDetails=DB::table('order_dish_details')
            ->select('*')
            ->join('restaurant_dishes', 'restaurant_dishes.id', '=', 'order_dish_details.rest_dish_id')
            ->join('restaurant_details', 'restaurant_details.id', '=', 'restaurant_dishes.restaurant_id')
            ->where('order_id',$orderId)
            ->first();
        return $orderDetails;
    }

    public function getOrderDetailsForId($id)
    {
        $order['order'] = self::getOrderInfo($id) ;
        if(!empty($order['order'])) {
            $address_info = self::getAddressForOrder($id,1) ;
            $order['order']->address = isset($address_info->address) ? $address_info->address : '';
            $order['order']->address_name = isset($address_info->address_name) ? $address_info->address_name : '';
        }
        $order['orderDishes'] = self::getOrderDishDetails($id) ;
        $order['orderTaxes'] = self::getOrderTaxes($id) ;
        $order['total'] = self::getTotalPrice($id);
        return $order;
    }

    public function getOrderInfo($id)
    {   $orderInfo=DB::table('order')
            ->join('order_status', 'order_status.order_status_id', '=', 'order.order_status_id')
            ->select('*','order_status.name as order_status')
            ->where('order.id',$id)
            ->first();
        return $orderInfo;
    }
    
	public function deletepreviousdish($id)
	{
		DB::table('order_dish_details')->where('order_id','=',$id)->delete();
	}

    public function getOrderTaxes($id)
    {
        $orderInfo=DB::table('order_rest_tax_details')
            ->select('*')
            ->where('order_id',$id)
            ->get();
		return $orderInfo;
    }

    public function updateOrder($id, $cust_email, $customer_name, $cust_contact, $customer_id, $spl_instructs, $address, $paymentmode, $paymentstatus, $donation, $delivery_type, $order_status, $order_source, $address_name = '', $addressId = '') 
    {
                $query = DB::table('order')->where('id', $id);
                if($customer_name) {
                    $query->update(['customer_name' => $customer_name]);
                }
                if($cust_contact) {
                    $query->update(['cust_contact' => $cust_contact]);
                }
                if($customer_id) {
                    $query->update(['customer_id' => $customer_id]);
                }
                if($spl_instructs) {
                    $query->update(['spl_instruct' => $spl_instructs]);
                }
                if($address) {
                    $customer= new Customer();
                    $customerId=$customer->getCustomerId($cust_email,$customer_name,$cust_contact);
                    if($customerId) {
                        $query->update(['customer_id' => $customerId]);
                    }
                    if($addressId) {
                        $addressId = $customer->setDefaultAddress($addressId, $customerId);
                    } else {
                        $addressId = $customer->insertAddress($customerId, $address, $address_name);
                    }
                    if($addressId) {
                        $query->update(['address_id' => $addressId]);
                    }
                }
                if($paymentmode) {
                    $query->update(['payment_mode' => $paymentmode]);
                }
                if($paymentstatus) {
                    $query->update(['payment_status' => $paymentstatus]);
                }
                if($donation) {
                    $query->update(['donation' => $donation]);
                }
                if($delivery_type) {
                    $query->update(['delivery_type' => $delivery_type]);
                }
                if($order_status) {
                    $order_place_time = date('Y-m-d H:i:s');
                    $order_status_id = self::getOrderStatusIdByName($order_status);
                    $query->update(['order_status_id' => $order_status_id, 'order_place_time' => $order_place_time]);
                }
                if(!empty($order_source)) {
                    $query->update(['order_source' => $order_source]);
                }
                $query = null ;
                
       }



    public function getOrderDishDetails($id){
        $orderDetails=DB::table('order_dish_details')
            ->select('*','restaurant_details.id as restaurant_id')
            ->join('restaurant_dishes', 'restaurant_dishes.id', '=', 'order_dish_details.rest_dish_id')
			->join('restaurant_details', 'restaurant_dishes.restaurant_id', '=', 'restaurant_details.id')
            ->join('dish_details', 'dish_details.id', '=', 'restaurant_dishes.dish_id')
            ->where('order_id',$id)
            ->get();
        return $orderDetails;
    }

    public function getAddressForOrder($id, $full_info = false) {
        $fields = "address.address";
        if($full_info) {
            $fields = "address.*";
        }
        $row = DB::table('order')
            ->join('address' , 'address_id' , '=' , 'address.id' )
            ->select($fields)
            ->where('order.id',$id)
            ->first();
        if($full_info) {
            return  $row;
        }
        return  isset($row->address) ? $row->address : '';
    }

    public function getTotalPrice($id){
        $row=DB::table('order')
            ->select('id','total_amount')
            ->where('id',$id)
            ->get();
        $details=array();
        foreach($row as $row)
        {
            $details['total_amount']= $row->total_amount;
            $details['id']= $row->id;
        }
        return $details;
    }


    public function updateOrderDetailsGuest($orderId,$email,$name,$address,$phone,$paymentId,$totalAmount,$paymentMode,$donation,$spl_instructs){
        $customer= new Customer();
        $customerId=$customer->getCustomerId($email,$name,$phone);
        $addressId=$customer->insertAddress($customerId,$address);

        DB::table('order')
            ->where('id', $orderId)
            ->update([
                'txn_id'=>$paymentId,
                'address_id'=>$addressId,
                'customer_id'=>$customerId,
                'customer_name' => $name,
                'cust_contact' => $phone,
                'payment_mode'=>$paymentMode,
                'donation'=>$donation,
                'spl_instruct'=>$spl_instructs,
                'order_source' => 'web'
            ]);
    }
    
    
    public function updatePaymentDetails($paymentDetails)
    {
        $jsonResponse= json_encode($paymentDetails);
        $txnid=$paymentDetails['txnid'];
        DB::table('pg_audit_log')->insert([
            'txn_id'=>$txnid,
            'response'=>$jsonResponse
        ]);
        //payment_status based on txnid
        DB::table('order')
            ->where('txn_id', $txnid)
            ->update(['payment_status' => $paymentDetails['status']
            ]);
    }

    //payment_status based on order id
    public function updatePaymentStatus($id,$status)
    {
        //payment_status
        DB::table('order')
            ->where('id', $id)
            ->update(['payment_status' => $status]);
    }

    //order_status based on order id
    public function updateOrderStatus($id, $status) {
        $order_place_time = date('Y-m-d H:i:s');
        //payment_status
        $order_status_id = self::getOrderStatusIdByName($status);
        DB::table('order')
            ->where('id', $id)
            ->update(['order_status_id' => $order_status_id, 'order_place_time' => $order_place_time]);
    }
    

    public function updateSpecialInstructions($id,$splinstructs)
    {
        DB::table('order')
            ->where('id', $id)
            //->update(['spl_instruct' => $splinstructs,'order_status' => 'pending' ]); commnet by saran for order status as discuss with Rohit
            ->update(['spl_instruct' => $splinstructs]);
    }

    public function getHistory($restaurant_id, $from_date, $to_date){
        $data = DB::table('restaurant_dishes')
            ->join('dish_details', 'dish_details.id', '=', 'restaurant_dishes.dish_id')
            ->join('order_dish_details', 'order_dish_details.rest_dish_id', '=', 'restaurant_dishes.id')
            ->join('order', 'order.id' , '=', 'order_dish_details.order_id')
            ->where('restaurant_dishes.restaurant_id',$restaurant_id)
            ->where(DB::raw('Date(order.created_time)'), '>=', $from_date)
            ->where(DB::raw('Date(order.created_time)'), '<=', $to_date)
            ->select('order_dish_details.order_id','order.created_time', 'order_dish_details.rest_dish_id',
                'order_dish_details.quantity', 'dish_details.dish_name', 'restaurant_dishes.price')
            ->get();

        $order_ids = DB::table('restaurant_dishes')
            ->join('order_dish_details', 'order_dish_details.rest_dish_id', '=', 'restaurant_dishes.id')
            ->join('order', 'order.id' , '=', 'order_dish_details.order_id')
			->join('address', 'address.id' , '=', 'order.address_id')
            ->where('restaurant_dishes.restaurant_id',$restaurant_id)
            ->where(DB::raw('Date(order.created_time)'), '>=', $from_date)
            ->where(DB::raw('Date(order.created_time)'), '<=', $to_date)
            ->where(function($q)
            {
                //$q->where('order.order_status', 'accepted')
                  //->orWhere('order.order_status', 'rejectedByRestaurant');
            })
            ->select('order_dish_details.order_id','restaurant_dishes.restaurant_id', 'order.created_time', 
                'order.customer_id', 'order.customer_name', 'order.address_id', 'order.order_status', 
                'order.total_amount','address.address as customer_address', 'order.payment_status')
            ->distinct()
            ->get();

        $temp = array();
        foreach ($order_ids as $order_ids1) {
            $temp1['restaurant_id']= $order_ids1->restaurant_id;
            $temp1['order_id'] = $order_ids1->order_id;
            $temp1['order_status'] = $order_ids1->order_status;    
            $booking = array();
            foreach ($data as $data1) {
                if($data1->order_id == $order_ids1->order_id){
                    $booking1['item_id'] = $data1->rest_dish_id;
                    $booking1['item_name'] = $data1->dish_name;
                    $booking1['quantity'] = $data1->quantity;
                    $booking1['price_per_piece'] = $data1->price;
                    $booking1['item_name'] = $data1->dish_name;
                    array_push($booking, $booking1);
                }
            }
            $temp1['order_details'] = $booking;
            $temp1['created_time'] = $order_ids1->created_time;
            $temp1['total_amount'] = $order_ids1->total_amount;
            $temp1['customer_id'] = $order_ids1->customer_id;
            $temp1['customer_name'] = $order_ids1->customer_name;
            $temp1['address_id'] = $order_ids1->address_id;
            $temp1['customer_address'] = $order_ids1->customer_address;
            $temp1['payment_status'] = $order_ids1->payment_status;
            array_push($temp, $temp1);
        }    
        return $temp;
    }

    public function getStatusCount($restaurant_id){
        //$GLOBALS['rest_id'] = $restaurant_id;
        $order_ids = DB::table('restaurant_dishes')
            ->join('order_dish_details', 'order_dish_details.rest_dish_id', '=', 'restaurant_dishes.id')
            ->join('order', 'order.id' , '=', 'order_dish_details.order_id')
            ->where('restaurant_dishes.restaurant_id', $restaurant_id)
            ->select('order.id')
            ->distinct()
            ->get();
        $id = array();
        foreach ($order_ids as $val) {
            array_push($id, $val->id);
        }
        $data = DB::table('order')
            ->select(DB::raw('count(order.order_status) as count, order.order_status'))
            ->whereIn('order.id',$id)
            ->where(function($q)
            {
                $q->where('order.order_status', 'accepted')
                  ->orWhere('order.order_status', 'rejectedByRestaurant');
            })
            ->groupBy('order.order_status')
            ->get();
        return $data;
    }

    public function setOrderRejected($restaurant_id, $booking_id, $cancel_reason){
          DB::table('order')
            ->where('order.id', $booking_id)
            ->update(array('order.order_status'=> 'rejectedByRestaurant', 
                        'order.cancel_reason' => $cancel_reason));
    }



    /**
    * Returns all the orders of a given restaurant.

    */
    public function getOrderDetails($restaurant_id){
        $data = DB::table('restaurant_dishes')
            ->join('dish_details', 'dish_details.id', '=', 'restaurant_dishes.dish_id')
            ->join('order_dish_details', 'order_dish_details.rest_dish_id', '=', 'restaurant_dishes.id')
            ->join('order', 'order.id' , '=', 'order_dish_details.order_id')
            ->where('restaurant_dishes.restaurant_id',$restaurant_id)
            ->select('order_dish_details.order_id','order.created_time', 'order_dish_details.rest_dish_id',
                'order_dish_details.quantity', 'dish_details.dish_name', 'restaurant_dishes.price')
            ->get();

        $order_ids = DB::table('restaurant_dishes')
            ->join('order_dish_details', 'order_dish_details.rest_dish_id', '=', 'restaurant_dishes.id')
            ->join('order', 'order.id' , '=', 'order_dish_details.order_id')
            ->where('restaurant_dishes.restaurant_id',$restaurant_id)
            ->select('order_dish_details.order_id','restaurant_dishes.restaurant_id', 'order.created_time', 
                'order.customer_id', 'order.customer_name', 'order.address_id', 'order.order_status', 'order.total_amount')
            ->distinct()
            ->get();

        $temp = array();
        foreach ($order_ids as $order_ids1) {
            $temp1['restaurant_id']= $order_ids1->restaurant_id;
            $temp1['order_id'] = $order_ids1->order_id;
            $temp1['order_status'] = $order_ids1->order_status;    
            $booking = array();
            foreach ($data as $data1) {
                if($data1->order_id == $order_ids1->order_id){
                    $booking1['item_id'] = $data1->rest_dish_id;
                    $booking1['item_name'] = $data1->dish_name;
                    $booking1['quantity'] = $data1->quantity;
                    $booking1['price_per_piece'] = $data1->price;
                    $booking1['item_name'] = $data1->dish_name;
                    array_push($booking, $booking1);
                }
            }
            $temp1['order_details'] = $booking;
            $temp1['created_time'] = $order_ids1->created_time;
            $temp1['total_amount'] = $order_ids1->total_amount;
            $temp1['customer_id'] = $order_ids1->customer_id;
            $temp1['customer_name'] = $order_ids1->customer_name;
            $temp1['address_id'] = $order_ids1->address_id;
            array_push($temp, $temp1);
        }
        return $temp;
    }

    public function bookDelivery($customer_id, $total_amount, $instruction, $delivery_type, $promo_code, $customer_address, $cart){
        $address_id = DB::table('address')
            ->insertGetId([
                'customer_id' => $customer_id,
                'address' => $customer_address->address
                    ]);
        $order_id = DB::table('order')
            ->insertGetId([
                'customer_id' => $customer_id,
                'total_amount' => $total_amount,
                //'instruction' => $instruction,
                //'delivery_type' => $delivery_type,
                //'promo_code' => $promo_code,
                'order_status' => "received",
                'address_id' => $address_id
                ]);
    
        foreach($cart as $val){
            foreach ($val->dishes as $dish) {
                DB::table('order_dish_details')
                    ->insert([
                        'order_id' => $order_id,
                        'resturant_id' => $val->id,
                        'rest_dish_id' => $dish->id,
                        'quantity' => $dish->count,
                        ]);    
            }
        }
        return "Added";
    }
	
	/*
	Get order listing for admin console
	*/
    public function getOrders() {
        $orderInfo = DB::table('order')
                    ->leftJoin('address', 'address.id', '=', 'order.address_id')
                    ->leftJoin('city', 'city.id', '=', 'order.city_id')
                    ->leftJoin('users', 'users.id', '=', 'order.customer_id')
                    ->join('order_status', 'order_status.order_status_id', '=', 'order.order_status_id')
                    ->select('order.*','users.email as customer_email','address.address','city.city_name', 'order_status.name as order_status',DB::raw('DATE_FORMAT(order_place_time, "%d/%m/%Y %h:%i:%s %p") as order_place_time_format'))
                    ->where('order_status.name','<>', 'Initiated')
                    ->orderBy('order.order_place_time','DESC')
                    ->paginate(10);
        $order['order'] = $orderInfo;
        return $order;
    }
	
	public function getFullOrderDetailsByOrderId($id) {
            
            $orderInfo=DB::table('order')
                ->join('order_status', 'order_status.order_status_id', '=', 'order.order_status_id')
                ->leftJoin('address', 'address.id', '=', 'order.address_id')
                ->leftJoin('users', 'users.id', '=', 'order.customer_id')
                ->select('order.*','users.email as customer_email','address.address','order_status.name as order_status', DB::raw('DATE_FORMAT(order_place_time, "%d/%m/%Y %h:%i:%s %p") as order_place_time_format'))
                ->where('order.id',$id)
                ->get();
            $order['order'] = $orderInfo ;
            $order['orderDishes'] = self::getFullOrderDishDetails($id);
            $order['orderHistory'] = self::getFullOrderHistory($id);
            return $order;
        }
        
	public function getFullOrderDishDetails($id) {
            $orderDetails=DB::table('order_dish_details')
                ->select('restaurant_dishes.*','order_dish_details.*','dish_details.*','category.cat_name as cuisine_name', 'restaurant_details.name as resto_name','restaurant_dishes.price as unit_price')
                ->join('restaurant_dishes', 'restaurant_dishes.id', '=', 'order_dish_details.rest_dish_id')
                ->join('dish_details', 'dish_details.id', '=', 'restaurant_dishes.dish_id')
                            ->leftJoin('category', 'category.id', '=', 'dish_details.category_id')
                            ->join('restaurant_details', 'restaurant_details.id', '=', 'restaurant_dishes.restaurant_id')
                ->where('order_id',$id)
                ->get();
            return $orderDetails;
        }
        
	public function getCustomerInfoByOrderId($order_id) {
            $row=DB::table('order')
                ->join('users' , 'order.customer_id' , '=' , 'users.id' )
                ->select('email','name')
                ->where('order.id',$order_id)
                ->first();
            return  $row;
	}
        
        
        public function getOrderStatusList() {
            
            $row = DB::table('order_status')
                ->select('order_status_id','name')
                ->orderBy('name')
                ->get();
            return  $row;
	}
        
        public function getFullOrderHistory($id) {
            
            $order_history = DB::table('order_history')
                ->select('order_history.*','order_status.name as order_status', DB::raw('DATE_FORMAT(order_history.date_added, "%d/%m/%Y %h:%i:%s %p") as order_history_date_added'))
                ->join('order_status', 'order_status.order_status_id', '=', 'order_history.order_status_id')
                ->where('order_history.order_id',$id)
                ->orderBy('order_history.date_added')
                ->get();
            return $order_history;
        }
        
        
        public function getOrderStatusIdByName($order_status_name) {
            
            $order_history = DB::table('order_status')
                ->select('order_status.order_status_id')
                ->where('order_status.name',$order_status_name)
                ->first();
            return !empty($order_history->order_status_id) ? $order_history->order_status_id : 1;
        }
        
        
        //order history based on order id
        public function updateOrderHistory($id, $status, $order_comment, $is_notify=1) {
            
            $order_place_time = date('Y-m-d H:i:s');
            $order_history_id = DB::table('order_history')
                        ->insertGetId([
                            'order_id' => $id,
                            'order_status_id' => $status,
                            'notify' => $is_notify,
                            'comment' => $order_comment,
                            'date_added' => $order_place_time
                          ]);
            if($order_history_id) {
                //payment_status
                DB::table('order')
                ->where('id', $id)
                ->update(['order_status_id' => $status]);
            }

        }
        
        public function getOrderStatusById($order_status_id) {
            
            $order_history = DB::table('order_status')
                ->select('order_status.status_message')
                ->where('order_status.order_status_id',$order_status_id)
                ->first();
            return !empty($order_history->status_message) ? $order_history->status_message : "Your order has been pending.";
        }
        
        public function isZappmealOrder($orderId) {
            $orderDetails=DB::table('order_dish_details')
                ->select('*')
                ->join('restaurant_dishes', 'restaurant_dishes.id', '=', 'order_dish_details.rest_dish_id')
                ->join('restaurant_details', 'restaurant_details.id', '=', 'restaurant_dishes.restaurant_id')
                ->where('order_id',$orderId)
                ->where('restaurant_details.is_homely',1)
                ->first();
            return $orderDetails;
        }


    public function getAllRestaurants($orderId){
        $orderDetails=DB::table('order_dish_details')
            ->select('restaurant_details.id as id','restaurant_details.name', 'restaurant_details.address as add', 'restaurant_details.phone', 'restaurant_details.lat', 'restaurant_details.lng')
            ->join('restaurant_dishes', 'restaurant_dishes.id', '=', 'order_dish_details.rest_dish_id')
            ->join('restaurant_details', 'restaurant_details.id', '=', 'restaurant_dishes.restaurant_id')
            ->where('order_id',$orderId)
            ->groupBy('restaurant_dishes.restaurant_id')
            ->get();
        return $orderDetails;
    }

    public function getDeliveryBoyInformation($order_id){
        $orderDetails=DB::table('delivery_boy_order_assignments')
            ->join('delivery_boy_users', 'delivery_boy_order_assignments.delivery_boy_id', '=', 'delivery_boy_users.id')
            ->where('order_id',$order_id)
            ->get();
        return $orderDetails;
    }
        
}