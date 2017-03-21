<?php namespace App;
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RestTaxDetail extends Model {

    protected $primaryKey = 'tax_id';
	protected $table = 'rest_tax_details';
	protected $fillable = ['service_tax_percent','vat_percent','service_charge_percent','packaging_charge'];
	public $timestamps = false;

}
