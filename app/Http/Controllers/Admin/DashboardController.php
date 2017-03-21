<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Contracts\Auth\Guard;
class DashboardController extends Controller {

	protected $auth;
	
    public function __construct(Guard $auth) {
		$this->middleware('auth');
		$this->auth = $auth;
    }

    public function index()
    {
    	return view('admin.dashboard.layouts.home');
    }


}