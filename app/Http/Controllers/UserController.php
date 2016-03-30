<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\UserGroup;
use App\Models\Warehouse;
use App\Models\Client;
use App\Models\UserWarehouse;
use App\Models\UserClient;

class UserController extends Controller
{
    protected $my_name = 'user';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getDashboard()
    {
        debugbar()->info(auth()->user()->warehouseClientList());
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

        //add warehouses and clients to each user - this is slow, but users are not used very often and there
        //are not that many users.
        foreach( $data as $item )
        {
            $item->warehouses = UserWarehouse::where('user_id', '=', $item->id)
                                               ->lists('warehouse_id')->toArray();
            $item->clients = UserClient::where('user_id', '=', $item->id)
                                         ->lists('client_id')->toArray();
        }

        //we need to send the url to do Ajax queries back here
        $url = url('/user');

        //get the data lists
        $warehouse_data = Warehouse::select('id', 'name', 'active')->orderBy('name')->get();
        $client_data = Client::select('id', 'short_name', 'name', 'active')->orderBy('name')->get();
        $user_groups = UserGroup::where('active', '=', true)->orderBy('name')->get();

        return view('pages.user', ['main_data' => $data,
                                   'url' => $url,
                                   'my_name' => $this->my_name,
                                   'user_group_data' => $user_groups,
                                   'warehouse_data' => $warehouse_data,
                                   'client_data' => $client_data]);
    }

    public function getCheckDuplicate($username)
    {
        $users = User::where('username', 'ILIKE', $username)->get();

        return $users;
    }

    public function postNew()
    {
        //validate password
        $this->validatePassword();

        //set username to a variable
        $username = request()->json('username');

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

    public function setWarehouseClient($warehouse_id, $client_id)
    {
        $user = auth()->user();
        $user->current_warehouse_id = $warehouse_id;
        $user->current_client_id = $client_id;
        $user->save();
    }

    private function saveItem()
    {
        $user = ( !empty(request()->json('id')) ) ? User::find(request()->json('id')) : new User();
        $user->username             = request()->json('username');
        $user->name                 = request()->json('name');
        $user->email                = request()->json('email');
        $user->user_group_id        = request()->json('user_group_id');
        $user->active               = ( !empty(request()->json('active')) ) ? true : false;

        //Only update/add password if it was set
        if( !empty(request()->json('password')) )
        {
            //first make sure they are the same
            $this->validatePassword();

            //set password
            $user->password = bcrypt(request()->json('password'));
        }

        $user->save();

        /* Update warehouses */
        //delete all current data
        UserWarehouse::where('user_id', '=', $user->id)->delete();

        //add warehouses
        $warehouses = request()->json('warehouses', []);
        foreach( $warehouses as $key => $warehouse_id )
        {
            $object = new UserWarehouse();
            $object->user_id = $user->id;
            $object->warehouse_id = $warehouse_id;
            $object->save();
        }

        /* Update clients */
        //delete all current data
        UserClient::where('user_id', '=', $user->id)->delete();

        //add clients
        $clients = request()->json('clients', []);
        foreach( $clients as $key => $client_id )
        {
            $object = new UserClient();
            $object->user_id = $user->id;
            $object->client_id = $client_id;
            $object->save();
        }

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