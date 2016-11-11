<?php

namespace App\Http\Controllers;

use App\Models\Site;

class SiteController extends Controller
{
    protected $my_name = 'site';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = Site::orderBy('id')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/site');

        return view('pages.site', ['main_data' => $data, 'url' => $url, 'my_name' => $this->my_name]);
    }

    public function getCheckDuplicate($name)
    {
        return Site::select('id')->where('name', 'ILIKE', $name)->take(1)->get();
    }

    public function postNew()
    {
        //set name to a variable
        $name = request()->json('name');

        //first check to make sure this is not a duplicate
        $sites = $this->getCheckDuplicate($name);
        if( count($sites) > 0 )
        {
            $error_message = array('errorMsg' => 'The site name of ' . $name . ' already exists.');
            return response()->json($error_message);
        }

        //create new item
        $site_id = $this->saveItem();

        return response()->json(['id' => $site_id]);
    }

    public function postUpdate()
    {
        return $this->saveItem();
    }

    private function saveItem()
    {
        $site = ( !empty(request()->json('id')) ) ? Site::find(request()->json('id')) : new Site();
        $site->title = request()->json('title');
        $site->name = request()->json('name');
        $site->domain = request()->json('domain');
        $site->handle = request()->json('handle');
        $site->email_admin_name = request()->json('email_admin_name');
        $site->save();

        return $site->id;
    }

    public function putDelete($id)
    {
        Site::find($id)->delete();
    }
}