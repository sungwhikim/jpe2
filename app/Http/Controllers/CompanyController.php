<?php
namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Country;
use App\Models\Province;

class CompanyController extends Controller
{
    protected $my_name = 'company';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListView()
    {
        //get the list data with the default sort set the same as in the angular table
        $data = Company::select('company.*', 'province.name as province_name')
                          ->join('province', 'company.province_id', '=', 'province.id')
                          ->orderBy('company.name')->get();

        //we need to send the url to do Ajax queries back here
        $url = url('/company');

        return response()->view('pages.company', ['main_data' => $data,
                                                    'url' => $url,
                                                    'my_name' => $this->my_name,
                                                    'country_data' => $this->getCountryList()]);
    }

    private function getCountryList()
    {
        //first get the list of countries
        $countries = Country::select('id', 'code', 'name')->orderBy('code')->get();

        //loop through and add the provinces
        foreach( $countries as $country )
        {
            $provinces = Province::select('id', 'code', 'name')->where('country_id', '=', $country->id)->get();
            $country->provinces = $provinces->toArray();
        }

        return $countries;
    }

    public function getById($id)
    {
        return Company::where('id', '=', $id)->get();
    }

    public function getCheckDuplicate($name)
    {
        return Company::select('id')->where('name', 'ILIKE', $name)->take(1)->get();
    }

    public function postNew()
    {
        //set to a variables
        $name = request()->json('name');

        //first check to make sure this is not a duplicate
        $companys = $this->getCheckDuplicate($name);
        if( count($companys) > 0 )
        {
            $error_message = array('errorMsg' => 'The company with name of ' . $name . ' already exists.');
            return response()->json($error_message);
        }

        //create new item
        $company_id = $this->saveItem();

        return response()->json(['id' => $company_id]);
    }

    public function postUpdate()
    {
        $this->saveItem();
    }

    private function saveItem()
    {
        $company = ( !empty(request()->json('id')) ) ? Company::find(request()->json('id')) : new Company();
        $company->short_name  = request()->json('short_name');
        $company->name        = request()->json('name');
        $company->address1    = request()->json('address1');
        $company->address2    = request()->json('address2');
        $company->city        = request()->json('city');
        $company->postal_code = request()->json('postal_code');
        $company->province_id = request()->json('province_id');
        $company->country_id  = request()->json('country_id');
        $company->active      = ( !empty(request()->json('active')) ) ? true : false;
        $company->save();

        return $company->id;
    }

    public function putDelete($id)
    {
        Company::find($id)->delete();
    }
}
