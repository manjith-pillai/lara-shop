<?php namespace App;
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DishDetail extends Model {

	protected $table = 'dish_details';
	public $timestamps = false;
	protected $fillable = ['dish_name','category_id','description','veg_flag'];

}
