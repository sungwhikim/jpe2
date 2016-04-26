<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use App\Enum\TxStatus;
use App\Models\ProductVariant1;
use App\Models\ProductVariant2;
use App\Models\ProductVariant3;
use App\Models\ProductVariant4;

class Transaction extends Model
{

    public function createNewAsn($request)
    {
        return $this->saveAsn($request);
    }

    public function updateAsn($request)
    {

    }

    private function saveAsn($request)
    {
        debugbar()->info($request);
        /* DO DATA VALIDATION */
        /* save the data */
        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
        {
            /* update main tx table */
            $transaction = new AsnReceive();
            $transaction->tx_status_id = TxStatus::active;
            $transaction->tx_date = $request->json('tx_date');
            $transaction->po_number = $request->json('po_number');
            $transaction->carrier_id = $request->json('carrier_id', null);
            $transaction->client_id = auth()->user()->current_client_id;
            $transaction->warehouse_id = auth()->user()->current_warehouse_id;
            $transaction->tracking_number = $request->json('tracking_number', null);
            $transaction->note = $request->json('note', null);
            $transaction->save();

            //do and error check to fail if no line items were sent in
            $line_items = $request->json('items');
            if( count($line_items) == 0 )
            {
                throw new Exception('No line items were entered.');
            }

            /* update line items */
            foreach( $line_items as $item )
            {
                //set line item object
                $transaction_detail = new AsnReceiveDetail();
                $transaction_detail->asn_receive_id = $transaction->id;

                //set the detail
                $this->setLineItemBasic($transaction_detail, $item);

                //save
                $transaction_detail->save();
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

        return ['id' => $transaction->id];
    }

    /**
     * @param $item_object
     * @param $item
     *
     * returns the item object with the variants already set for the insert/update into the database
     */
    public function setLineItemBasic(&$item_object, $item)
    {
        //set variables
        $product_id = $item['product_id'];
        $quantity = $item{'quantity'};
        $uom = $item['selectedUom'];

        //find multiplier
        foreach( $item['uoms'] as $uom_item )
        {
            if( $uom_item['key'] == $uom ) { $uom_multiplier = $uom_item['multiplier_total']; }
        }

        //validate product_id and quantity
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
                                                       'value' => $item['variant1_value']]);

            //set the id
            $item_object->variant1_id = $variant->id;
        }
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

}
