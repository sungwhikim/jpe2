<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\UserGroup;

class UserController extends Controller
{
    protected $my_name = 'user';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getDashboard()
    {
        //this validates that the user has access to this user function
        //User::validateRoute();

        return view('pages.dashboard');
    }

    /**
     * Validates the route to make sure the user has access
     *
     * @return redirects to login page if user does not have access
     */
    public function checkRoute()
    {
        //set a string to be identical to the url/route that is set in the database
        $route = '/' . request()->route()->uri();


        $route = '/users';

        debugbar()->info($route);
        //get the list of user functions
        $user_functions = auth()->user()->userFunctions();

        //see if it is in the list
        $found = false;
        foreach( $user_functions->get('url') as $url )
        {
            $test[] = $url . '|' . $route;
            if( $url == $route )
            {
                $found = true;
            }
        }

        //redirect to login page with error if route is not found
        if( $found = true )
        {
            //set flash message
            session()->flash('alert-type', 'danger');
            session()->flash('alert-message', 'You do not have access to the requested function.');

            //send back to home page
            return redirect('/logout');
        }

        return true;
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = User::orderBy('name')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/user');

        //get user groups
        $user_groups = UserGroup::where('active', '=', true)->orderBy('name')->get();

        return view('pages.user', ['main_data' => $data,
                                   'url' => $url,
                                   'my_name' => $this->my_name,
                                   'user_group_data' => $user_groups]);
    }

    public function getById($id)
    {
        return User::where('id', '=', $id)->get();
    }

    public function getCheckDuplicate($username)
    {
        $users = User::where('username', 'ILIKE', $username)->get();
        debugbar()->info($users);
        return $users;
    }

    public function postNew()
    {
        //validate password
        $this->validatePassword();

        //set username to a variable
        $username = request()->json('username');
        debugbar()->info($username);
        //first check to make sure this is not a duplicate
        $users = $this->getCheckDuplicate($username);
        if( count($users) > 0 )
        {
            $error_message = array('errorMsg' => 'The User Name ' . $username . ' already exists.');
            return response()->json($error_message);
        }

        //create new item
        $user_id = $this->saveItem();

        return response()->json(['id' => $user_id]);
    }

    public function postUpdate()
    {
        $this->saveItem();
    }

    private function saveItem()
    {
        $user = ( !empty(request()->json('id')) ) ? User::find(request()->json('id')) : new User();
        $user->username      = request()->json('username');
        $user->name          = request()->json('name');
        $user->email         = request()->json('email');
        $user->user_group_id = request()->json('user_group_id');
        $user->active        = ( !empty(request()->json('active')) ) ? true : false;

        //Only update/add password if it was set
        if( !empty(request()->json('password')) )
        {
            //first make sure they are the same
            $this->validatePassword();

            //set password
            $user->password = bcrypt(request()->json('password'));
        }

        $user->save();

        return $user->id;
    }

    public function putDelete($id)
    {
        User::find($id)->delete();
    }

    protected function validatePassword()
    {
        if( request()->json('password') !== request()->json('password_validate') )
        {
            $error_message = array('errorMsg' => 'The passwords must match.');
            return response()->json($error_message);
        }
    }

}