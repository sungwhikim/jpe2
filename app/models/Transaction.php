<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use App\Enum\TxStatus;
use App\Models\Product;
use App\Models\ProductVariant1;
use App\Models\ProductVariant2;
use App\Models\ProductVariant3;
use App\Models\ProductVariant4;
use Exception;

class Transaction extends Model
{
    public function getTransaction($tx_type, $tx_id)
    {
        //get classes
        $classes = $this->getClasses($tx_type);
        $product_model = new Product();

        //get main transaction
        $tx = new $classes['transaction'];
        $transaction = $tx->find($tx_id);

        //add line items
        $tx_detail = new $classes['transaction_detail'];
        $table_detail = $tx_type . '_detail';

        //get detail line item
        $transaction_detail = $tx_detail::select($table_detail . '.id',
                                                 $table_detail . '.' . $tx_type . '_id',
                                                 $table_detail . '.product_id',
                                                 $table_detail . '.quantity',
                                                 $table_detail . '.uom AS selectedUom',
                                                 $table_detail . '.uom_multiplier AS selectedUomMultiplierTotal',
                                                 'variant1.value AS variant1_value',
                                                 'variant1.value AS variant2_value',
                                                 'variant1.value AS variant3_value',
                                                 'variant1.value AS variant4_value')
                                        ->leftJoin('variant1', $table_detail . '.variant1_id', '=', 'variant1.id')
                                        ->leftJoin('variant2', $table_detail . '.variant1_id', '=', 'variant2.id')
                                        ->leftJoin('variant3', $table_detail . '.variant1_id', '=', 'variant3.id')
                                        ->leftJoin('variant4', $table_detail . '.variant1_id', '=', 'variant4.id')
                                        ->where($tx_type . '_id', '=', $transaction->id)->get();

        //loop and add product object and variants and detail to each line item
        foreach( $transaction_detail as $line_item)
        {
            $product_id = $line_item->product_id;

            //update quantity
            $line_item->quantity = $line_item->quantity / $line_item->selectedUomMultiplierTotal;

            //get details and data
            $line_item['uoms'] = $product_model->getUomList($product_id, false)['uoms'];
            $line_item['product'] = Product::find($product_id);
            $line_item['variants'] = $product_model->getTxVariant($product_id);

            //get bins
            if( isset($classes['transaction_bin']) )
            {

            }
        }

        //add detail list to transaction
        $transaction['items'] = $transaction_detail;

        return $transaction;
    }

    public function newAsnTx($request)
    {
        //set classes
        $classes = $this->getClasses('asn_receive');

        return $this->saveTx($request, $classes, 'asn_receive');
    }

    public function updateAsn($request)
    {

    }

    public function newReceiveTx($request)
    {
        //set classes
        $classes = $this->getClasses('receive');

        return $this->saveTx($request, $classes, 'receive');
    }

    private function saveTx($request, $classes, $tx_type)
    {
        //data validation
        $result = $this->basicDataValidation($request);
        if( $result !== true ) { return response()->json($result); }

        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
        {
            /* update main tx table */
            $transaction = new $classes['transaction'];
            $this->setTransactionProperty($transaction, $request, TxStatus::active);
            $transaction->save();

            /* update line items */
            foreach( $request->json('items') as $item )
            {

                //set line item object
                $transaction_detail = new $classes['transaction_detail'];
                $transaction_detail->{$tx_type . '_id'} = $transaction->id;

                //set the detail
                $this->setLineItem($transaction_detail, $item);

                //save
                $transaction_detail->save();

                //save bins
                if( isset($classes['transaction_bin']) )
                {
                    $this->saveBin($item, $classes['transaction_bin'], $transaction_detail->id);
                }
            }
        }
        catch(\Exception $e)
        {
            //rollback since something failed
            DB::rollback();

            //log error so we can trace it if need be later
            Log::info(auth()->user());
            Log::error($e);

            //set error message.  Don't send verbose error if not in debug mode
            $err_msg = ( env('APP_DEBUG') === true ) ? $e->getMessage() : 'SQL error. Please try again or report the issue to the admin.';

            //send back error
            $error_message = array('errorMsg' => 'The transaction was not saved. Error: ' . $err_msg);
            return response()->json($error_message);
        }

        //if we got here, then everything worked!
        DB::commit();

        return ['tx_id' => $transaction->id];
    }

    /**
     * Check the po number for duplicate
     *
     * @return: boolean - True if duplicate does not exist
     */
    public function checkPoNumber($table, $warehouse_id, $client_id, $po_number)
    {
        $result = DB::table($table)
                    ->where('warehouse_id', '=', $warehouse_id)
                    ->where('client_id', '=', $client_id)
                    ->where('po_number', '=', $po_number)
                    ->take(1)->get();

        return ( count($result) == 0) ? true : false;
    }

    private function basicDataValidation($request)
    {
        //local variables
        $tx_type = $request->json('txType');
        $warehouse_id = $request->json('warehouse_id');
        $client_id = $request->json('client_id');
        $po_number = $request->json('po_number');

        /* this is a weird condition that may exist due to JS issues and so we will do an explicit check here */
        if( is_numeric($warehouse_id) === false || is_numeric($client_id) === false || strlen($tx_type) == 0 )
        {
            return array('errorMsg' => 'A JavaScript data error occurred. Please refresh the page and try again.');
        }

        /* check for duplicate po number */
        $result = $this->checkPoNumber($tx_type, $warehouse_id, $client_id, $po_number);
        if( $result !== true )
        {
            return array('errorMsg' => 'The PO Number is a duplicate.');
        }

        /* do and error check to fail if no line items were sent in */
        $line_items = $request->json('items');
        if( count($line_items) == 0 )
        {
            return array('errorMsg' => 'No line items were entered.');
        }

        //send back true since all error checks passed
        return true;
    }

    /**
     * @param $transaction
     * @param $request
     *
     * returns the item object with the basic properties set
     */
    private function setTransactionProperty(&$transaction, $request, $status)
    {
        $transaction->tx_status_id = $status;
        $transaction->tx_date = $request->json('tx_date');
        $transaction->po_number = $request->json('po_number');
        $transaction->carrier_id = $request->json('carrier_id', null);
        $transaction->client_id = $request->json('client_id');
        $transaction->warehouse_id = $request->json('warehouse_id');
        $transaction->tracking_number = $request->json('tracking_number', null);
        $transaction->note = $request->json('note', null);
    }

    /**
     * @param $item_object
     * @param $item
     *
     * returns the item object with the variants already set for the insert/update into the database
     */
    public function setLineItem(&$item_object, $item)
    {
        //set variables
        $product_id = $item['product']['id'];
        $quantity = $item['quantity'];
        $uom = $item['selectedUom'];

        //find multiplier
        foreach( $item['uoms'] as $uom_item )
        {
            if( $uom_item['key'] == $uom ) { $uom_multiplier = $uom_item['multiplier_total']; }
        }

        /* If we did not find a multiplier, then raise an error since we can't be sure of the quantity */
        if( isset($uom_multiplier) ===  false )
        {
            throw new Exception('Product quantity UOM multiplier not found for product: ' . $item['product_id']);
        }

        /* validate product_id and quantity */
        if( is_numeric($product_id) === false || is_numeric($quantity) === false || strpos($quantity, ',') !== false )
        {
            throw new Exception('Product and/or quantity is missing.');
        }

        /* calculate quantity to the base uom quantity as this is how the quantities are stored in the system */
        //if uom is set to first one, then it is already the base quantity
        if( $uom == 'uom1' )
        {
            $base_quantity = $quantity;
        }
        //set to correct multiplier
        else
        {
            $base_quantity = $quantity * $uom_multiplier;
        }

        //set main properties
        $item_object->product_id = $product_id;
        $item_object->quantity = $base_quantity;
        $item_object->uom = $uom;
        $item_object->uom_multiplier = $uom_multiplier;

        /* Process variant 1 */
        if( isset($item['variant1_value']) && strlen($item['variant1_value']) > 0 && $item['variants']['variant1_active'] === true )
        {
            //find or create the variant
            $variant = ProductVariant1::firstOrCreate(['product_id' => $product_id,
                                                       'name' => $item['variants']['variant1_name'],
                                                       'value' => $item['variant1_value'],
                                                       'active' => true]);

            //set the id
            $item_object->variant1_id = $variant->id;
        }

        /* Process variant 2 */
        if( isset($item['variant2_value']) && strlen($item['variant2_value']) > 0 && $item['variants']['variant2_active'] === true )
        {
            //find or create the variant
            $variant = ProductVariant2::firstOrCreate(['product_id' => $product_id,
                                                       'name' => $item['variants']['variant2_name'],
                                                       'value' => $item['variant2_value'],
                                                       'active' => true]);

            //set the id
            $item_object->variant2_id = $variant->id;
        }

        /* Process variant 3 */
        if( isset($item['variant3_value']) && strlen($item['variant3_value']) > 0 && $item['variants']['variant3_active'] === true )
        {
            //find or create the variant
            $variant = ProductVariant3::firstOrCreate(['product_id' => $product_id,
                                                       'name' => $item['variants']['variant3_name'],
                                                       'value' => $item['variant3_value'],
                                                       'active' => true]);

            //set the id
            $item_object->variant3_id = $variant->id;
        }

        /* Process variant 4 */
        if( isset($item['variant4_value']) && strlen($item['variant4_value']) > 0 && $item['variants']['variant4_active'] === true )
        {
            //find or create the variant
            $variant = ProductVariant4::firstOrCreate(['product_id' => $product_id,
                                                       'name' => $item['variants']['variant4_name'],
                                                       'value' => $item['variant4_value'],
                                                       'active' => true]);

            //set the id
            $item_object->variant4_id = $variant->id;
        }
    }

    /**
     * @param $item
     * @param $class
     * @param $transaction_detail_id
     *
     * returns the item object with the variants already set for the insert/update into the database
     */
    public function saveBin($item, $class, $transaction_detail_id)
    {

    }

    /**
     * This sets up the transaction model classes
     *
     * @param $tx_type
     *
     * @return mixed
     */
    private function getClasses($tx_type)
    {
        switch( $tx_type )
        {
            case 'asn_receive':
                $classes['transaction'] = 'App\Models\AsnReceive';
                $classes['transaction_detail'] = 'App\Models\AsnReceiveDetail';
                break;
            case 'receive';
                $classes['transaction'] = 'App\Models\Receive';
                $classes['transaction_detail'] = 'App\Models\ReceiveDetail';
                $classes['transaction_bin'] = 'App\Models\ReceiveBin';
                break;
            case 'asn_ship';
                $classes['transaction'] = 'App\Models\AsnShip';
                $classes['transaction_detail'] = 'App\Models\AsnShipDetail';
                break;
            case 'ship';
                $classes['transaction'] = 'App\Models\Ship';
                $classes['transaction_detail'] = 'App\Models\ShipDetail';
                $classes['transaction_bin'] = 'App\Models\ShipBin';
                break;
        }

        return $classes;
    }
}
