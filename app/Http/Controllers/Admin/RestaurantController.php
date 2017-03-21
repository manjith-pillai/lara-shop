<?php namespace App\Http\Controllers;
namespace App\Http\Controllers\Admin;
use App\Http\Requests;
use App\Models\Restaurant;
use App\Models\RestaurantDetail;
use App\Models\Area;
use App\Models\RestTaxDetail;
use App\Models\Category;
use App\Models\DishDetail;
use App\Models\RestaurantDish;
use App\Models\City;
use App\Models\Cuisine;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Validator;
use Excel;
use Session;
use Input;
use DB;
use Response;

use Illuminate\Http\Request;

class RestaurantController extends Controller {


	protected $auth;
	public $restaurant_last_id;
	public $category_last_id;
	public $dish_detail_last_id;
	public $city_last_id;
	public $area_last_id;
	public $category_veg;
	
    public function __construct(Guard $auth) {
		$this->middleware('auth');
		$this->auth = $auth;
		$this->restaurant_last_id = 0;
		$this->category_last_id = 0;
		$this->city_last_id = 0;
		$this->dish_detail_last_id = 0;
		$this->area_last_id = 0;
		$this->category_veg = 'Veg';
    }
    
	public function addNewRestaurant()
	{
            if($this->auth->user()->is_admin) {
		$restaurant = new Restaurant();
                $cities = $restaurant->getCities();
                $tax = DB::table('rest_tax_details')->get();
                $cuisine = $restaurant->getCuisines();
		return view('admin.restaurants.new_restaurant',compact('cities','tax','cuisine'));
            }
	}

	public function showRestaurantList()
	{
            if($this->auth->user()->is_admin) {
		if(Input::get('query'))
		{
			$q = Input::get('query');
			$restaurant = RestaurantDetail::join('city', 'city.id', '=', 'restaurant_details.city_id')
				->select('city.city_name','restaurant_details.*')->where('name', 'like', '%' . $q . '%')	
				->orWhere('phone', 'like', '%' . $q . '%')
				->orWhere('address', 'like', '%' . $q . '%')
				->orWhere('city.city_name', 'like', '%' . $q . '%')
				->paginate(15);
		return view('admin.restaurants.show_restaurant',compact('restaurant','q'));
		}
		else
		{
		$q = Input::get('query');
		$restaurant = RestaurantDetail::join('city', 'city.id', '=', 'restaurant_details.city_id')
			->select('city.city_name','restaurant_details.*')
			->orderBy('restaurant_details.id','desc')
			->paginate(15);
		 
		return view('admin.restaurants.show_restaurant',compact('restaurant','q'));
		}
            }
	}

	public function storeRestaurant(Request $request)
	{
                if($this->auth->user()->is_admin) {
                    
                
		$validate_rules = [
			'name' => 'required|unique:restaurant_details,name',
	        'address' => 'required',
	        'lng' => 'required|numeric',
	        'lat' => 'required|numeric',
	        'phone' => 'required',
	        'email' => 'required|email',
	        'resturant_cuisine' => 'required',
	        'cost_two_people' => 'required|numeric',
	        'city_id' => 'required',
	        'area_id' => 'required',
	        'open_time' => 'required',
	        'close_time' => 'required',
	        'img' => 'mimes:jpeg,bmp,png,gif',
	        'service_tax_percent' => 'required',
	        'contact_person' => 'required',
	        'contact_person_phone' => 'required',
	        'rating' => 'required',
	        'featured' => 'required',
	        'is_active' => 'required'
	    	];
	        if(Input::get('area_id') == "change") {
				$validate_rules = [
				'area_name' => 'required',
					];
				}
	    $this->validate($request,$validate_rules);
		$area_id_a = $request->area_id;
			if($request->area_name) {
			$last_area_id = Area::firstOrCreate([
				'name' => $request->area_name,
				'city_id' => $request->city_id,
				]);
			if(!empty($last_area_id->id))
			{
				$area_id_a = $last_area_id->id;
			}else
			{
				$area_id_a = $last_area_id->area_id;
			}
		} 
		$gen_url_name = $this->generateRestaurantUrl($request->name,$area_id_a, $request->city_id,$request->address);

		if($request->hasFile('img'))
		{
		$file = Input::file('img');
		$name = $file->getClientOriginalName();
		$img_a = 'data/'.$name;
		$file->move(public_path().'/image/data',$name);
		
		}
	
		$rest_tax_details = RestTaxDetail::firstOrCreate([
  			'service_tax_percent' => $request->service_tax_percent, 
  			'vat_percent' => $request->vat_percent,
  			'service_charge_percent' => $request->service_charge_percent,
  			'packaging_charge' => $request->packaging_charge,
  			]);
		if(empty($rest_tax_details->id)){
  			$tax_id_a = $rest_tax_details->tax_id;
  		}
  		else{
  			$tax_id_a = $rest_tax_details->id;
  		}
		$restaurant = RestaurantDetail::firstOrCreate([
				'name' => $request->name,
				'address' => $request->address,
				'lng' => $request->lng,
				'lat' => $request->lat,
				'phone' => $request->phone,
				'email' => $request->email,
				'cost_two_people' => $request->cost_two_people,
				'city_id' => $request->city_id,
				'area_id' => $area_id_a,
				'url_name' => $gen_url_name,
				'open_time' => $request->open_time,
				'close_time' => $request->close_time,
				'rating' => $request->rating,
				'img' => isset($img_a) ? $img_a:'data/noimg.png',
				'tax_id'=> $tax_id_a,
				'contact_person' => $request->contact_person,
				'contact_person_phone' => $request->contact_person_phone,
				'featured' => $request->featured,
				'is_active' => $request->is_active,
				'meta_keywords' => $request->meta_keywords,
				'meta_description' => $request->meta_description,
			]);


				if($restaurant->id) {
					$rest_list = $request->resturant_cuisine;
					foreach($rest_list as $rest){
						
						$cuisine_list = DB::table('rest_cuisine_list')->insertGetId([
	    						'restaurant_id' => $restaurant->id,
	    						'cuisine_id'=> $rest,
	    							]);
								}
	    					
					}

		 return redirect('admin/restaurants/index')->with('flash_message', 'Restaurant successfully added!');
                }
	}


    public function generateRestaurantUrl($restaurant_name, $restaurant_area_id, $restaurant_city_id,$address) {
    	$area_model = new Area();
    	$restaurant_area_id = $area_model->getArea($restaurant_area_id);
    	$restaurant_city_id = $area_model->getCity($restaurant_city_id);
                    $restaurant_url = strtolower(str_replace(' ', '_',$restaurant_name.'_'.$restaurant_area_id.'_'.$restaurant_city_id.'_'.$address));
                    return $restaurant_url;
    }
	
	public function editRestaurant($id)
	{	
             if($this->auth->user()->is_admin) {
		$rest = new Restaurant();
		$cities = $rest->getCities();
		$restaurant = DB::table('restaurant_details')
                ->join('rest_tax_details', 'rest_tax_details.tax_id', '=', 'restaurant_details.tax_id')
                ->select('restaurant_details.*', 'rest_tax_details.*')
                ->where('restaurant_details.id',$id)
                ->first();
                //dd($restaurant);
		 $cuisine = $rest->getCuisines();
		 $cuisinelist = array();
		 $cuisine_row = DB::table('restaurant_details')
		 				->join('rest_cuisine_list','rest_cuisine_list.restaurant_id','=','restaurant_details.id')
		 				->select(array(DB::raw('group_concat(rest_cuisine_list.cuisine_id) as cuisine_ids')))
		 				->where('rest_cuisine_list.restaurant_id',$id)
		 				->groupBy('rest_cuisine_list.restaurant_id')
		 				->first();
		 				$cuisinelist=explode(',',$cuisine_row->cuisine_ids);
		        $area  = Area::where('city_id','=',$restaurant->city_id)->orderBy('name')->get();

		return view('admin.restaurants.edit_restaurant',compact('restaurant','cities','tax','area','cuisine','cuisinelist'));
             }
	}

	
	public function updateRestaurant($id, Request $request)
	{
            if($this->auth->user()->is_admin) {
		$restaurant = RestaurantDetail::findOrFail($id);
		$restaurant->name = $request->name;
		$restaurant->address = $request->address;
		$restaurant->lng = $request->lng;
		$restaurant->lat = $request->lat;
		$restaurant->phone = $request->phone;
		$restaurant->email = $request->email;
		$restaurant->cost_two_people = $request->cost_two_people;
		$restaurant->city_id = $request->city_id;
		$restaurant->contact_person = $request->contact_person;
		$restaurant->contact_person_phone = $request->contact_person_phone;
		$restaurant->open_time = $request->open_time;
		$restaurant->close_time = $request->close_time;
		$restaurant->featured = $request->featured;
		$restaurant->rating = $request->rating;
		$restaurant->is_active = $request->is_active;
		$restaurant->meta_keywords = $request->meta_keywords;
		$restaurant->meta_description = $request->meta_description;
		
		if($request->hasFile('img'))
		{
			$file = Input::file('img');
			$name = $file->getClientOriginalName();
			$restaurant->img = 'data/'.$name;
			$file->move(public_path().'/image/data',$name);
		}
		
		$restaurant->area_id = $request->area_id;
			if($request->area_name) {
			$last_area_id = Area::firstOrCreate([
				'name' => $request->area_name,
				'city_id' => $request->city_id,
				]);
			if(!empty($last_area_id->id))
			{
				$restaurant->area_id = $last_area_id->id;
			}
			else
			{
				$restaurant->area_id = $last_area_id->area_id;
			}
		} 
		$restaurant->url_name = $this->generateRestaurantUrl($request->name,$restaurant->area_id, $request->city_id,$request->address);
		$rest_tax_details = RestTaxDetail::firstOrCreate([
  			'service_tax_percent' => $request->service_tax_percent, 
  			'vat_percent' => $request->vat_percent,
  			'service_charge_percent' => $request->service_charge_percent,
  			'packaging_charge' => $request->packaging_charge,
  			]);
		if(empty($rest_tax_details->id)){
  			$restaurant->tax_id = $rest_tax_details->tax_id;
  		}
  		else{
  			$restaurant->tax_id = $rest_tax_details->id;
  		}
  		$restaurant->save();
	  	
  		if($request->resturant_cuisine) {
					$rest_list = $request->resturant_cuisine;
					foreach($rest_list as $rest_l){
						$cuisine_row = DB::table('rest_cuisine_list')->where('restaurant_id',$restaurant->id)
						->where('cuisine_id',$rest_l)->get();
						if(empty($cuisine_row)){
						$cuisine_list = DB::table('rest_cuisine_list')->insertGetId([
	    						'restaurant_id' => $restaurant->id,
	    						'cuisine_id'=> $rest_l,
	    							]);
	    					}
	    				}
					}
		Session::flash('flash_message', 'Restaurant Updated successfully');
		return redirect()->back();
            }
	}


	public function deleteRestaurant($id)
	{
            if($this->auth->user()->is_admin) {
		$restaurant = RestaurantDetail::findOrFail($id);
		$restaurant->delete();
		Session::flash('flash_message', 'Restaurant Deleted successfully');
		return redirect()->back();
            }
	}

	public function opeRestaurant($id)
	{
            if($this->auth->user()->is_admin) {
		$restaurant = RestaurantDetail::findOrFail($id);
		$restaurant = RestaurantDetail::where('is_active', '=', 'open')->find($id);
		$restaurant->is_active = "closed";
		$restaurant->save();
		return redirect()->back();
            }
		


	}

	public function closedRestaurant($id)
	{
            if($this->auth->user()->is_admin) {
		$restaurant = RestaurantDetail::find($id);
		$restaurant = RestaurantDetail::where('is_active', '=', 'closed')->find($id);
		$restaurant->is_active = "open";
		$restaurant->save();
		return redirect()->back();
            }
		
	}


	public function importview()
	{
            if($this->auth->user()->is_admin) {
		return view('admin.restaurants.import_menu');
            }
	}

	public function importfiles(Request $request)
	{
            if($this->auth->user()->is_admin) {
		$this->validate($request, [
        	'import_file' => 'required',
        	
    		]);
                 
		 
		if(Input::hasFile('import_file'))
		{
			$path = Input::file('import_file')->getRealPath();
			try {
  				Excel::selectSheets('Sheet1')->load($path, function($reader){
					$reader->each(function($sheet) {
    					if(!empty($sheet->restaurant_name)) {
    						if(!empty($sheet->city)) { 
	    						$city_row = City::firstOrCreate([
	    							'city_name' => trim($sheet->city),
	    							'city_url_name' => str_replace(' ', '', trim($sheet->city)),
	    						]);
	    						$this->city_last_id = $city_row->id; 
	    						if($this->city_last_id && !empty($sheet->area)) {
	    							$area_row = Area::firstOrCreate([
	    								'city_id' => trim($this->city_last_id),
	    								'name' => trim($sheet->area),
	    							]);
	    							$this->area_last_id = $area_row->area_id; 
	    						}
	    					}

	    				$rest_tax_details = RestTaxDetail::firstOrCreate([
  							'service_tax_percent' => trim($sheet->service_tax_percent), 
  							'vat_percent' => trim($sheet->vat_percent),
  							'service_charge_percent' => trim($sheet->service_charge_percent),
  							'packaging_charge' => trim($sheet->packaging_charge),
  							]);
							if(empty($rest_tax_details->id))
							{
  								$tax_id_a = $rest_tax_details->tax_id;
  							}
  							else 
  							{
  								$tax_id_a = $rest_tax_details->id;
  							}
                                                        

    						$restaurant_row = RestaurantDetail::firstOrCreate([
    								'name' => trim($sheet->restaurant_name),
    								'address' => trim($sheet->restaurant_address),
    								'city_id' => trim($this->city_last_id),
    								'area_id' => trim($this->area_last_id),
    								'url_name' => $this->generateRestaurantUrl(trim($sheet->restaurant_name), trim($this->area_last_id), trim($this->city_last_id),trim($sheet->restaurant_address)),
    								
    								]);
    						$this->restaurant_last_id = $restaurant_row->id; 
    						$rest = RestaurantDetail::find($restaurant_row->id);

                                                if($this->restaurant_last_id) {
                                                    $restaurant_row_update = \App\Models\RestaurantDetail::where('id', $this->restaurant_last_id)
                                                    ->update([
    								'tax_id'  => trim($sheet->tax_id),
    								'cost_two_people' => trim($sheet->cost_for_two),
    								'lng' => !empty($sheet->longitude) ? trim($sheet->longitude) : 0,
    								'lat' => !empty($sheet->latitude) ? trim($sheet->latitude) : 0,
    								'phone' => !empty($sheet->restaurant_phone_number) ? trim($sheet->restaurant_phone_number) : 0,
    								'rating' => !empty($sheet->rating) ? $sheet->rating : 0,
    								'img' => ($rest->img == '') ? 'data/noimg.png' : $rest->img
    								,
    								'email' => !empty($sheet->email) ? trim($sheet->email) : '',
    								'open_time' => !empty($sheet->open_time) ? trim($sheet->open_time) : '',
    								'close_time' => !empty($sheet->close_time) ? trim($sheet->close_time) : '',
    								'contact_person' => !empty($sheet->contact_person) ? trim($sheet->contact_person) : '',
    								'contact_person_phone' => !empty($sheet->contact_person_phone) ? trim($sheet->contact_person_phone) : 0,
    								'tax_id' => trim($tax_id_a),
    								'meta_keywords' => !empty($sheet->meta_keywords) ? trim($sheet->meta_keywords): '',
    								'meta_description' => !empty($sheet->meta_description) ? trim($sheet->meta_description): '',
    								'is_active' => 'closed',
    								]);
                                                    
                                                }
    						
    					}
    				
    						
    				
					
    				if($this->restaurant_last_id) {
    					if(!empty($sheet->cuisine)) {
	    					$cuisine = trim($sheet->cuisine);
	    					$cuisine_split = explode(",",$cuisine);
	    					foreach($cuisine_split as $cuisine_item) {
	    						$cuisine_row = Cuisine::firstOrCreate([
	    							'cuisine_name'=> $cuisine_item,
	    						]);
	    						$check_cuisine = DB::table('rest_cuisine_list')
	    										->where('restaurant_id',$this->restaurant_last_id)
	    										->where('cuisine_id',$cuisine_row->id)
	    										->get();
	    						if(empty($check_cuisine)) {
	    							$cuisine_list = DB::table('rest_cuisine_list')->insertGetId([
	    								'restaurant_id' => $this->restaurant_last_id,
	    								'cuisine_id'=> $cuisine_row->id,
	    							]);
	    						}
	    					}

	    				}

    					if(!empty($sheet->category)) {
	    					$cat = trim($sheet->category);
	    					$cat_split = explode(" > ",$cat);
	    					$this->category_veg = $cat_split[0];		
	    					$category_row = Category::firstOrCreate([
	    						'cat_name' => $cat_split[1],
	    						'category_description' => trim($sheet->category_description),
	    					]);
	    					$this->category_last_id = $category_row->id;
	    					
    						}

    					}

    				if($this->category_last_id && !empty($sheet->dish_name)) {
    					$dish_detail = DishDetail::firstOrCreate([
	     						'dish_name' => trim($sheet->dish_name),
	     						'description' => trim($sheet->dish_description),
	     						'veg_flag' => (trim($this->category_veg) == "Nonveg") ? 0 : 1 ,
	     						'category_id' => trim($this->category_last_id),
	     					]);
    					if(!empty($dish_detail->id)) {

    						$dish_detail = RestaurantDish::firstOrCreate([
	    						'dish_id' => $dish_detail->id,
	     						'restaurant_id' => $this->restaurant_last_id,
	     						'price' => trim($sheet->price),
	     						]);
    						$this->dish_detail_last_id = $dish_detail->dish_id;
    						if($this->dish_detail_last_id) 
    						{
                                                    \App\Models\RestaurantDish::where('dish_id', $this->dish_detail_last_id)
                                                    ->update([
                                                  	'price' => trim($sheet->price),
                                                   	]);
                                               }


    					}


    				}

				});
			});

	} catch ( \Illuminate\Database\QueryException $e) 
		{
    		Session::flash('errormsg', "File Upload is Unsuccessful");
			return redirect()->back();
		}
			
			
		}
		Session::flash('message', "File is Uploaded Successfully");
			return redirect()->back();
            }
	}

	public function getAreaList()
	{
		$city_id = Input::get('city_id');
            $areaList = Area::where('city_id','=',$city_id)->orderBy('name')->get();
            return Response::json($areaList);

	}
	public function postSearch()
	{
            if($this->auth->user()->is_admin) {
		$q = Input::get('query');
		$search = DB::table('restaurant_details')->join('city', 'city.id', '=', 'restaurant_details.city_id')
			->select('city.city_name','restaurant_details.*')->where('name', 'like', '%' . $q . '%'
		)->get();

		return view('admin.restaurants.search_restaurant',compact('search'));
            }
	}

	public function placeorder($city_url = '', $rest_url_name = '')
	{
		$rest = new Restaurant();
		$cities = $rest->getCities();
		$restuarant_list = array();
		$restaurantDetails = array();
		if(!empty($city_url)) {
			$restuarant_list = DB::table('restaurant_details')->join('city','city.id','=','restaurant_details.city_id')->select('restaurant_details.name','city.*','restaurant_details.city_id','restaurant_details.url_name')->where('city_url_name','=',$city_url)->orderBy('name')->get();
			if(!empty($rest_url_name && $city_url)) {
				 $restaurantDetails['details']=$rest->getDetails($rest_url_name);
         		$restaurantDetails['dishes']=$rest->getDishes($rest_url_name,0, 20000,array());
	     		$restaurantDetails['cusines']=$rest->getCategories($rest_url_name);
			}
		}
		//dd($restuarant_list);
		return view('admin.restaurants.placeorder',compact('cities','city_url', 'rest_url_name', 'restuarant_list','restaurantDetails'));

	}

	// public function restlist($id)
	// {
	// 	$rest = new Restaurant();
	// 	$cities = $rest->getCities();
	// 	$results = RestaurantDetail::where('city_id','=',$id)->orderBy('name')->get();
		
	// 	return view('admin.restaurants.restlist',compact('cities','$url_name','results','id'));
	// }
	// public function restorder($id,$rid)
	// {
	// 	$rest = new Restaurant();
	// 	$cities = $rest->getCities();
	// 	$results = RestaurantDetail::where('name','=',$rid)->orderBy('name')->get();
	// 	dd($results);
	// 	return view('admin.restaurants.restlist',compact('cities','$url_name','results','id'));
	// }
	
}
