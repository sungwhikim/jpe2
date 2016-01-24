<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CountryController extends Controller
{
    public function getList()
    {
        $countries = Country::select('id', 'code', 'name')->orderBy('code')->get();
        $url = url('/country');

        return response()->view('pages.country', array('countries' => $countries, 'url' => $url));
    }

    public function getIndex($id)
    {
        return Country::where('id', '=', $id)->orderBy('code')->get();
    }

    public function postNew()
    {
        $country =  new Country();
        $country->code = request()->json('code');
        $country->name = request()->json('name');
        $country->save();

        return response()->json(array('id' => $country->id));
    }

    public function postUpdate()
    {
        $country = Country::find(request()->json('id'));
        $country->code = request()->json('code');
        $country->name = request()->json('name');
        $country->save();
    }

    public function putDelete($id)
    {
        Country::find($id)->delete();

        return Country::all();
    }

    public function getRestore($id)
    {
        Country::withTrashed()
            ->where('id', $id)
            ->restore();

        return Country::all();
    }
}