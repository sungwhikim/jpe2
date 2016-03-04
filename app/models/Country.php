<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Province;

class Country extends Model
{
    protected $table = 'country';

    public function ListWithProvinces()
    {
        //first get the list of countries
        $countries = $this->select('id', 'code', 'currency_name', 'currency_prefix', 'name')->orderBy('code')->get();

        //loop through and add the provinces
        foreach( $countries as $country )
        {
            $provinces = Province::select('id', 'code', 'name')->where('country_id', '=', $country->id)->get();
            $country->provinces = ( count($provinces) > 0 ? $provinces->toArray() : array(0 => ['id' => -1,
                                                                                                'code' => 'NONE',
                                                                                                'name' => '-- None --']) );
            debugbar()->info($country->provinces);
        }

        return $countries;
    }
}