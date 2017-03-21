<?php namespace App;
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {

	protected $table = 'category';
	public $timestamps = false;
	protected $fillable = ['cat_name','category_description'];

}
