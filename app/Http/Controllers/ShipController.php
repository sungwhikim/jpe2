<?php
namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Client;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Carrier;
use App\Models\Product;
use App\Models\Ship;
use App\Models\ShipBin;

class ShipController extends TransactionController
{
    public function __construct()
    {
        parent::__construct();

        $this->tx_direction = 'ship';
        $this->tx_type      = 'ship';
        $this->tx_title     = 'Shipping';
        $this->tx_view      = 'pages.ship';
    }

    public function getPickList($tx_id)
    {
        return view('layouts.pick-list', ['data' => $this->getDocumentData($tx_id, true)]);
    }

    public function getShippingMemo($tx_id)
    {
        return view('layouts.shipping-memo', ['data' => $this->getDocumentData($tx_id, false)]);
    }

    public function getPickAndPack($tx_ids)
    {
        $items = ShipBin::select('product.sku', 'product.name', 'bin.aisle', 'bin.section', 'bin.tier', 'bin.position',
                                'ship_bin.quantity', 'ship_detail.uom_multiplier', 'ship_detail.uom_name', 'ship_bin.receive_date',
                                'product_variant1.value AS variant1_value',
                                'product_variant2.value AS variant2_value',
                                'product_variant3.value AS variant3_value',
                                'product_variant4.value AS variant4_value',
                                'product_variant1.name AS variant1_name',
                                'product_variant2.name AS variant2_name',
                                'product_variant3.name AS variant3_name',
                                'product_variant4.name AS variant4_name')
                        ->join('bin', 'ship_bin.bin_id', '=', 'bin.id')
                        ->join('product', 'bin.product_id', '=', 'product.id')
                        ->join('ship_detail', 'ship_bin.ship_detail_id', '=', 'ship_detail.id')
                        ->join('ship', 'ship_detail.ship_id', '=', 'ship.id')
                        ->leftJoin('product_variant1', 'ship_detail.variant1_id', '=', 'product_variant1.id')
                        ->leftJoin('product_variant2', 'ship_detail.variant2_id', '=', 'product_variant2.id')
                        ->leftJoin('product_variant3', 'ship_detail.variant3_id', '=', 'product_variant3.id')
                        ->leftJoin('product_variant4', 'ship_detail.variant4_id', '=', 'product_variant4.id')
                        ->whereIn('ship.id', explode(',', $tx_ids))
                        ->where('ship_bin.quantity', '>', 0)
                        ->whereNull('ship_bin.deleted_at')
                        ->orderBy('bin.aisle')->orderBy('bin.section')->orderBy('bin.tier')->orderBy('bin.position')
                        ->orderBy('ship_bin.receive_date')->orderBy('ship_bin.quantity')
                        ->get();

        return view('layouts.pick-and-pack', ['items' => $items]);
    }

    private function getDocumentData($tx_id, $get_bin)
    {
        //get top level transaction data
        $transaction = ship::where('id', '=', $tx_id)->first();

        //get items
        $classes = $this->transaction_model->getClasses($this->tx_type);
        $transaction->items = $this->transaction_model->getTransactionDetail($this->tx_type, $tx_id, $classes['transaction_detail']);

        //add product data and bins
        foreach( $transaction->items as $transaction_item)
        {
            //get product data
            $transaction_item->product = Product::select('product.sku', 'product.name')
                                                ->where('id', '=', $transaction_item->product_id)->first();

            //only get bins for pick list
            if( $get_bin === true )
            {
                //get bins
                $transaction_item->bins = ShipBin::join('bin', 'ship_bin.bin_id', '=', 'bin.id')
                                                 ->where('ship_detail_id', '=', $transaction_item->id)->get();
            }
        }

        //add warehouse
        $transaction->warehouse = Warehouse::where('id', '=', $transaction->warehouse_id)->first();

        //add client
        $transaction->client = Client::where('id', '=', $transaction->client_id)->first();

        //add customer
        $transaction->customer = Customer::select('customer.*', 'province.code AS province_code')
                                         ->join('province', 'customer.province_id', '=', 'province.id')
                                         ->where('customer.id', '=', $transaction->customer_id)->first();

        //get company name
        $transaction->company_short_name = Company::where('id', '=', $transaction->client->company_id)->value('short_name');

        //get carrier name
        $transaction->carrier_name = Carrier::where('id', '=', $transaction->carrier_id)->value('name');

        return $transaction;
    }
}