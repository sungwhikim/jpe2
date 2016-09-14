<?php

namespace App\Http\Controllers;

use App\Models\Province;

class ProvinceController extends Controller
{
    protected $my_name = 'province';

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = Province::orderBy('name')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/province');

        return view('pages.province', ['main_data' => $data, 'url' => $url, 'my_name' => $this->my_name]);
    }

    public function getById($id)
    {
        return Province::where('id', '=', $id)->get();
    }

    public function getCheckDuplicate($provincename)
    {
        $provinces = Province::where('provincename', 'ILIKE', $provincename)->get();
        return $provinces;
    }

    public function postNew()
    {
        //validate password
        $this->validatePassword();

        //set provincename to a variable
        $provincename = request()->json('provincename');

        //first check to make sure this is not a duplicate
        $provinces = $this->getCheckDuplicate($provincename);
        if( count($provinces) > 0 )
        {
            $error_message = array('errorMsg' => 'The Province Name ' . $provincename . ' already exists.');
            return response()->json($error_message);
        }

        //create new item
        $province_id = $this->saveItem();

        return response()->json(['id' => $province_id]);
    }

    public function postUpdate()
    {
        return $this->saveItem();
    }

    private function saveItem()
    {
        $province = ( !empty(request()->json('id')) ) ? Province::find(request()->json('id')) : new Province();
        $province->provincename = request()->json('provincename');
        $province->name     = request()->json('name');
        $province->email    = request()->json('email');
        $province->active      = ( !empty(request()->json('active')) ) ? true : false;

        //Only update/add password if it was set
        if( !empty(request()->json('password')) )
        {
            //first make sure they are the same
            $this->validatePassword();

            //set password
            $province->password = bcrypt(request()->json('password'));
        }

        $province->save();

        return $province->id;
    }

    public function putDelete($id)
    {
        Province::find($id)->delete();

        return Province::all();
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