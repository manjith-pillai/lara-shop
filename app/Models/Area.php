<?php namespace App;
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Area extends Model {

	protected $table = 'areas';
	public $timestamps = false;
    protected $primaryKey = 'area_id';

    protected $fillable = ['name','city_id'];

    public function getArea($id) {
        $area = DB::table('areas')->select('areas.name')->where('areas.area_id','=',$id)->first();
        return $area->name;

    }

    public function getCity($id){
        $city = DB::table('city')->select('city.city_name')->where('city.id','=',$id)->first();
        return $city->city_name;
    }

}
