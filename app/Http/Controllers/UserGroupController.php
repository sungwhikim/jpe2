<?php
namespace App\Http\Controllers;

use App\Models\UserGroup;
use App\Models\UserFunction;
use App\Models\UserFunctionCategory;

class UserGroupController extends Controller
{
    protected $my_name = 'user group';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = UserGroup::orderBy('name')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/user-group');

        return response()->view('pages.user-group', ['main_data' => $data,
                                                     'url' => $url,
                                                     'my_name' => $this->my_name,
                                                     'user_functions' => $this->getUserFunctionList()]);
    }

    public function getUserFunctionList()
    {
        //get just the function categories
        $categories = UserFunctionCategory::where('active', '=', true)
                                            ->orderBy('sort_order')->get();

        //loop through and add the user functions.  Yes, it is more efficient to use
        //a single query, then parse, but there are only 4 categories.
        foreach( $categories as $category )
        {
            //get the functions for this category
            $functions = UserFunction::where('user_function_category_id', '=', $category->id)
                                       ->orderBy('sort_order')->get();

            //assign to return array
            $user_functions[] = array('category_name' => $category->name,
                                      'functions' => $functions);
        }

        return $user_functions;
    }

    public function getById($id)
    {
        return UserGroup::where('id', '=', $id)->get();
    }

    public function getCheckDuplicate($name)
    {
        return UserGroup::select('id')->where('name', 'ILIKE', $name)
                                      ->take(1)->get();
    }

    public function postNew()
    {
        //set to a variables;
        $name = request()->json('name');

        //first check to make sure this is not a duplicate
        $user_groups = $this->getCheckDuplicate($name);
        if( count($user_groups) > 0 )
        {
            $error_message = array('errorMsg' => 'The user group with name of ' . $name . ' already exists.');
            return response()->json($error_message);
        }

        //create new item
        $user_group_id = $this->saveItem();

        return response()->json(['id' => $user_group_id]);
    }

    public function postUpdate()
    {
        $this->saveItem();
    }

    private function saveItem()
    {
        $user_group = ( !empty(request()->json('id')) ) ? UserGroup::find(request()->json('id')) : new UserGroup();
        $user_group->name        = request()->json('name');
        $user_group->active      = ( !empty(request()->json('active')) ) ? true : false;
        $user_group->save();

        return $user_group->id;
    }

    public function putDelete($id)
    {
        UserGroup::find($id)->delete();

        return UserGroup::all();
    }
}
?>