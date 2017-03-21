<?php namespace App;
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RestaurantDish extends Model {

	protected $table = 'restaurant_dishes';
	public $timestamps = false;
	protected $fillable = ['dish_id','restaurant_id','price'];

}
