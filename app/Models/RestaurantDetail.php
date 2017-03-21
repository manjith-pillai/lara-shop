<?php namespace App;
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RestaurantDetail extends Model {

	protected $table = 'restaurant_details';
	public $timestamps = false;

	 protected $fillable = [
        'name',
        'address',
        'lng',
        'lat',
        'phone',
        'img',
        'cost_two_people',
        'email',
        'url_name',
        'city_id',
        'area_id',
        'timing',
        'open_time',
        'close_time',
        'contact_person',
        'contact_person_phone',
        'tax_id',
        'rating',
        'featured',
        'is_active',
        'meta_keywords',
        'meta_description',
    ];


}
