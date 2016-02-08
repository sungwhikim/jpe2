<?php

namespace App\Http\Controllers;

use App\Models\UserFunctionCategory;

class UserFunctionCategoryController extends Controller
{
    protected $my_name = 'user function category';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = UserFunctionCategory::orderBy('sort_order')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/user-function-category');

        return view('pages.user-function-category', ['main_data' => $data, 'url' => $url, 'my_name' => $this->my_name]);
    }

    public function getById($id)
    {
        return UserFunctionCategory::where('id', '=', $id)->get();
    }

    public function getCheckDuplicate($name)
    {
        return UserFunctionCategory::where('name', 'ILIKE', $name)->get();
    }

    public function postNew()
    {
        //set code to a variable
        $name = request()->json('name');

        //first check to make sure this is not a duplicate
        $items = $this->getCheckDuplicate($name);
        if( count($items) > 0 )
        {
            $error_message = array('errorMsg' => 'The user function category name ' . $name . ' already exists.');
            return response()->json($error_message);
        }

        //create new item
        $id = $this->saveItem();

        return response()->json(['id' => $id]);
    }

    public function postUpdate()
    {
        $this->saveItem();
    }

    private function saveItem()
    {
        $user_function_category = ( !empty(request()->json('id')) ) ? UserFunctionCategory::find(request()->json('id')) : new UserFunctionCategory();
        $user_function_category->name = request()->json('name');
        $user_function_category->sort_order = request()->json('sort_order');
        $user_function_category->active = ( !empty(request()->json('active')) ) ? true : false;
        $user_function_category->save();

        return $user_function_category->id;
    }

    public function putDelete($id)
    {
        UserFunctionCategory::find($id)->delete();

        return UserFunctionCategory::all();
    }
}