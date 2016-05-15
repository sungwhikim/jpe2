<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
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

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectPath = '/home';
    protected $loginPath = '/login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * The login form
     *
     * @param Request $request
     *
     * @return view::login page
     */
    public function getIndex(Request $request)
    {
        return view('pages.login');
    }

    /**
     * Process login
     *
     * @return redirect to dashboard or back to home page
     */
    public function postLogin()
    {
        //set variables
        $username = request()->get('username');
        $password = request()->get('password');
        $remember_me = request()->get('remember_me', 0);

        //flash it so it is available in case we need to direct the user back to login page
        request()->flashOnly('username');

        if( auth()->attempt(['username' => $username, 'password' => $password, 'active' => true], $remember_me) )
        {
            // Authentication passed...
            return redirect()->intended('/dashboard');
        }
        else
        {
            //--Authentication failed--
            //log failure

            //set flash message
            session()->flash('alert-type', 'danger');
            session()->flash('alert-message', 'Username and/or password invalid.');

            //logout user
            auth()->logout();

            //send back to login page
            return redirect('/login');
        }
    }

    /**
     * Logout - override default method to flash data
     *
     * @return redirect to home page
     */
    public function getLogout()
    {
        //set flash message
        session()->flash('alert-type', 'info');
        session()->flash('alert-message', 'You have been logged out.');

        //logout user
        auth()->logout();

        //send back to home page
        return redirect('/login');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:20|unique:user',
            'name' => 'required|max:100',
            'email' => 'required|email|max:100|unique:user',
            'password' => 'required|confirmed|min:8',
        ]);
    }

}
