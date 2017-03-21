<?php namespace App\Http\Controllers\Auth;

use Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AppController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\MessageBag;
use Validator;
use DB;
class AuthController extends Controller {

	use DispatchesCommands, ValidatesRequests;
	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/
 	use AuthenticatesAndRegistersUsers;
	protected $redirectTo = '/auth/login';

	protected $loginPath = '/auth/login';

    protected $redirectPath = '/auth/login';

    protected $redirectAfterLogout = '/';

    
	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}
    
	public function getLogin()
    {
        return view('auth.login');
    }


	public function getRegister()
    {
        return view('auth.register');
    }
	

	/**
	*	Login For Users  via API   POST ONLY
	*	Validates if the user is logged in or not  
	* 	Returns User profile  if valid or else  returns  '405'
	*	INPUT email - string , password - string
	*	OutPut	
	*	{	"status":200,		-- Integer
	*		"message":"Login Successful", -- String
	*		"value":		-- Optional 
	*			{	"id":1,
	*				"name":"Hemal", -- String
	*				"email":"", -- String
	*				"created_at":"2016-01-27 13:46:36", -- Timestamp
	*				"updated_at":"2016-02-04 18:16:22", --  TimeStamp 
	*				"is_active":1, -- boolean int
	*				"phone":""		-- String 
	*			}
	*	}	
	*	
	*/
    public function userLogin()
    {
        $request =  new Request();
		$email = $request::input('email'); 
		$password = $request::input('password');        
		$credentials = ['email' => $email, 'password' => $password] ;
        if($this->auth->attempt($credentials,false,true) )
        {
        	$response['status'] = 200 ;
        	$response['message'] = "Login Successful" ;
        	$response['value'] = $this->auth->getUser() ;
                $response['value']['user_addresses'] = DB::table('address')->where('customer_id', $this->auth->getUser()->id)->get();
        	return json_encode($response) ;
        }
        else
        {
        	$response['status'] = 405 ;
        	$response['message'] = "Login Failure" ;
        	return json_encode($response) ;
        }
	}

	/**
	*	Registration For Users  via API  POST ONLY
	*	Validates if the user details are valid if yes return user object  
	* 	Returns User profile  if valid or else  returns  '406'
	*	INPUT email - string , password - string, phone - string,  name - String, confirm_password - String 
	*	OutPut	
	*	{	"status":200,		-- Integer
	*		"message":"Registration Failed with error", -- String
	*		"value":		-- Optional 
	*			{
	*				"name":"hemal11",
	*				"email":"hemal11@11.co",
	*				"updated_at":"2016-02-08 17:38:46",
	*				"created_at":"2016-02-08 17:38:46",
	*				"id":2
	*			}
	*	}	
	*	
	*/
	public function registerUser() {
            $request =  new Request();
            $email = $request::input('email'); 
            $password = $request::input('password');        
            $confirm_password = $request::input('confirm_password'); 
            $name = $request::input('name');
            $phone = (null != $request::input('phone')) ? $request::input('phone') : '' ;
            $is_phone_verified = (null != $request::input('is_phone_verified')) ? $request::input('is_phone_verified') : 0;
            $credentials = ['email' => $email,'phone' => $phone ,'password' => $password, 'confirm_password' => $confirm_password, 'name' => $name, 'is_phone_verified' => $is_phone_verified];
            $validation_rules = array();
            $validation_rules['email'] = 'email|required|unique:users,email';
            $validation_rules['password'] = 'required';
            $validation_rules['confirm_password'] = 'required';
            $validation_rules['name'] = 'required';
            $validation_rules['phone'] = 'unique:users,phone';
            $validator = Validator::make($request::all(), $validation_rules);
            if ($validator->fails()) {
                $response['status'] = 406 ;
                $response['message'] = $validator->messages()->first();
                return json_encode($response);
            } elseif ($password != $confirm_password)
                  {
                    $response['status'] = 406 ;
                    $response['message'] = "Password does not match" ;
                    return json_encode($response) ;
                  }

            else {
                try {
                    $data_du = $this->registrar->create($credentials);
                    $response['status'] = 200 ;
                    $response['message'] = "Registration Successful" ;
                    $response['value'] = $data_du ;
                    $response['value']['user_addresses'] = DB::table('address')->where('customer_id', $data_du->id)->get();
                    return json_encode($response) ;
                } catch (Illuminate\Database\QueryException $e) {
                    $response['status'] = 406 ;
                    $response['message'] = "Registration Failed with error ".$e ;
                    return json_encode($response) ;
	    	}
	    }
	}





     public function changePassword() {

            $request = new request();
            $email = $request::input('email');
            $oldpassword = $request::input('old_password');   
            $newpassword = $request::input('new_password');
            $confirmpassword = $request::input('confirm_password');
            $validation_rules = array();
            $validation_rules['email'] = 'required|email';
            $validation_rules['old_password'] = 'required';
            $validation_rules['new_password'] = 'required';
            $validation_rules['confirm_password'] = 'required';
            $validator = validator::make($request::all(), $validation_rules);
            if ($validator->fails())
        {
            $response['status'] = 406 ;
            $response['message'] = $validator->messages()->first();
            return json_encode($response) ;
        } elseif($newpassword != $confirmpassword) {
            $response['status'] = 406 ;
            $response['message'] = "Password don't match";
            return json_encode($response) ;
        } else {
            if($this->auth->attempt(['email'=>$email, 'password' =>$oldpassword],false,true) ){
                DB::table('users')->where('email',$email)->update(['password' => bcrypt($newpassword)]);    
                $response['status'] = 200 ;
                $response['message'] = "Your pass has been changed";
                return json_encode($response) ;
            } else {
                $response['status'] = 406 ;
                $response['message'] = "The email or password you have entered is incorrect.";
                return json_encode($response) ;
            }
        }
   }

   public function fbLogin(){

            $request =  new Request();
            $fb_id = $request::input('fb_id');
            $email = $request::input('email'); 
            $name = $request::input('name');     
            $validation_rules = array();
            $validation_rules['fb_id'] = 'required';
            $validation_rules['email'] = 'required|email';
            $validation_rules['name'] = 'required';
            $validator = validator::make($request::all(), $validation_rules);
            if ($validator->fails())
            {
                $response['status'] = 406 ;
                $response['message'] = $validator->messages()->first();
                return json_encode($response) ;
            } else {
                   $result = DB::table('users')->where('email', $email)->first();
                   if($result) {
                        $response['status'] = 200 ;
                        $response['message'] = "Login Successful" ;
                        DB::table('users')->where('email', $email)->update(['name' => $name, 'email' => $email, 'fb_id' => $fb_id]);
                        $fb_user_info = DB::table('users')->where('email', $email)->select('id','name', 'email', 'is_active', 'phone', 'is_phone_verified')->first();
                        $response['value'] = $fb_user_info;
                        $response['value']->user_addresses = DB::table('address')->where('customer_id', $fb_user_info->id)->get();
                        return json_encode($response) ;
                    } else {
                        $password = bcrypt(mt_rand(10000000,999999999));
                        DB::table('users')->insert(['email' => $email, 'name' => $name, 'fb_id' => $fb_id, 'password' => $password]);
                        $response['status'] = 200 ;
                        $response['message'] = "Login Successful" ;
                        $fb_user_info = DB::table('users')->where('email', $email)->select('id','name', 'email', 'is_active', 'phone', 'is_phone_verified')->first();
                        $response['value'] = $fb_user_info;
                        $response['value']->user_addresses = DB::table('address')->where('customer_id', $fb_user_info->id)->get();
                        return json_encode($response) ;
            
                      }
               }
     }

     public function forgetPassword() {
               
               $appcontroller = new AppController();
               return $appcontroller->sendOTP();


        }

}
