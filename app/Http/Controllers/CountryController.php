<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CountryController extends Controller
{
    protected $my_name = 'country';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = Country::select('id', 'code', 'name', 'currency_name', 'currency_prefix')->orderBy('code')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/country');

        return view('pages.country', ['main_data' => $data, 'url' => $url, 'my_name' => $this->my_name]);
    }

    public function getById($id)
    {
        return Country::where('id', '=', $id)->get();
    }

    public function getByCode($code)
    {
        return Country::select('id')->where('code', 'ILIKE', $code)->take(1)->get();
    }

    public function getCheckDuplicate($code)
    {
        return $this->getByCode($code);
    }

    public function postNew()
    {
        //set code to a variable
        $code = request()->json('code');

        //first check to make sure this is not a duplicate
        $countries = $this->getCheckDuplicate($code);
        if( count($countries) > 0 )
        {
            $error_message = array('errorMsg' => 'The country code of ' . $code . ' already exists.');
            return response()->json($error_message);
        }

        //create new item
        $country_id = $this->saveItem();

        return response()->json(['id' => $country_id]);
    }

    public function postUpdate()
    {
        $this->saveItem();
    }

    private function saveItem()
    {
        $country = ( !empty(request()->json('id')) ) ? Country::find(request()->json('id')) : new Country();
        $country->code = request()->json('code');
        $country->name = request()->json('name');
        $country->currency_name = request()->json('currency_name');
        $country->currency_prefix = request()->json('currency_prefix');
        $country->save();

        return $country->id;
    }

    public function putDelete($id)
    {
        Country::find($id)->delete();
    }
}