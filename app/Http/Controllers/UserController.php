<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    protected $my_name = 'user';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getDashboard()
    {
        return view('pages.dashboard');
    }

    public function getListView()
    {

        //get the list data with the default sort set the same as in the angular table
        $data = User::orderBy('name')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/user');

        return view('pages.user', ['main_data' => $data, 'url' => $url, 'my_name' => $this->my_name]);
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
        $user->username = request()->json('username');
        $user->name     = request()->json('name');
        $user->email    = request()->json('email');
        $user->active      = ( !empty(request()->json('active')) ) ? true : false;

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

        return User::all();
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