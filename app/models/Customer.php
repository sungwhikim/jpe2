<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';

    public function getUserCustomerList()
    {
        $data = Customer::select('customer.id', 'name')
                        ->join('customer_client_warehouse', 'customer.id', '=', 'customer_id')
                        ->where('customer_client_warehouse.client_id', '=', auth()->user()->current_client_id)
                        ->where('customer_client_warehouse.warehouse_id', '=', auth()->user()->current_warehouse_id)
                        ->where('customer.active', '=', true)->get();

        return $data;
    }
}
