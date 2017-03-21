<?php


namespace App\Models;
use Illuminate\Support\Facades\DB;

class Restaurant extends BaseModel {

    public function getDetails($url_name){
        $results= DB::table('restaurant_details')
            ->join('rest_cuisine_list', 'rest_cuisine_list.restaurant_id', '=', 'restaurant_details.id')
            ->leftjoin('areas', 'areas.area_id','=','restaurant_details.area_id')
            ->leftjoin('city','city.id', '=', 'restaurant_details.city_id')
            ->join('cuisine', 'cuisine.id', '=', 'rest_cuisine_list.cuisine_id')
            ->select(array(DB::raw('restaurant_details.id, restaurant_details.name, address, rating, timing, open_time, is_homely, close_time, img, areas.name as area_name, city.city_name, group_concat(cuisine_name) as cuisine_names, meta_keywords,meta_description' )))
            ->where('url_name',$url_name)
            ->groupby('restaurant_details.id')
            ->get();
        return $results;
    }

    public function getDishes($url_name,$min_price,$max_price,$cat_in)
	{
		
		$results= DB::table('restaurant_details')
            ->join('restaurant_dishes', 'restaurant_details.id', '=', 'restaurant_dishes.restaurant_id')
            ->join('dish_details', 'restaurant_dishes.dish_id', '=', 'dish_details.id')
            ->join('category', 'dish_details.category_id', '=', 'category.id')
			->where('url_name',$url_name)
            ->where(function($query) use ($min_price,$max_price,$cat_in) 
				{
						

                        if(isset($cat_in))
						{
							foreach($cat_in as $cat)
							{
								$query->orwhere('category.cat_name', $cat) ;  
							}
						}
                        if(isset($min_price) && $min_price >= 0 )
                        {						
						  $query->where('restaurant_dishes.price','>=',$min_price) ;
                        }
                        if(isset($max_price) && $max_price >= 0 )
                        {                       
                            $query->where('restaurant_dishes.price','<=',$max_price);
						}
					return $query ;	
				})
            ->orderBy('cuisine','desc')
            ->select(
                'dish_details.dish_name as dish_name',
                'dish_details.veg_flag as veg_flag',
                'category.cat_name as cuisine',
                'category.category_description as cuisine_description',
                'restaurant_dishes.id as id',
                'restaurant_dishes.price as price'
            )
            ->get();
        return $results;
    }
	public function getCategories($url_name)
	{
		$results= DB::table('restaurant_details')
            ->join('restaurant_dishes', 'restaurant_details.id', '=', 'restaurant_dishes.restaurant_id')
            ->join('dish_details', 'restaurant_dishes.dish_id', '=', 'dish_details.id')
            ->join('category', 'dish_details.category_id', '=', 'category.id')
            ->where('url_name',$url_name)
            ->groupBy('cuisine')
            ->select(
                'category.cat_name as cuisine'
            )
            ->orderBy('cuisine','desc')
            ->get();
			return $results; 
	}
	
    public function applyFilter($customer_id, $sorting_type, $sorting_flag, $min, $max){

        $cust_lat_long = DB::table('customer_details')
            ->join('address', 'customer_details.id', '=', 'address.customer_id')
            ->where('customer_details.id', $customer_id)
            ->select('address.longitude as lng1', 'address.latitude as lat1')
            ->get();
        $lat1 = $cust_lat_long[0]->lat1;
        $lng1 = $cust_lat_long[0]->lng1;
        if($sorting_type != ""){
            $restaurant_ids = DB::table('restaurant_details')
                    ->select('restaurant_details.id','restaurant_details.rating', 'restaurant.cost_two_people')
                    ->select(DB::raw('restaurant_details.id, restaurant_details.name, restaurant_details.address_last, restaurant_details.cost_two_people,
                         restaurant_details.featured, restaurant_details.rating, restaurant_details.img,
                         ( 6371 * acos( cos( radians('.$lat1.') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$lng1.') ) + sin( radians('.$lat1.') ) * sin( radians( lat ) ) ) ) AS distance'))
                    ->where('restaurant_details.cost_two_people', '>=', $min)
                    ->where('restaurant_details.cost_two_people', '<=', $max)
                    ->orderBy($sorting_type, $sorting_flag)
                    ->get();
        }
        else{
            $restaurant_ids = DB::table('restaurant_details')
                ->select(DB::raw('restaurant_details.id, restaurant_details.name, restaurant_details.address_last, restaurant_details.cost_two_people,
                     restaurant_details.featured, restaurant_details.rating, restaurant_details.img,
                      ( 6371 * acos( cos( radians('.$lat1.') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$lng1.') ) + sin( radians('.$lat1.') ) * sin( radians( lat ) ) ) ) AS distance'))
                ->where('restaurant_details.cost_two_people', '>=', $min)
                ->where('restaurant_details.cost_two_people', '<=', $max)
                ->orderBy('distance')
                ->get();
        }
        return $restaurant_ids;
    }


    public function getCities($web_format = true) {
            $cities = array();
            $results= DB::table('city')
            ->select('id as city_id', 'city_name', 'default_lat','default_lng','city_url_name','radius')
            ->orderBy('city_name')
            ->get();
            if($web_format) {
                foreach($results as $city) {
                    $cities[$city->city_url_name] = $city;
                }
            } else {
                $cities = $results;
            }
        return $cities;
    }
    public function getCity($id){
        $city = DB::table('city')->select('city.city_name')->where('city.id','=',$id)->first();
        return $city->city_name;
    }
   
    public function getCityIdByRestDishId($restDishId) {

        $row = DB::table('restaurant_dishes')
                ->join('restaurant_details', 'restaurant_details.id', '=', 'restaurant_dishes.restaurant_id')
                ->select('restaurant_details.city_id')
                ->where('restaurant_dishes.id',$restDishId)
                ->first();
        return  isset($row->city_id) ? $row->city_id : '';
    }


    public function isRestaurantExist($restaurant_name, $restaurant_city_id, $restaurant_area_id = 0) {

        $row = DB::table('restaurant_details')
                ->select('restaurant_details.name','restaurant_details.id')
                ->where('restaurant_details.city_id',$restaurant_city_id)
                ->where('restaurant_details.area_id',$restaurant_area_id)
                ->where('restaurant_details.name', 'like', $restaurant_name.'%')
                ->first();
        return  !empty($row->name) ? $row->id : false;
    }
    
    
    public function getAreaByAreaId($restaurant_area_id) {

        $row = DB::table('areas')
                ->select('areas.name')
                ->where('areas.area_id',$restaurant_area_id)
                ->first();
        return  !empty($row->name) ? $row->name : '';
    }
    
    public function getCityByCityId($restaurant_area_id) {

        $row = DB::table('city')
                ->select('city.city_name')
                ->where('city.id',$restaurant_area_id)
                ->first();
        return  !empty($row->city_name) ? $row->city_name : '';
    }
    
    public function getCuisineIdByName($cuisine_name) {
        
        $row = DB::table('cuisine')
                ->select('cuisine.cuisine_name','cuisine.id')
                ->where('cuisine.cuisine_name',$cuisine_name)
                ->first();
        if(!empty($row->cuisine_name)) {
           return  $row->id;
        } else {
            $cusineId = DB::table('cuisine')->insertGetId([
                'cuisine_name'=>$cuisine_name,
                'image'=>null
                ]);
            return $cusineId;
        }
    }
    
    
    public function getCategoryIdByName($category_name) {
        $row = DB::table('category')
                ->select('category.cat_name','category.id')
                ->where('category.cat_name',$category_name)
                ->first();
        if(!empty($row->cat_name)) {
           return  $row->id;
        } else {
            $categoryId = DB::table('category')->insertGetId([
                'cat_name'=>$category_name,
                'image'=>null
                ]);
            return $categoryId;
        }
    }
    
    public function getDishIdByName($rest_category_name, $category_id_rest, $category_name, $desc = '') {
        $row = DB::table('dish_details')
                ->select('dish_details.dish_name','dish_details.id')
                ->where('dish_details.dish_name',$rest_category_name)
                ->where('dish_details.category_id',$category_id_rest)
                ->first();
        if(!empty($row->dish_name)) {
           return  $row->id;
        } else {
            $veg_flag = 1;
            preg_match_all("/(Non Veg|Chicken|Mutton|Fish|keema|kima|prawn|anda|egg|omlet|omlette|omlete|tangdi|tangadi|pronsh|murg|murga)/", $category_name, $matches);
            if(!empty($matches['0'])) {
                $veg_flag = 0;
            }
            if($veg_flag == 1) {
                preg_match_all("/(Non Veg|Chicken|Mutton|Fish|keema|kima|prawn|anda|egg|omlet|omlette|omlete|tangdi|tangadi|pronsh|murg|murga)/", $rest_category_name, $matches);
                if(!empty($matches['0'])) {
                    $veg_flag = 0;
                }
            }
            $dishId = DB::table('dish_details')->insertGetId([
                    'dish_name'=>$rest_category_name,
                    'veg_flag'=> $veg_flag,
                    'category_id'=>$category_id_rest,
                    'description' => $desc
                ]);
            return $dishId;
        }
    }
    
    public function updateRestTax($rest_id, $taxes) {
        $res_tax_id = 0;
        if(empty($taxes['vat_percent'])) {
            $taxes['vat_percent'] = 0;
        }
        if(!empty($taxes['cess'])) {
            $taxes['service_tax_percent'] = ($taxes['service_tax_percent'] + $taxes['cess']);
        }
        $rest_tax_details = RestTaxDetail::where(['vat_percent' => $taxes['vat_percent'], 'service_tax_percent' => $taxes['service_tax_percent']])->first();
        if(!empty($rest_tax_details)) {
            $res_tax_id = $rest_tax_details->tax_id;
        } else {
            $rest_tax_details = new RestTaxDetail;
            $rest_tax_details->vat_percent = $taxes['vat_percent'];
            $rest_tax_details->service_tax_percent = $taxes['service_tax_percent'];
            $rest_tax_details->save();
            $res_tax_id = $rest_tax_details->tax_id;
        }
        if(!empty($res_tax_id)) {
            $update_status = DB::table('restaurant_details')
                    ->where('restaurant_details.id', $rest_id)
                    ->update(['restaurant_details.tax_id' => $res_tax_id]);
        }
    }
    
    public function getCityIdByAreaId($restaurant_area_id) {

        $row = DB::table('areas')
                ->select('areas.city_id')
                ->where('areas.area_id',$restaurant_area_id)
                ->first();
        return  !empty($row->city_id) ? $row->city_id : '';
    }


    public function getCuisines(){
        $results = DB::table('cuisine')
        ->select('id as cuisine_id','cuisine_name')
        ->orderBy('cuisine_name')
        ->get();
        return $results;
    }

    public function insertCuisines($restaurantId,$cusineId){

        if(is_array($cusineId)){
            for($i=0;$i < count($cusineId);$i++)
            {
                $results = DB::table('rest_cuisine_list')->insertGetId(array('restaurant_id'=>$restaurantId,
                    'cuisine_id'=>$cusineId[$i]));
            }
            return $results;
        }
       
        // $result = DB::table('rest_cuisine_list')->insertGetId([
        //         'cuisine_id' => $cusineId,
        //         'restaurant_id' => $restaurantId,
        //     ]);
        // return $result;
    
      
    }


} 