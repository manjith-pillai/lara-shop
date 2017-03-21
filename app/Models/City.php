<?php namespace App;
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class City extends Model {

	protected $table = 'city';
	public $timestamps = false;
	protected $fillable = ['city_name','country','default_lat','default_lng ','city_url_name','radius'];

}