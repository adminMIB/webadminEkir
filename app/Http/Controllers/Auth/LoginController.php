<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
	protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	
	/*
	* Login Authenticate
	*/
	public function login(Request $request){
		$input = $request->all();

		//dd(Hash::make('1'));

		//$username = isset($input['username']) ? $input['username'] : null;
		$email = isset($input['username']) ? $input['username'] : null;
		$password = isset($input['password']) ? $input['password'] : null;
		if($email && $password){
			$User = \DB::table('mgt_users')
				->select("mgt_users.*")
				->where('user_login', $email)
				->where('user_status', '1')
				->first();
			
			if($User){
				if (Hash::check($password, $User->user_pass)) {
					//Last Login, updated when user has logout
					$User->user_logged_in =date("Y-m-d H:i:s");

					//Get Role User
					$User->user_role_as ="";
					$User->user_role_display ="";
					$Role = \DB::table("mgt_roles")->where("role_id", $User->user_role)->first();
					if($Role){
						$User->user_role_as = $Role->role_name;
						$User->user_role_display = $Role->role_display_name;
					}

					if($User->user_url == ""){
						$User->user_url =asset('assets/img/avatars/no-image-profile-200-200.jpg');
					}else{
						$User->user_url = url("/")."/assets/img/".$User->user_url;
					}

					// The passwords match...
					$User->pwd = $password;
					$request->session()->put('user', $User);
					return redirect($this->redirectTo);
				}else{
					$request->session()->flash('error', config("message.login_error")); 
					return redirect()->route('login');		
				}
			}else{
				$request->session()->flash('error', config("message.login_error")); 
				return redirect()->route('login');	
			}
		}else{
			$request->session()->flash('error', config("message.login_error")); 
			return redirect()->route('login');
		}
	}
	
	/*
	* Logout
	*/
	public function logout(Request $request){
		// Forget a single key...
		$request->session()->forget('user');
		return redirect('/login');
	}

}
