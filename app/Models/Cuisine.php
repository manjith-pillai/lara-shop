<?php namespace App;
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cuisine extends Model {

	protected $table = 'cuisine';
	public $timestamps = false;
	protected $fillable = ['cuisine_name'];

}