<?php
namespace App\Models;
use Illuminate\Support\Facades\DB;

class Search extends BaseModel {
    private $keyword;
    private $lat;
    private $lng;
    public function __construct($text,$lat,$lng) {
        $this->keyword=$text;
        $this->lat=$lat;
        $this->lng=$lng;
    }
    public function  omniSearch($city){
        $lat=$this->lat;
        $lng=$this->lng;
        $results= DB::table('restaurant_details')
            ->select('*')
            ->select(DB::raw('*,( 6371 * acos( cos( radians('.$lat.') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians( lat ) ) ) ) AS distance'))
            ->having('distance', '<', 50)
            ->orderBy('distance')
            ->get();
        return $results;
    }



    public function cusineList() 
    {
        $results= DB::table('cuisine') ->select('*')->get();
        return $results ;        
    }


    public function sortedSearch($city,$type , $sortorder,$min_price,$max_price,$cuisines_in){
        $lat=$this->lat;
        $lng=$this->lng;
        $results= DB::table('restaurant_details')
            ->join('rest_cuisine_list', 'restaurant_details.id', '=', 'rest_cuisine_list.restaurant_id')
            ->join('cuisine', 'cuisine.id', '=', 'rest_cuisine_list.cuisine_id')
            ->join('city', 'city.id', '=', 'restaurant_details.city_id')
            ->leftjoin('areas', 'areas.area_id', '=', 'restaurant_details.area_id')
            ->where('city.city_url_name', '=', $city)
            ->where('restaurant_details.is_active', '=', 'open')
            ->where(function($query) use ($min_price,$max_price,$cuisines_in) 
                {
                        
                        if(isset($cuisines_in))
                        {
                            foreach($cuisines_in as $cusine)
                            {
                                $query->orwhere('cuisine.cuisine_name', $cusine) ;  
                            }
                        }
                        if(isset($min_price) && $min_price >= 0 )
                        {                       
                          $query->where('restaurant_details.cost_two_people','>=',$min_price) ;
                        }
                        if(isset($max_price) && $max_price >= 0 )
                        {                       
                            $query->where('restaurant_details.cost_two_people','<=',$max_price);
                        }
                    return $query ; 
                }) 
            //->select('*')
            ->select(DB::raw("*, restaurant_details.name as name, areas.name as area_name,group_concat(cuisine_name separator ', ') as cuisine_name, (CASE  WHEN lat IS NULL THEN 10000 ELSE  ( 6371 * acos( cos( radians(".$lat.") ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(".$lng.") ) + sin( radians(".$lat.") ) * sin( radians( lat ) ) ) ) END) AS distance"))
            ->groupBy("rest_cuisine_list.restaurant_id")
            ->orderBy('is_homely','desc') 
            ->orderBy($type, $sortorder) 
            ->get();

        return $results;
    }
}
