<?php
namespace App\Http\Controllers\Account;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Contracts\Auth\Guard;
use App\Models\Order;
use Auth;
use DB;
class AccountController extends Controller {

	protected $auth;
	
    public function __construct(Guard $auth) {
		$this->middleware('auth');
		$this->auth = $auth;
    }

    public function myaccount()
    {
        if(Auth::user())
        {
            $address = DB::table('address')
                    ->join('users','users.id','=','address.customer_id')
                    ->select('address.*','users.*')
                    ->where('address.customer_id','=',Auth::user()->id)
                    ->get();

                    //dd($address);
            $results = DB::table('order')
                ->join('order_dish_details','order_dish_details.order_id','=','order.id')
                ->join('restaurant_details','restaurant_details.id','=','order_dish_details.rest_id')
                ->join('address','address.id','=','order.customer_id')
                ->join('restaurant_dishes','restaurant_dishes.id','=','order_dish_details.rest_dish_id')
                ->join('dish_details','dish_details.id','=','restaurant_dishes.dish_id')
                ->select('order.total_amount','order.donation','order.total_vat','order.total_servtax','order.total_sercharge','order.total_delCharge','order.total_packcharge','order.total_with_tax','order.order_place_time','order_dish_details.*','restaurant_details.name','address.*','restaurant_dishes.*','dish_details.*')
                ->where('order.customer_id','=',Auth::user()->id)
                //->groupBy('order.id')
                ->get();
            //dd($results);
            $myorders = array();
            $i = 0;
            foreach($results as $mydishes)
            {
                //echo ($i-1);
                //echo '<br>';
                //var_dump($myorders[$i]['order_id']);
               
                    //echo ($myorders[$i-1]['order_id']);
                if(isset($myorders[$mydishes->order_id]) && (trim($myorders[$mydishes->order_id]['order_id']) === trim($mydishes->order_id)))
                {
                    $myorders[$mydishes->order_id]['restaurant_names'] = $myorders[$mydishes->order_id]['restaurant_names'].', '.$mydishes->name;
                    
                }else
                {
                    
                    //var_dump ($mydishes->order_id);
                    $myorders[$mydishes->order_id]['restaurant_names'] = $mydishes->name;
                    $myorders[$mydishes->order_id]['order_place_time'] = $mydishes->order_place_time;
                    $myorders[$mydishes->order_id]['order_id'] = $mydishes->order_id;
                    $myorders[$mydishes->order_id]['total_amt'] = $mydishes->total_with_tax;
                    $myorders[$mydishes->order_id]['total_amount'] = $mydishes->total_amount;
                    $myorders[$mydishes->order_id]['total_packcharge'] = $mydishes->total_packcharge;
                    $myorders[$mydishes->order_id]['total_sercharge'] = $mydishes->total_sercharge;
                    $myorders[$mydishes->order_id]['total_servtax'] = $mydishes->total_servtax;
                    $myorders[$mydishes->order_id]['total_vat'] = $mydishes->total_vat;
                    $myorders[$mydishes->order_id]['total_delCharge'] = $mydishes->total_delCharge;
                    //var_dump ($myorders[$i]['order_id']);
                }
                 $myorders[$mydishes->order_id]['order_dishes'][$i] = $mydishes;
                

                $i++;
            }
            //dd($myorders);
    	   return view('account',compact('myorders','address'));
        }
    }


}