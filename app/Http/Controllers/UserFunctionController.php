<?php
namespace App\Http\Controllers;

use App\Models\UserFunction;


class UserFunctionController extends Controller
{
    protected $my_name = 'user_function';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = UserFunction::join('user_function u2', 'u2.parent_id', '=', 'user_function.id')
                          ->orderBy('user_function.name')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/user_function');

        return response()->view('pages.user_function', ['main_data' => $data,
                                                        'url' => $url,
                                                        'my_name' => $this->my_name]);
    }

    public function getById($id)
    {
        return UserFunction::where('id', '=', $id)->get();
    }

    public function getCheckDuplicate($name)
    {
        return UserFunction::select('id')->where('name', 'ILIKE', $name)->take(1)->get();
    }

    public function postNew()
    {
        //set to a variables
        $name = request()->json('name');

        //first check to make sure this is not a duplicate
        $user_functions = $this->getCheckDuplicate($name);
        if( count($user_functions) > 0 )
        {
            $error_message = array('errorMsg' => 'The user_function with name of ' . $name . ' already exists.');
            return response()->json($error_message);
        }

        //create new item
        $user_function_id = $this->saveItem();

        return response()->json(['id' => $user_function_id]);
    }

    public function postUpdate()
    {
        $this->saveItem();
    }

    private function saveItem()
    {
        $user_function = ( !empty(request()->json('id')) ) ? UserFunction::find(request()->json('id')) : new UserFunction();
        $user_function->name        = request()->json('name');
        $user_function->contact     = request()->json('contact');
        $user_function->email       = request()->json('email');
        $user_function->phone       = request()->json('phone');
        $user_function->fax         = request()->json('fax');
        $user_function->address1    = request()->json('address1');
        $user_function->address2    = request()->json('address2');
        $user_function->city        = request()->json('city');
        $user_function->postal_code = request()->json('postal_code');
        $user_function->province_id = request()->json('province_id');
        $user_function->country_id  = request()->json('country_id');
        $user_function->active      = ( !empty(request()->json('active')) ) ? true : false;
        $user_function->save();

        return $user_function->id;
    }

    public function putDelete($id)
    {
        UserFunction::find($id)->delete();

        return UserFunction::all();
    }
}
