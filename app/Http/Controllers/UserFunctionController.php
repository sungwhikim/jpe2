<?php
namespace App\Http\Controllers;

use App\Models\UserFunction;
use App\Models\UserFunctionCategory;
use App\Models\UserGroupToUserFunction;


class UserFunctionController extends Controller
{
    protected $my_name = 'user function';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = UserFunction::select('user_function.*', 'user_function_category_name as category_name', 'user_function_category_id as category_id')
                              ->orderBy('name')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/user-function');

        //get user function category data
        $user_function_categories = UserFunctionCategory::orderBy('name')->get();

        return response()->view('pages.user-function', ['main_data' => $data,
                                                        'url' => $url,
                                                        'my_name' => $this->my_name,
                                                        'category_data' => $user_function_categories]);
    }

    public function getListMenu()
    {

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
            //don't check for duplicates at this time as we need to allow multiple spacers
            //$error_message = array('errorMsg' => 'The user function with name of ' . $name . ' already exists.');
            //return response()->json($error_message);
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
        $user_function->url         = request()->json('url');
        $user_function->sort_order  = request()->json('sort_order');
        $user_function->user_function_category_id = request()->json('category_id');
        $user_function->user_function_category_name = UserFunctionCategory::where('id', '=', request()->json('category_id'))->value('name');
        $user_function->active      = ( !empty(request()->json('active')) ) ? true : false;
        $user_function->save();

        return $user_function->id;
    }

    public function putDelete($id)
    {
        //first delete from user group to user function table
        UserGroupToUserFunction::where('user_function_id','=', $id)->delete();

        //delete the user function
        UserFunction::find($id)->delete();
    }
}
