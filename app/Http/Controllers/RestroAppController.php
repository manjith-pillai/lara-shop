<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Order;
Use App\Models\Device;
Use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash;
use Auth;

class RestroAppController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

    public function orderRejected(Request $request){
    	$restaurant_id = $request->input('restaurant_id');
    	$booking_id = $request->input('booking_id');
    	$cancel_reason = $request->input('cancel_reason');
    	$order = new Order();
    	$order->setOrderRejected($restaurant_id, $booking_id, $cancel_reason);
    	return "Rejected";
    }

    public function getHistory(Request $request){
    	$restaurant_id = $request->input('restaurant_id');
    	$from_date = date('Y-m-d',strtotime($request->input('from_date')));
    	$to_date = date('Y-m-d',strtotime($request->input('to_date')));
    	$order = new Order();
	   	$getDetails = $order->getHistory($restaurant_id, $from_date, $to_date);
    	return $getDetails;
	}

	public function getStatusCount(Request $request){
		$restaurant_id = $request->input('restaurant_id');
		$order = new Order;
		return $order->getStatusCount($restaurant_id);
	}

	public function bookDelivery(Request $request){
		//$request = json_decode($request);
		$data = json_decode('{
			"customer_id": "2",
		    "total_amount": "550",
		    "instruction": "Less spicy, less oily",
		    "delivery_type": "normal",
		    "promo_code": "A7DS8X",
		    "address": {
		        "name": "home",
		        "address": "H.No-239/B, Daultabad, Gurgaon, Sector 103, Haryana"
		    },
		    "cart": [{
		        "id": "69",
		        "total_cost": "300",
		        "dishes": [{
		            "id": "3436",
		            "count": "2"
		        }, {
		            "id": "3446",
		            "count": "1"
		        }]
		    }, {
		        "id": "122",
		        "total_cost": "250",
		        "dishes": [{
		            "id": "3436",
		            "count": "2"
		        }, {
		            "id": "3446",
		            "count": "1"
		        }]
		    }]
		}');	
		 // $customer_id = $request->input('customer_id');
		 // $total_amount = $request->input('total_amount');
		 // $instruction = $request->input('instruction');
		 // $delivery_type = $request->input('delivery_type');
		 // $promo_code = $request->input('promo_code');
		 // $customer_address = $request->input('address');
		 // $cart = $request->input('cart');
		
		 $customer_id = $data->customer_id;
		 $total_amount = $data->total_amount;
		 $instruction = $data->instruction;
		 $delivery_type = $data->delivery_type;
		 $promo_code = $data->promo_code;
		 $customer_address = $data->address;
		 $cart = $data->cart;

		 $order = new Order();
		 $result = $order->bookDelivery($customer_id, $total_amount, $instruction, $delivery_type, $promo_code, $customer_address, $cart);
		 return $result;

	}

	public function getOrderDetails(Request $request){
		$restaurant_id = $request->input('restaurant_id');
		$order = new order();
		$details = $order->getOrderDetails($restaurant_id);
		return $details;

	}

	public function applyFilter(Request $request){
		$customer_id = $request->input('customer_id');
		$sorting_type = $request->input('sorting_type');
		$sorting_flag = $request->input('sorting_flag');
		$min = $request->input('min');
		$max = $request->input('max');
		if($max == "")
			$max = '1000';
		$restaurant = new Restaurant();
		$sortedRestaurantIds = $restaurant->applyFilter($customer_id, $sorting_type, $sorting_flag, $min, $max);
		return $sortedRestaurantIds;
	}

	public function addPushKey(Request $request){
		$push_key = $request->input('push_key');
		$device_id = $request->input('device_id');
		$device = new Device();
		$result = $device->addPushKey($push_key, $device_id);
		return $result;
	}

	public function sendNotification(Request $request){
		$message = $request->input('message');
		$device = new Device();
		$result = $device->sendNotification($message);
		return $result;
	}

	public function signUp(Request $request){
		$input = $request->input();
		$email = $input['email'];
		$password = $input['password'];
		$name = $input['name'];
		$creds = array('email'=>$email,'password' => $password);
		$rules=array(
			'email'=>'required|unique:users',
			);
		$validator=Validator::make($input,$rules);
		if($validator->fails()) {
			return json_encode(array('msg'=>'Already Registered','success'=>'false'));
		}
		User::create(array(
			'name'=>$name,
			'email'=>$email,
			'password'=>Hash::make($password)
			));
		return json_encode(array('msg'=>"Account Created",'success'=>'true'));
	}

	public function signIn(Request $request){
		$email = $request->input('email');
		$password = $request->input('password');
		$creds=array('email'=>$email,'password'=>$password);
		if(Auth::attempt($creds))
			return json_encode(array('msg'=>'Logged In','success'=>'true','id'=>Auth::User()->id,'name'=>Auth::User()->name));
		 else
		 	return json_encode(array('msg'=>' ! Email or Password is Incorrect','success'=>'false','id'=>'', 'name'=>''));
	}


}
