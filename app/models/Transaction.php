<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use Exception;

use App\Enum\InventoryActivityType;
use App\Enum\TxStatus;

class Transaction extends Model
{
    public function getTransaction($tx_type, $tx_id, $convert = false)
    {
        //get classes
        $classes = $this->getClasses($tx_type);
        $product_model = new Product();
        $inventory_model = new Inventory();

        //get main transaction
        $user_id = auth()->user()->id;
        $tx = new $classes['transaction'];
        $transaction = $tx::select($tx_type . '.*', 'warehouse.name AS warehouse_name', 'client.short_name AS client_short_name')
                          ->join('warehouse', $tx_type . '.warehouse_id', '=', 'warehouse.id')
                          ->join('client', $tx_type . '.client_id', '=', 'client.id')
                          ->join('user_warehouse', $tx_type . '.warehouse_id', '=', 'user_warehouse.warehouse_id')
                          ->join('user_client', $tx_type . '.client_id', '=', 'user_client.client_id')
                          ->where('user_warehouse.user_id', '=', $user_id)
                          ->where('user_client.user_id', '=', $user_id)
                          ->where($tx_type . '.id', '=', $tx_id)->take(1)->get()[0];

        //verify that the transaction was found accounting for the user's warehouse and client permission list
        if( count($transaction) == 0 )
        { throw new Exception('Invalid transaction ID or you do not have permissions to view this transaction'); }

        //if we are converting it, we need to double check it isn't being done twice.  This should not happen as
        //the button should not exist, but we need to make sure some weird js state allows it.
        if( $tx->tx_status_id == TxStatus::converted )
        { throw new Exception('This transaction was already converted.'); }

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
                                                 $table_detail . '.variant1_id',
                                                 $table_detail . '.variant2_id',
                                                 $table_detail . '.variant3_id',
                                                 $table_detail . '.variant4_id',
                                                 'product_variant1.value AS variant1_value',
                                                 'product_variant2.value AS variant2_value',
                                                 'product_variant3.value AS variant3_value',
                                                 'product_variant4.value AS variant4_value')
                                        ->leftJoin('product_variant1', $table_detail . '.variant1_id', '=', 'product_variant1.id')
                                        ->leftJoin('product_variant2', $table_detail . '.variant2_id', '=', 'product_variant2.id')
                                        ->leftJoin('product_variant3', $table_detail . '.variant3_id', '=', 'product_variant3.id')
                                        ->leftJoin('product_variant4', $table_detail . '.variant4_id', '=', 'product_variant4.id')
                                        ->where($tx_type . '_id', '=', $transaction->id)
                                        ->orderBy($table_detail . '.id')->get();

        //loop and add product object and variants and detail to each line item
        foreach( $transaction_detail as $line_item)
        {
            $product_id = $line_item->product_id;
            $uom_multiplier = $line_item->selectedUomMultiplierTotal;

            //update quantity
            $line_item->quantity = $line_item->quantity / $uom_multiplier;

            //get details and data
            $line_item['uoms'] = $product_model->getUomList($product_id, false)['uoms'];
            $line_item['product'] = Product::find($product_id);
            $line_item['variants'] = $product_model->getTxVariant($product_id);

            //get bins for receiving transactions
            if( $tx_type == 'receive' )
            {
                $line_item['bins'] = $this->getTransactionBin($tx_type, $uom_multiplier, $product_id, $line_item->id);
            }

            //get bin list if the transaction is being converted because there are not bins set yet
            if( $convert === true) { $line_item['bins'] = $product_model->getInventoryBin($product_id); }

            //for shipping transactions, add the inventory total and uom multiplier
            if( $tx_type == 'asn_ship' || $tx_type == 'ship' )
            {
                $line_item['inventoryTotal'] = $inventory_model->getVariantInventoryTotal($line_item->product_id, $line_item->variant1_id,
                                                    $line_item->variant2_id, $line_item->variant3_id, $line_item->variant4_id);
                $line_item['selectedUomMultiplierTotal'] = $uom_multiplier;
            }
        }

        //add detail list to transaction
        $transaction['items'] = $transaction_detail;

        return $transaction;
    }

    public function newTransaction($request, $tx_type)
    {
        //data validation
        $result = $this->basicDataValidation($request);
        if( $result !== true ) { return response()->json($result); }

        //get classes
        $classes = $this->getClasses($tx_type);

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
                    /* Receiving and Shipping are handled differently.  Receiving can be just added, but shipping needs
                       to calculate FIFO/LIFO, etc */
                    //receiving
                    if( $tx_type == 'receive' )
                    {
                        $this->addTxBinReceive($transaction_detail, $item['bins'], $classes['transaction_bin'],
                            $tx_type, $request['tx_date']);
                    }

                    //shipping
                    if( $tx_type == 'ship' )
                    {

                    }
                }
            }

            //if it was a converted transaction, update the status
            if( $request->json('txSetting')['convert'] === true )
            {
                DB::table('asn_' . $tx_type)
                  ->where('id', '=', $request->json('id'))
                  ->update(['tx_status_id' => TxStatus::converted]);
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

    public function updateTransaction($request, $tx_type, $tx_id)
    {
        //data validation
        $result = $this->basicDataValidation($request, $tx_id);
        if( $result !== true ) { return response()->json($result); }

        //get classes
        $classes = $this->getClasses($tx_type);

        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
        {
            //update main tx table
            $transaction = new $classes['transaction'];
            $transaction = $transaction->find($tx_id);

            /* Don't allow update if the status is not active */
            if( $transaction->tx_status_id != TxStatus::active )
            {
                throw new Exception('Only active transactions can be edited.');
            }

            //set properties and update
            $this->setTransactionProperty($transaction, $request, TxStatus::active);
            $transaction->save();

            /* update line items */
            foreach( $request->json('items') as $item )
            {
                //set status of item of new or edit
                $update_item = ( empty($item['id']) === false ) ? true : false;

                //create line item object
                $transaction_detail = new $classes['transaction_detail'];

                //process differently whether it is an existing item or a new item
                //existing item
                if( $update_item === true )
                {
                    $transaction_detail = $transaction_detail->find($item['id']);
                }

                //new item

                //set parent id
                $transaction_detail->{$tx_type . '_id'} = $transaction->id;

                //set the detail
                $this->setLineItem($transaction_detail, $item);

                //save
                $transaction_detail->save();

                //save bins
                if( isset($classes['transaction_bin']) )
                {
                    //receiving
                    if( $tx_type == 'receive' )
                    {
                        $this->addTxBinReceive($transaction_detail, $item['bins'], $classes['transaction_bin'],
                            $tx_type, $request['tx_date']);
                    }

                    //shipping
                    if( $tx_type == 'ship' )
                    {

                    }
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
    }

    public function voidTransaction($tx_type, $tx_id)
    {

    }

    /**
     * Check the po number for duplicate
     *
     * @return: boolean - True if duplicate does not exist
     */
    public function checkPoNumber($table, $warehouse_id, $client_id, $po_number, $tx_id = null)
    {
        $result = DB::table($table)
                    ->where('warehouse_id', '=', $warehouse_id)
                    ->where('client_id', '=', $client_id)
                    ->where('po_number', '=', $po_number)
                    ->take(1);

        //we need to account for when this is an update and the user changes the po number
        if( is_null($tx_id) === false ) { $result->where('id', '!=', $tx_id); }

        return ( count($result->get()) == 0) ? true : false;
    }

    private function basicDataValidation($request, $tx_id = null)
    {
        //local variables
        $tx_type = $request->json('txSetting')['type'];
        $warehouse_id = $request->json('warehouse_id');
        $client_id = $request->json('client_id');
        $po_number = $request->json('po_number');

        /* this is a weird condition that may exist due to JS issues and so we will do an explicit check here */
        if( is_numeric($warehouse_id) === false || is_numeric($client_id) === false || strlen($tx_type) == 0 )
        {
            return array('errorMsg' => 'A JavaScript data error occurred. Please refresh the page and try again.');
        }

        /* check for duplicate po number */
        if( $this->checkPoNumber($tx_type, $warehouse_id, $client_id, $po_number, $tx_id) !== true )
        {
            return array('errorMsg' => 'The PO Number is a duplicate.');
        }

        /* do and error check to fail if no line items were sent in */
        if( count($request->json('items')) == 0 )
        {
            return array('errorMsg' => 'No line items were entered.');
        }

        /* check for customer id in shipping transactions */
        if( ($tx_type == 'asn_ship' || $tx_type == 'ship') && is_numeric($request->json('customer_id')) === false )
        {
            return array('errorMsg' => 'Please select a customer.');
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

        //add customer id for shipping transactions
        if( !empty($request->json('customer_id')) ) { $transaction->customer_id = $request->json('customer_id'); }
    }

    /**
     * @param $item_object
     * @param $item
     *
     * @return - the item object with the variants already set for the insert/update into the database
     */
    public function setLineItem(&$item_object, $item)
    {
        //set variables
        $product_id = $item['product']['id'];
        $quantity = $item['quantity'];
        $uom = $item['selectedUom'];

        /* validate product_id and quantity */
        if( is_numeric($product_id) === false || is_numeric($quantity) === false || strpos($quantity, ',') !== false )
        {
            throw new Exception('Product and/or quantity is missing.');
        }

        //get uom multiplier
        $uom_multiplier = $item['selectedUomMultiplierTotal'];

        /* If we did not find a multiplier, then raise an error since we can't be sure of the quantity */
        if( is_numeric($uom_multiplier) ===  false )
        {
            throw new Exception('Product quantity UOM multiplier not found for product: ' . $item['product_id']);
        }

        /* calculate quantity to the base uom quantity as this is how the quantities are stored in the system */
        $base_quantity = $quantity * $uom_multiplier;

        //set main properties
        $item_object->product_id = $product_id;
        $item_object->quantity = $base_quantity;
        $item_object->uom = $uom;
        $item_object->uom_multiplier = $uom_multiplier;

        /* Process variant 1 */
        if( isset($item['variant1_value']) && strlen($item['variant1_value']) > 0
                && $item['variants']['variant1_active'] === true && $item['variant1_value'] != '[none]' )
        {
            //find or create the variant
            $variant = ProductVariant1::firstOrCreate(['product_id' => $product_id,
                                                       'name' => $item['variants']['variant1_name'],
                                                       'value' => $item['variant1_value'],
                                                       'active' => true]);

            //set the id
            $item_object->variant1_id = $variant->id;
        }
        //set it to null so the property is set to be used later
        else { $item_object->variant1_id = null; }

        /* Process variant 2 */
        if( isset($item['variant2_value']) && strlen($item['variant2_value']) > 0
            && $item['variants']['variant2_active'] === true && $item['variant2_value'] != '[none]' )
        {
            //find or create the variant
            $variant = ProductVariant2::firstOrCreate(['product_id' => $product_id,
                                                       'name' => $item['variants']['variant2_name'],
                                                       'value' => $item['variant2_value'],
                                                       'active' => true]);

            //set the id
            $item_object->variant2_id = $variant->id;
        }
        //set it to null so the property is set to be used later
        else { $item_object->variant2_id = null; }

        /* Process variant 3 */
        if( isset($item['variant3_value']) && strlen($item['variant3_value']) > 0
            && $item['variants']['variant3_active'] === true && $item['variant3_value'] != '[none]' )
        {
            //find or create the variant
            $variant = ProductVariant3::firstOrCreate(['product_id' => $product_id,
                                                       'name' => $item['variants']['variant3_name'],
                                                       'value' => $item['variant3_value'],
                                                       'active' => true]);

            //set the id
            $item_object->variant3_id = $variant->id;
        }
        //set it to null so the property is set to be used later
        else { $item_object->variant3_id = null; }

        /* Process variant 4 */
        if( isset($item['variant4_value']) && strlen($item['variant4_value']) > 0
            && $item['variants']['variant4_active'] === true && $item['variant4_value'] != '[none]' )
        {
            //find or create the variant
            $variant = ProductVariant4::firstOrCreate(['product_id' => $product_id,
                                                       'name' => $item['variants']['variant4_name'],
                                                       'value' => $item['variant4_value'],
                                                       'active' => true]);

            //set the id
            $item_object->variant4_id = $variant->id;
        }
        //set it to null so the property is set to be used later
        else { $item_object->variant4_id = null; }
    }

    /**
     * This is for new receiving transactions to add bins
     *
     * @param $transaction_item
     * @param $bins
     * @param $tx_bin
     * @param $tx_type
     * @param $tx_date
     *
     * @throws Exception
     */
    public function addTxBinReceive($transaction_item, $bins, $tx_bin, $tx_type, $tx_date)
    {
        //set variables
        $total = $transaction_item->quantity;
        $tx_detail_id = $transaction_item->id;
        $subtotal = 0;
        $inventory_model = new Inventory();

        //loop through the bins
        foreach( $bins as $bin )
        {
            //create bin if need be
            $bin_id = $bin['id'];
            if( $bin_id === null )
            {
                $bin_object = new Bin;
                $bin_object->product_id = $transaction_item->product_id;
                $bin_object->aisle = $bin['aisle'];
                $bin_object->section = $bin['section'];
                $bin_object->tier = $bin['tier'];
                $bin_object->position = $bin['position'];
                $bin_object->default = false;
                $bin_object->active = true;
                $bin_object->save();

                $bin_id = $bin_object->id;
            }

            //convert to base quantity
            $bin_quantity = $bin['quantity'] * $transaction_item->uom_multiplier;

            //increment subtotal
            $subtotal += $bin_quantity;

            //check to make sure we are not somehow adding too many items
            if( $total - $subtotal < 0 ) { throw new Exception('Too many quantities assigned in the bins.'); }

            //update bin only if quantity was entered
            if( $bin_quantity > 0 )
            {
                //add to the transaction bin table
                $tx_bin_id = $this->addTxBin($tx_bin, $tx_type, $tx_detail_id, $bin_id, $bin_quantity);

                //update the inventory
                $inventory_model->addInventoryItem($bin_quantity, $bin_id, $tx_date, $transaction_item->variant1_id,
                    $transaction_item->variant2_id, $transaction_item->variant3_id, $transaction_item->variant4_id,
                    InventoryActivityType::RECEIVE, $tx_bin, $tx_bin_id);
            }

            //find the default bin to be used later so we don't have to run the loop again
            if( $bin['default'] === true ) { $default_bin = $bin; }
        }

        //if there are not enough items, add the rest to the default bin
        //set quantity
        $remaining_bin_quantity = $total - $subtotal;
        if( $remaining_bin_quantity > 0 )
        {
            //if there is no default bin, we can't complete the transaction
            if( isset($default_bin) === false ) { throw new Exception('Default bin was not found for product id: ' . $transaction_item->product_id); }

            //add tx bin
            $tx_bin_id = $this->addTxBin($tx_bin, $tx_type, $tx_detail_id, $default_bin['id'], $remaining_bin_quantity);

            //update the inventory
            $inventory_model->addInventoryItem($remaining_bin_quantity, $default_bin['id'], $tx_date, $transaction_item->variant1_id,
                $transaction_item->variant2_id, $transaction_item->variant3_id, $transaction_item->variant4_id,
                InventoryActivityType::RECEIVE, $tx_bin, $tx_bin_id);
        }
    }

    /**
     * Add a transaction bin item
     */
    private function addTxBin($tx_bin, $tx_type, $tx_detail_id, $bin_id, $quantity)
    {
        $bin_object = new $tx_bin;
        $bin_object->{$tx_type . '_detail_id'} = $tx_detail_id;
        $bin_object->bin_id = $bin_id;
        $bin_object->quantity = $quantity;
        $bin_object->save();

        return $bin_object->id;
    }

    /**
     * This returns the transaction bin data
     *
     * @return mixed
     */
    public function getTransactionBin($tx_type, $uom_multiplier, $product_id, $tx_detail_id)
    {
        $tx_bin_table = $tx_type . '_bin';
;
        /* We are using raw sql here due to limitations of eloquent and join statements with passing in variables
           into multiple join on statements */
        $sql = "SELECT bin.id, bin.aisle, bin.section, bin.tier, bin.position, bin.default,
                       SUM(inventory.quantity) AS inventory,
                       " . $tx_bin_table . ".quantity /" . $uom_multiplier . " AS quantity
                FROM bin
                    LEFT OUTER JOIN inventory
                        ON inventory.bin_id = bin.id
                    LEFT OUTER JOIN " . $tx_bin_table . "
                        ON " . $tx_bin_table . ".bin_id = bin.id
                            AND " . $tx_bin_table . "." . $tx_type . "_detail_id = ?
                WHERE bin.product_id = ?
                GROUP BY bin.id, bin.aisle, bin.section, bin.tier, bin.position, bin.default, " . $tx_bin_table . ".quantity
                ORDER BY bin.aisle ASC, bin.section ASC, bin.tier ASC, bin.position ASC";


        return DB::select($sql, [$tx_detail_id, $product_id]);
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
