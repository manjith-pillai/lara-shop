<?php namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Restaurant;
use \App\Models\Search;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class FooterController extends Controller {

	public function showContactUsPage(){
		$title = "Zapdel: Food Delivery | Restaurant Takeout | Order Online - Contact Us";
        $keywords = "";
        $description = "";
		return view ('contact',compact('title','keywords','description'));
	}

	public function showAboutUsPage(){
		$title = "Zapdel: Food Delivery | Restaurant Takeout | Order Online - About Us";
        $keywords = "";
        $description = "";
		return view ('about',compact('title','keywords','description'));
	}

	public function showFaqPage(){
		$title = "Zapdel: Food Delivery | Restaurant Takeout | Order Online - FAQ";
        $keywords = "";
        $description = "";
		return view ('faq',compact('title','keywords','description'));
	}

	public function showCnrPage(){
		$title = "Zapdel: Food Delivery | Restaurant Takeout | Order Online - Cancellations & Refund";
        $keywords = "";
        $description = "";
		return view ('cnr',compact('title','keywords','description'));
	}

	public function showTncPage(){
		$title = "Zapdel: Food Delivery | Restaurant Takeout | Order Online - Terms & Conditions";
        $keywords = "";
        $description = "";
		return view ('tnc',compact('title','keywords','description'));
	}

	public function showPrivacyPolicyPage(){
		$title = "Zapdel: Food Delivery | Restaurant Takeout | Order Online - Privacy Policy";
        $keywords = "";
        $description = "";
		return view ('privacy_policy',compact('title','keywords','description'));
	}

	public function showOsrPage(){
		$title = "Zapdel: Food Delivery | Restaurant Takeout | Order Online - Our Social Responsibilites";
        $keywords = "";
        $description = "";
		return view ('osr',compact('title','keywords','description'));
	}

	public function showShippingPage(){
		$title = "Zapdel: Food Delivery | Restaurant Takeout | Order Online - Shipping & Delivery Policy";
        $keywords = "";
        $description = "";
		return view ('shipping',compact('title','keywords','description'));
	}

	public function careerPage(){
		$title = "";
        $keywords = "";
        $description = "";
		return view ('careers',compact('title','keywords','description'));
	}
	public function careerList(){
		$title = "";
        $keywords = "";
        $description = "";
		return view ('career-list',compact('title','keywords','description'));
	}
	public function error500Page(){
		$title = "Zapdel: Food Delivery | Restaurant Takeout | Order Online - 500";
        $keywords = "";
        $description = "";
		return view('errors.500',compact('title','keywords','description'));
	}
	public function error404Page(){
		$title = "Zapdel: Food Delivery | Restaurant Takeout | Order Online - 404";
        $keywords = "";
        $description = "";
		return view('errors.404',compact('title','keywords','description'));
	}

}
