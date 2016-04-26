<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    protected $table = 'carrier';

    public function getUserCarrierList($type)
    {
        //use query function so we can change the query based on the type
        $query = Carrier::query();

        //base query
        $query->select('carrier.id', 'carrier.name')
                ->join('carrier_client_warehouse', 'carrier.id', '=', 'carrier_client_warehouse.carrier_id')
                ->where('carrier_client_warehouse.warehouse_id', '=', auth()->user()->current_warehouse_id)
                ->where('carrier_client_warehouse.client_id', '=', auth()->user()->current_client_id)
                ->where('carrier.active', '=', true);

        //type of receiving carriers
        if( $type == 'receive' )
        {
            $query->where('carrier_client_warehouse.receive', '=', true);
        }

        //type of shipping carriers
        else
        {
            $query->where('carrier_client_warehouse.ship', '=', true);
        }

        //return data
        return $query->get();
    }
}