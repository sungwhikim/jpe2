<?php

namespace App\Models;

use App\Enum\TransactionEmailActivity;
use App\Enum\TransactionLogActivityType;
use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;
use Log;
use Exception;
use Mail;

use App\Enum\InventoryActivityType;
use App\Enum\TxStatus;

class Transaction extends Model
{
    public function getTransaction($tx_type, $tx_id, $convert = false)
    {
        //for converted transactions, we need to change the transaction type
        if( $convert === true )
        {
            $tx_type = $this->getConvertTxType($tx_type);
        }

        //get classes
        $classes = $this->getClasses($tx_type);

        //get main transaction
        $user_id = auth()->user()->id;
        $tx = new $classes['transaction'];
        $transaction = $tx::select($tx_type . '.*', 'warehouse.name AS warehouse_name', 'client.short_name AS client_short_name',
                                   'tx_status.name AS tx_status_name', 'user.name AS user_name', 'carrier.name AS carrier_name')
                          ->join('warehouse', $tx_type . '.warehouse_id', '=', 'warehouse.id')
                          ->join('client', $tx_type . '.client_id', '=', 'client.id')
                          ->join('user_warehouse', $tx_type . '.warehouse_id', '=', 'user_warehouse.warehouse_id')
                          ->join('user_client', $tx_type . '.client_id', '=', 'user_client.client_id')
                          ->join('tx_status', $tx_type . '.tx_status_id', '=', 'tx_status.id')
                          ->join('user', $tx_type . '.user_id', '=', 'user.id')
                          ->leftJoin('carrier', $tx_type . '.carrier_id', '=', 'carrier.id')
                          ->where('user_warehouse.user_id', '=', $user_id)
                          ->where('user_client.user_id', '=', $user_id)
                          ->where($tx_type . '.id', '=', $tx_id)->take(1)->get()[0];

        //verify that the transaction was found accounting for the user's warehouse and client permission list
        if( count($transaction) == 0 )
        { throw new Exception('Invalid transaction ID or you do not have permission to view this transaction'); }

        //if we are converting it, we need to double check it isn't being done twice.  This should not happen as
        //the button should not exist, but we need to make sure some weird js state allows it.
        if( $tx->tx_status_id == TxStatus::converted )
        { throw new Exception('This transaction was already converted.'); }

        //add detail list to transaction
        $transaction['items'] = $this->getTransactionDetailComplete($tx_type, $tx_id, $classes, $convert);

        return $transaction;
    }

    public function getTransactionDetail($tx_type, $tx_id, $tx_detail_class)
    {
        //add line items
        $tx_detail = new $tx_detail_class;
        $table_detail = $tx_type . '_detail';

        //get detail line item
        return $tx_detail::select($table_detail . '.id',
                                  $table_detail . '.' . $tx_type . '_id',
                                  $table_detail . '.product_id',
                                  $table_detail . '.quantity',
                                  $table_detail . '.uom AS selectedUom',
                                  $table_detail . '.uom_multiplier AS selectedUomMultiplierTotal',
                                  $table_detail . '.uom_name AS selectedUomName',
                                  $table_detail . '.variant1_id',
                                  $table_detail . '.variant2_id',
                                  $table_detail . '.variant3_id',
                                  $table_detail . '.variant4_id',
                                  'product_variant1.value AS variant1_value',
                                  'product_variant2.value AS variant2_value',
                                  'product_variant3.value AS variant3_value',
                                  'product_variant4.value AS variant4_value',
                                  'product_variant1.name AS variant1_name',
                                  'product_variant2.name AS variant2_name',
                                  'product_variant3.name AS variant3_name',
                                  'product_variant4.name AS variant4_name')
                        ->leftJoin('product_variant1', $table_detail . '.variant1_id', '=', 'product_variant1.id')
                        ->leftJoin('product_variant2', $table_detail . '.variant2_id', '=', 'product_variant2.id')
                        ->leftJoin('product_variant3', $table_detail . '.variant3_id', '=', 'product_variant3.id')
                        ->leftJoin('product_variant4', $table_detail . '.variant4_id', '=', 'product_variant4.id')
                        ->where($tx_type . '_id', '=', $tx_id)
                        ->orderBy($table_detail . '.id')->get();
    }

    public function getTransactionDetailComplete($tx_type, $tx_id, $classes, $convert)
    {
        //set classes
        $product_model = new Product();
        $inventory_model = new Inventory();

        //get transaction detail
        $transaction_detail = $this->getTransactionDetail($tx_type, $tx_id, $classes['transaction_detail']);

        //loop and add product object and variants and detail to each line item
        foreach( $transaction_detail as $line_item)
        {
            $product_id = $line_item->product_id;
            $uom_multiplier = $line_item->selectedUomMultiplierTotal;

            //update quantity
            $line_item->quantity = $line_item->quantity / $uom_multiplier;

            //get details and data
            $line_item['uoms'] = $product_model->getUomList($product_id, false)['uoms'];
            $line_item['product'] = Product::findOrFail($product_id);
            $line_item['variants'] = $product_model->getTxVariant($product_id);

            //get bins for receiving transactions
            if( $tx_type == 'receive' )
            {
                $line_item['bins'] = $this->getTransactionBin($tx_type, $uom_multiplier, $product_id, $line_item->id);
            }

            //get bins for shipping transactions
            if( $tx_type == 'ship' )
            {
                $line_item['bins'] = $this->getTransactionBinShip($uom_multiplier, $line_item);
            }

            //get bin list if the transaction is being converted because there are no bins set yet
            if( $convert === true) { $line_item['bins'] = $product_model->getInventoryBin($product_id); }

            //for shipping transactions, add the inventory total and uom multiplier
            if( $tx_type == 'csr' || $tx_type == 'ship' )
            {
                //get current inventory total
                $line_item['inventoryTotal'] = $inventory_model->getVariantInventoryTotal($line_item->product_id, $line_item->variant1_id,
                    $line_item->variant2_id, $line_item->variant3_id, $line_item->variant4_id);

                //if it is a shipping transaction, add back the quantity to correct the base inventory level.
                //the front end dynamically calculates the inventory total so we need to add it back to zero out the difference.
                if( $tx_type == 'ship') { $line_item['inventoryTotal'] += $line_item->quantity * $uom_multiplier; }

                //set UOM multiplier
                $line_item['selectedUomMultiplierTotal'] = $uom_multiplier;
            }
        }

        return $transaction_detail;
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

            /* add line items */
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
                if( isset($classes['transaction_bin']) ) {
                    /* Receiving and Shipping are handled differently.  Receiving can be just added, but shipping needs
                       to calculate FIFO/LIFO, etc */
                    //receiving
                    if ( $tx_type == 'receive' ) {
                        $this->addTxBinReceive($transaction_detail, $item['bins'], $classes['transaction_bin'],
                            $tx_type, $transaction->tx_date);
                    }

                    //shipping
                    if ( $tx_type == 'ship' ) {
                        $this->addShippingBin($transaction, $transaction_detail, $classes);
                    }
                }
            }

            //if it was a converted transaction, update the status and log and add corresponding transaction id
            if( $request->json('txSetting')['convert'] === true )
            {
                //get converted tx type
                $tx_type_converted = $this->getConvertTxType($tx_type);

                //update converted table
                DB::table($tx_type_converted)
                  ->where('id', '=', $request->json('id'))
                  ->update(['tx_status_id' => TxStatus::converted,
                            'converted_tx_id' => $transaction->id]);

                //log the converted transaction
                $this->addTransactionLog($request->json('id'), $tx_type_converted, TransactionLogActivityType::convert, $request->json()->all());
            }

            //log activity
            $this->addTransactionLog($transaction->id, $tx_type, TransactionLogActivityType::create, $request->json()->all());
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

        //send confirmation email
        $this->sendConfirmationEmail($tx_type, $transaction, TransactionEmailActivity::created, $classes);

        //set return array
        $return_data = ['tx_id' => $transaction->id];

        //send back items data with bins if it was a shipping or receiving transaction.  This is because of the bin updates.
        if( $tx_type == 'ship' || $tx_type == 'receive' ) { $return_data['items'] = $this->getTransactionDetailComplete($tx_type, $transaction->id, $classes, false); }

        return $return_data;
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
            //get main tx table
            $tx_model = new $classes['transaction'];
            $transaction = $tx_model->findOrFail($tx_id);

            /* Don't allow update if the status is not active */
            if( $transaction->tx_status_id != TxStatus::active )
            {
                throw new Exception('Only active transactions can be edited.');
            }

            //set properties and update
            $this->setTransactionProperty($transaction, $request, TxStatus::active);
            $transaction->save();

            //initialize array of transaction id's used
            $tx_detail_ids = [];

            //initialize model separate from what we will use in the loop to prevent collisions
            $tx_detail_model = new $classes['transaction_detail'];

            //process line items
            foreach( $request->json('items') as $item )
            {
                //set line item object
                $transaction_detail = new $classes['transaction_detail'];

                //process new item first
                if( !isset($item['id']) || $item['id'] === null )
                {
                    $transaction_detail->{$tx_type . '_id'} = $transaction->id;
                }

                //get existing item
                else
                {
                    $transaction_detail = $transaction_detail->findOrFail($item['id']);
                }

                //set the detail
                $this->setLineItem($transaction_detail, $item);

                //save
                $transaction_detail->save();

                //add id to array so we can use it to delete all the unused line items later
                array_push($tx_detail_ids, $transaction_detail->id);

                //update bins and inventory
                if( isset($classes['transaction_bin']) )
                {
                    //receiving
                    if ( $tx_type == 'receive' )
                    {
                        $this->updateTxBinReceive($transaction_detail, $item['bins'], $classes['transaction_bin'],
                            $tx_type, $transaction->tx_date);
                    }

                    /* for shipping, back out old bin data first, then either add back what the user has selected,
                       or, recalculate FIFO/LIFO */
                    //shipping
                    if ( $tx_type == 'ship' )
                    {
                        //delete the existing bins
                        $this->deleteTxBin($tx_type, [$transaction_detail], $classes);

                        //update the transaction bin
                        $this->updateTxBinShip($item, $transaction, $transaction_detail, $classes);
                    }
                }
            }

            /* delete line items and back out the bins and inventory */
            //get the items to delete
            $tx_detail_items = $tx_detail_model::where($tx_type . '_id', '=', $transaction->id)
                                                ->whereNotIn('id', $tx_detail_ids)->get();

            //delete all the bins
            $this->deleteTxBin($tx_type, $tx_detail_items, $classes);

            //delete the transaction detail
            $tx_detail_model::where($tx_type . '_id', '=', $transaction->id)
                            ->whereNotIn('id', $tx_detail_ids)->delete();

            //log activity
            $this->addTransactionLog($tx_id, $tx_type, TransactionLogActivityType::update, $request->json()->all());
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

        //send confirmation email
        $this->sendConfirmationEmail($tx_type, $transaction, TransactionEmailActivity::updated, $classes);

        //send back items data with bins if it was a shipping transaction.  This is because of the bin updates.
        if( $tx_type == 'ship' || $tx_type == 'receive' ) { return ['items' => $this->getTransactionDetailComplete($tx_type, $tx_id, $classes, false)]; }
    }

    /**
     * Voids a transaction and backs out the inventory committed
     *
     * @param $tx_type
     * @param $tx_id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function voidTransaction($tx_type, $tx_id)
    {
        //get classes
        $classes = $this->getClasses($tx_type);

        //get transaction
        $tx =  new $classes['transaction'];
        $transaction = $tx::findOrFail($tx_id);

        //make sure it is active.  We cannot void transactions in any other status
        if( $transaction->tx_status_id != TxStatus::active ) { throw new Exception('Only an active transaction can be voided.'); }

        //wrap the entire process in a transaction
        DB::beginTransaction();
        try
        {
            //update the status
            $transaction->tx_status_id = TxStatus::voided;
            $transaction->save();

            //if has transaction bin, reverse the inventory quantities
            if( isset($classes['transaction_bin']) )
            {
                //set table names
                $table_tx = $tx_type;
                $table_tx_detail = $tx_type . '_detail';
                $table_tx_bin = $tx_type . '_bin';

                //get the list of all the bin transactions
                $tx_bin = new $classes['transaction_bin'];
                $transaction_bins = $tx_bin::select($table_tx_bin . '.id', $table_tx_bin . '.bin_id',$table_tx_bin . '.quantity', $table_tx_bin . '.receive_date',
                                                    $table_tx_detail . '.variant1_id', $table_tx_detail . '.variant2_id',
                                                    $table_tx_detail . '.variant3_id', $table_tx_detail . '.variant4_id')
                                           ->join($table_tx_detail, $table_tx . '_detail_id', '=', $table_tx_detail . '.id')
                                           ->join($table_tx, $table_tx . '_id', '=', $table_tx . '.id')
                                           ->where($table_tx . '.id', '=', $tx_id)
                                           ->where($table_tx_detail . '.deleted_at', '=', null)
                                           ->groupBy($table_tx_bin . '.id', $table_tx_bin . '.bin_id',$table_tx_bin . '.quantity', $table_tx_bin . '.receive_date',
                                                    $table_tx_detail . '.variant1_id', $table_tx_detail . '.variant2_id',
                                                    $table_tx_detail . '.variant3_id', $table_tx_detail . '.variant4_id')->get();

                //get inventory model
                $inventory_model = new Inventory();

                //set activity type
                $activity_type = ( $tx_type == 'receive' ) ? InventoryActivityType::RECEIVE : InventoryActivityType::SHIP;

                foreach( $transaction_bins as $transaction_bin )
                {
                    //set quantity. Receiving needs to subtract while shipping needs to add
                    $quantity = ( $tx_type == 'receive' ) ? $transaction_bin->quantity * -1 : $transaction_bin->quantity;

                    //update the inventory
                    $inventory_model->addInventoryItem($quantity, $transaction_bin->bin_id, $transaction_bin->receive_date,
                        $transaction_bin->variant1_id, $transaction_bin->variant2_id, $transaction_bin->variant3_id,
                        $transaction_bin->variant4_id, $activity_type, $tx_type . '_bin', $transaction_bin->id);
                }
            }

            //log activity
            $this->addTransactionLog($tx_id, $tx_type, TransactionLogActivityType::void, null);
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
            $error_message = array('errorMsg' => 'The transaction was not voided. Error: ' . $err_msg);
            return response()->json($error_message);
        }

        //if we got here, then everything worked!
        DB::commit();

        //send confirmation email
        $this->sendConfirmationEmail($tx_type, $transaction, TransactionEmailActivity::voided, $classes);
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
                    ->where('tx_status_id', '!=', TxStatus::voided) // allow duplicates for voided transactions
                    ->take(1);

        //we need to account for when this is an update and the user changes the po number
        if( is_null($tx_id) === false && $tx_id != 'null' ) { $result->where('id', '!=', $tx_id); }

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
        if( ($tx_type == 'csr' || $tx_type == 'ship') && is_numeric($request->json('customer_id')) === false )
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
        $transaction->tx_date = $request->json('tx_date_clean'); //we use the tx_date_clean because of the way js date is parsed
        $transaction->po_number = $request->json('po_number');
        $transaction->carrier_id = $request->json('carrier_id', null);
        $transaction->client_id = $request->json('client_id');
        $transaction->warehouse_id = $request->json('warehouse_id');
        $transaction->tracking_number = $request->json('tracking_number', null);
        $transaction->note = $request->json('note', null);

        //add user id to track transactions
        $transaction->user_id = auth()->user()->id;

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
        $no_variant_set = '-- none --';

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
        $item_object->uom_name = $item['selectedUomName'];

        /* Process variant 1 */
        if( isset($item['variant1_value']) && strlen($item['variant1_value']) > 0
                && $item['variants']['variant1_active'] === true && $item['variant1_value'] != $no_variant_set )
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
            && $item['variants']['variant2_active'] === true && $item['variant2_value'] != $no_variant_set )
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
            && $item['variants']['variant3_active'] === true && $item['variant3_value'] != $no_variant_set )
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
            && $item['variants']['variant4_active'] === true && $item['variant4_value'] != $no_variant_set )
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
        /* don't add bins if quantity is zero.  They can set a zero quantity if item was supposed to be sent, but not received */
        if( $transaction_item->quantity == 0 ) { return; }

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
                $tx_bin_id = $this->addTxBin($tx_bin, $tx_type, $tx_detail_id, $bin_id, $bin_quantity, $tx_date);

                //update the inventory
                $inventory_model->addInventoryItem($bin_quantity, $bin_id, $tx_date, $transaction_item->variant1_id,
                    $transaction_item->variant2_id, $transaction_item->variant3_id, $transaction_item->variant4_id,
                    InventoryActivityType::RECEIVE, $tx_type . '_bin', $tx_bin_id);
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
            $tx_bin_id = $this->addTxBin($tx_bin, $tx_type, $tx_detail_id, $default_bin['id'], $remaining_bin_quantity, $tx_date);

            //update the inventory
            $inventory_model->addInventoryItem($remaining_bin_quantity, $default_bin['id'], $tx_date, $transaction_item->variant1_id,
                $transaction_item->variant2_id, $transaction_item->variant3_id, $transaction_item->variant4_id,
                InventoryActivityType::RECEIVE, $tx_type . '_bin', $tx_bin_id);
        }
    }

    /**
     * Updates existing receiving transaction bins and inventory
     *
     * @param $transaction_item
     * @param $bins
     * @param $tx_bin
     * @param $tx_type
     * @param $tx_date
     *
     * @throws Exception
     */
    public function updateTxBinReceive($transaction_item, $bins, $tx_bin, $tx_type, $tx_date)
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

            //if this transaction bin exists, then update it if the quantity is different
            if( isset($bin['tx_bin_id']) && $bin['tx_bin_id'] !== null )
            {
                //get tx bin
                $transaction_bin_model = new $tx_bin;
                $transaction_bin = $transaction_bin_model::findOrFail($bin['tx_bin_id']);

                //set quantity
                $bin_quantity_difference =  $bin_quantity - $transaction_bin->quantity;

                /* update only if the quantity has changed */
                if( $bin_quantity_difference != 0 )
                {
                    //update the bin quantity
                    $transaction_bin->quantity = $bin_quantity;
                    $transaction_bin->save();

                    //update inventory
                    $inventory_model->addInventoryItem($bin_quantity_difference, $bin_id, $tx_date, $transaction_item->variant1_id,
                        $transaction_item->variant2_id, $transaction_item->variant3_id, $transaction_item->variant4_id,
                        InventoryActivityType::RECEIVE, $tx_type . '_bin', $bin['tx_bin_id']);
                }
            }
            //since this transaction bin does not exists, just add it
            else
            {
                //change tx_bin table and update inventory only if this is a new bin or if there is a quantity difference
                if( $bin_quantity > 0 )
                {
                    //add to the transaction bin table
                    $tx_bin_id = $this->addTxBin($tx_bin, $tx_type, $tx_detail_id, $bin_id, $bin_quantity, $tx_date);

                    //update the inventory
                    $inventory_model->addInventoryItem($bin_quantity, $bin_id, $tx_date, $transaction_item->variant1_id,
                        $transaction_item->variant2_id, $transaction_item->variant3_id, $transaction_item->variant4_id,
                        InventoryActivityType::RECEIVE, $tx_type . '_bin', $tx_bin_id);
                }
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
            $tx_bin_id = $this->addTxBin($tx_bin, $tx_type, $tx_detail_id, $default_bin['id'], $remaining_bin_quantity, $tx_date);

            //update the inventory
            $inventory_model->addInventoryItem($remaining_bin_quantity, $default_bin['id'], $tx_date, $transaction_item->variant1_id,
                $transaction_item->variant2_id, $transaction_item->variant3_id, $transaction_item->variant4_id,
                InventoryActivityType::RECEIVE, $tx_type . '_bin', $tx_bin_id);
        }
    }

    private function updateTxBinShip($item, $transaction, $transaction_detail, $classes)
    {
        //set local class instance
        $inventory_model = new Inventory();

        //if bin is not set, then it is a new item so just add bins
        if( empty($item['bins']) )
        {
            $this->addShippingBin($transaction, $transaction_detail, $classes);
            return;
        }

        //get quantity total of the bin items so we can see if the user has set the bins or not.  Also, if the
        //numbers don't match for some reason, we can disregard the user entered bins and reset it from scratch.
        $bin_total = 0;
        $bin_list = [];

        foreach( $item['bins'] as $bin )
        {
            if( is_numeric($bin['quantity']) && $bin['quantity'] > 0 )
            {
                //increment total
                $bin_total += $bin['quantity'];

                //add to array to be used later to add the line items so we don't have to loop through the entire array again.
                $bin_list[] = $bin;
            }
        }

        //check to see if the bin quantity total matches the item total.  If so, then we just add them in sequence
        if( $bin_total == $item['quantity'] )
        {
            foreach( $bin_list as $bin )
            {
                $quantity = $bin['quantity'] * $item['selectedUomMultiplierTotal'];

                //add to the transaction bin table
                $tx_bin_id = $this->addTxBin($classes['transaction_bin'], 'ship', $transaction_detail->id, $bin['id'], $quantity, $bin['receive_date']);

                //update the inventory
                $inventory_model->addInventoryItem($quantity * -1, $bin['id'], $bin['receive_date'], $transaction_detail->variant1_id,
                    $transaction_detail->variant2_id, $transaction_detail->variant3_id, $transaction_detail->variant4_id,
                    InventoryActivityType::SHIP, 'ship_bin', $tx_bin_id);
            }
        }

        //since the quantities doesn't match or bins were not set, just generate all new bins
        else
        {
            $this->addShippingBin($transaction, $transaction_detail, $classes);
        }
    }

    /**
     * Add a transaction bin item
     */
    private function addTxBin($tx_bin, $tx_type, $tx_detail_id, $bin_id, $quantity, $receive_date)
    {
        //get object and set properties
        $bin_object = new $tx_bin;
        $bin_object->{$tx_type . '_detail_id'} = $tx_detail_id;
        $bin_object->bin_id = $bin_id;
        $bin_object->quantity = $quantity;
        $bin_object->receive_date = $receive_date;

        //save tx bin
        $bin_object->save();

        return $bin_object->id;
    }

    /**
     * Add shipping transaction bin data
     *
     * @param $transaction
     * @param $transaction_detail
     */
    private function addShippingBin($transaction, $transaction_detail, $classes)
    {
        /* don't process if the quantity is zero.  Quantity can be zero due to back ordered items, but we don't want to
           set a bin */
        if( $transaction_detail->quantity == 0 ) { return; }

        //get the inventory management type and set query order
        $query_order = ( Client::findOrFail($transaction->client_id)->fifo_lifo == 'fifo' ) ? 'ASC' : 'DESC';

        //get all the bins for this product and variant(s)
        $bins = Bin::select('bin.id', 'inventory.receive_date', DB::raw('SUM(inventory.quantity) AS quantity'))
                   ->join('inventory', 'bin.id', '=', 'inventory.bin_id')
                   ->where('bin.product_id', '=', $transaction_detail->product_id)
                   ->where('inventory.variant1_id', '=', $transaction_detail->variant1_id)
                   ->where('inventory.variant2_id', '=', $transaction_detail->variant2_id)
                   ->where('inventory.variant3_id', '=', $transaction_detail->variant3_id)
                   ->where('inventory.variant4_id', '=', $transaction_detail->variant4_id)
                   ->groupBy('bin.product_id', 'bin.id', 'inventory.receive_date')
                   ->havingRaw('SUM(inventory.quantity) > 0')
                   ->orderBy('inventory.receive_date', $query_order)
                   ->orderBy('quantity')
                   ->get();

        /* If somehow no bins are returned, then inventory is off so stop processing */
        if( count($bins) == 0 ) { throw new Exception('Cannot find the inventory needed in the bins.'); }

        //init variables for adding to ship_bin and inventory table
        $remaining_quantity = $transaction_detail->quantity;
        $inventory_model = new Inventory();

        //walk through the bins and add to the ship_bin table and inventory table
        foreach( $bins as $bin )
        {
            $bin_quantity = $bin->quantity;

            //if there is enough available in this bin, just set the quantity to remaining quantity
            if( $remaining_quantity <= $bin_quantity ) { $quantity = $remaining_quantity; }

            //since there is not enough in the bin, just get all from the bin
            else { $quantity = $bin_quantity; }

            //decrement the remaining quantity
            $remaining_quantity -= $quantity;

            //add to the transaction bin table
            $tx_bin_id = $this->addTxBin($classes['transaction_bin'], 'ship', $transaction_detail->id, $bin->id, $quantity, $bin->receive_date);

            //update the inventory
            $inventory_model->addInventoryItem($quantity * -1, $bin->id, $bin->receive_date, $transaction_detail->variant1_id,
                $transaction_detail->variant2_id, $transaction_detail->variant3_id, $transaction_detail->variant4_id,
                InventoryActivityType::SHIP, 'ship_bin', $tx_bin_id);

            //if we have gotten all the quantities we need, then exit the loop
            if( $remaining_quantity <= 0 ) { break; }
        }
    }

    /**
     * This returns the transaction bin data for receiving transactions
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
                       SUM(inventory.quantity) AS inventory, " . $tx_bin_table . ".id AS tx_bin_id,
                       " . $tx_bin_table . ".quantity /" . $uom_multiplier . " AS quantity
                FROM bin
                    LEFT OUTER JOIN inventory
                        ON inventory.bin_id = bin.id
                    LEFT OUTER JOIN " . $tx_bin_table . "
                        ON " . $tx_bin_table . ".bin_id = bin.id
                            AND " . $tx_bin_table . "." . $tx_type . "_detail_id = ?
                WHERE bin.product_id = ?
                  AND " . $tx_bin_table . ".deleted_at IS NULL
                GROUP BY bin.id, bin.aisle, bin.section, bin.tier, bin.position, bin.default, " .
                         $tx_bin_table . ".quantity, " . $tx_bin_table . ".id
                ORDER BY bin.aisle ASC, bin.section ASC, bin.tier ASC, bin.position ASC";

        return DB::select($sql, [$tx_detail_id, $product_id]);
    }

    /**
     * This returns the transaction bin data for shipping transactions
     *
     * @return mixed
     */
    public function getTransactionBinShip($uom_multiplier, $line_item)
    {
        //set variants here
        $variant1_id = ( empty($line_item->variant1_id) ) ? 'IS NULL' : ' = ' . $line_item->variant1_id;
        $variant2_id = ( empty($line_item->variant2_id) ) ? 'IS NULL' : ' = ' . $line_item->variant2_id;
        $variant3_id = ( empty($line_item->variant3_id) ) ? 'IS NULL' : ' = ' . $line_item->variant3_id;
        $variant4_id = ( empty($line_item->variant4_id) ) ? 'IS NULL' : ' = ' . $line_item->variant4_id;

        /* We are using raw sql here due to limitations of eloquent and join statements with passing in variables
           into multiple join on statements */
        $sql = "SELECT bin.id, bin.aisle, bin.section, bin.tier, bin.position, bin.default, inventory.receive_date,
                       CASE WHEN (ship_bin.quantity IS NULL)
                                THEN SUM(inventory.quantity)
                                ELSE SUM(inventory.quantity) + ship_bin.quantity
                            END AS inventory,
                       ship_bin.id AS tx_bin_id,
                       ship_bin.quantity /" . $uom_multiplier . " AS quantity
                FROM inventory
                    INNER JOIN bin
                        ON inventory.bin_id = bin.id
                    LEFT OUTER JOIN ship_bin
                        ON ship_bin.bin_id = inventory.bin_id
                            AND ship_bin.receive_date = inventory.receive_date
                            AND ship_bin.deleted_at IS NULL
                            AND ship_bin.ship_detail_id = ?
                WHERE bin.product_id = ?
                  AND inventory.variant1_id " . $variant1_id . "
                  AND inventory.variant2_id " . $variant2_id . "
                  AND inventory.variant3_id " . $variant3_id . "
                  AND inventory.variant4_id " . $variant4_id . "
                GROUP BY bin.id, bin.aisle, bin.section, bin.tier, bin.position, bin.default, inventory.receive_date, ship_bin.quantity, ship_bin.id
                HAVING SUM(inventory.quantity) + ship_bin.quantity > 0 OR SUM(inventory.quantity) > 0
                ORDER BY bin.aisle ASC, bin.section ASC, bin.tier ASC, bin.position ASC, inventory.receive_date";

        return DB::select($sql, [$line_item->id, $line_item->product_id]);
    }

    /**
     * Pass in the detail items and it will delete the bins associated with them and back out the inventory
     *
     * @param $tx_type
     * @param $tx_detail_items
     * @param $classes
     *
     * @throws Exception
     */
    private function deleteTxBin($tx_type, $tx_detail_items, $classes)
    {
        //loop through and soft delete the transaction and back out the bins
        foreach( $tx_detail_items as $tx_detail_item )
        {
            //if we have bins, delete them and back out the inventory
            if( isset($classes['transaction_bin']) )
            {
                //get bins
                $tx_bin_model = new $classes['transaction_bin'];
                $transaction_bins = $tx_bin_model::where($tx_type . '_detail_id', '=', $tx_detail_item->id)->get();

                //get inventory model
                $inventory_model = new Inventory();

                //set activity type
                $activity_type = ( $tx_type == 'receive' ) ? InventoryActivityType::RECEIVE : InventoryActivityType::SHIP;

                //update inventory
                foreach ( $transaction_bins as $transaction_bin )
                {
                    //set quantity. Receiving needs to subtract while shipping needs to add
                    $quantity = ( $tx_type == 'receive' ) ? $transaction_bin->quantity * -1 : $transaction_bin->quantity;

                    //update the inventory
                    $inventory_model->addInventoryItem($quantity, $transaction_bin->bin_id, $transaction_bin->receive_date,
                        $tx_detail_item->variant1_id, $tx_detail_item->variant2_id, $tx_detail_item->variant3_id,
                        $tx_detail_item->variant4_id, $activity_type, $tx_type . '_bin', $transaction_bin->id);
                }

                //delete bins
                $tx_bin_model::where($tx_type . '_detail_id', '=', $tx_detail_item->id)->delete();
            }
        }
    }

    private function sendConfirmationEmail($tx_type, $tx_data, $action, $classes)
    {
        /* WE MAY NEED TO VERIFY IF THIS TYPE OF TRANSACTION FOR THIS PARTICULAR WAREHOUSE/CLIENT SHOULD BE SENT OR NOT
           DEPENDING ON THE USER ACCOUNT SETTINGS AND THE TX ACTIVITY TYPE.  FOR EXAMPLE, SHOULD THERE BE CONFIRMATIONS
           FOR VOIDS AND CONVERSIONS?  OR, JUST NEW AND UPDATES? */

        /* DON'T SEND EMAILS IF THE FLAG IS SET IN THE .env FILE. THIS IS FOR LOCAL DEBUGGING AND TESTING */
        if( env('APP_CONFIRM_EMAIL_DO_NOT_SEND') === true ) { return; }

        /* NEED TO ACCOUNT FOR WHITE LABEL SITES HERE
             Email, From, Image, Subject, Outbound Server, etc */
        //use temp object for now
        $site = ['site_title' => 'JPE Logistics',
                 'admin_email' => 'dev.transactions@jpent.com',
                 'admin_name' => 'Jpent Admin'];

        //get transaction detail
        $tx_data->items = $this->getEmailTransactionDetail($tx_type, $tx_data->id, $classes['transaction_detail']);

        //set addresses
        $this->setEmailTemplateAddress($tx_data);

        //set template properties
        $this->setEmailTemplateProperty($tx_data, $tx_type, $action);

        //get to email addresses - only send to current user in debug mode to prevent mass sending of emails
        $to_emails = env('APP_CONFIRM_EMAIL_DEV') ? auth()->user()->email :
                        $this->getConfirmationEmailAddress($tx_data->warehouse_id, $tx_data->client_id, $tx_type, $action);

        //set the host for the images.  The reason is that on a local host server, we need to set it to a public location
        $tx_data->host = strpos(url('/'), 'local') ? 'http://jpent.01digitalmarketing.com' : url('/');

        Mail::send('emails.transaction', ['data' => $tx_data], function ($m) use($tx_type, $site, $tx_data, $to_emails)
        {
            //set email addresses
            $m->from($site['admin_email'], $site['admin_name']);
            $m->to($to_emails);
            $m->cc($site['admin_email']);

            //set subject
            $m->subject($tx_data->tx_title . ' confirmation from ' . $site['site_title']);
        });
    }

    private function getConfirmationEmailAddress($warehouse_id, $client_id, $tx_type, $action)
    {
        //find all active users with this warehouse/client and has current tx_type email set to true
        $emails = User::join('user_warehouse', 'user.id', '=', 'user_warehouse.user_id')
                      ->join('user_client', 'user.id', '=', 'user_client.user_id')
                      ->where('warehouse_id', '=', $warehouse_id)
                      ->where('client_id', '=', $client_id)
                      ->where('tx_email_' . $tx_type, '=', true)
                      ->where('user.active', '=', true)
                      ->whereNotNull('email')
                      ->where('email', '!=', '')
                      ->distinct()->lists('email')->toArray();

        /* There could be a situation where no one wants the emails.  In this case, always send a confirmation
           to the person completing the transaction */
        if( count($emails) == 0 ) { $emails[] = auth()->user()->email; }

        return $emails;
    }

    /**
     * This method adds the addresses for the email template
     *
     * @param $tx_data
     */
    public function setEmailTemplateAddress(&$tx_data)
    {
        //get addresses of warehouse, client, carrier, and customer
        $tx_data->warehouse = Warehouse::find($tx_data->warehouse_id);
        $tx_data->client = Client::find($tx_data->client_id);
        $tx_data->carrier = Carrier::find($tx_data->carrier_id);
        if( isset($tx_data->customer_id) ) { $tx_data->customer = Customer::find($tx_data->customer_id); }
    }

    /**
     * This method adds the email template properties
     *
     * @param $tx_data
     * @param $tx_type
     * @param $action
     */
    public function setEmailTemplateProperty(&$tx_data, $tx_type, $action)
    {
        //add tx_type to data
        $tx_data->tx_type = $tx_type;

        //get transaction status name if not set as it won't be if it is a new transaction
        if( empty($tx_data->tx_status_name) )
        {
            $tx_data->tx_status_name = \App\Models\TxStatus::where('id', '=', $tx_data->tx_status_id)->pluck('name');
        }

        //reformat tx_date
        $date = date_create($tx_data->tx_date);
        $tx_data->tx_date = $date->format('m-d-Y');

        //set the tx title and warehouse heading
        switch( $tx_type )
        {
            case 'asn':
                $tx_data->tx_title = 'ASN - Advanced Shipping Notice';
                $tx_data->warehouse_title = 'Ship To';
                break;
            case 'receive';
                $tx_data->tx_title = 'Receiving';
                $tx_data->warehouse_title = 'Ship To';
                break;
            case 'csr';
                $tx_data->tx_title = 'CSR - Client Stock Release';
                $tx_data->warehouse_title = 'Ship From';
                break;
            case 'ship';
                $tx_data->tx_title = 'Shipping';
                $tx_data->warehouse_title = 'Ship From';
                break;
        }

        //set tx status message for created status
        if( $action == TransactionEmailActivity::created )
        {
            $tx_data->tx_status_message = 'A new transaction was created';
        }

        //tx status message for all other statuses
        else
        {
            $tx_data->tx_status_message = 'The transaction was ' . $action;
        }
    }

    public function getEmailTransactionDetail($tx_type, $tx_id, $tx_detail_class)
    {
        //add line items
        $tx_detail = new $tx_detail_class;
        $table_detail = $tx_type . '_detail';

        //get detail line item
        $transaction_details = $tx_detail::select($table_detail . '.id',
                                                  $table_detail . '.product_id',
                                                  'product.sku',
                                                  'product.name',
                                                  $table_detail . '.quantity',
                                                  $table_detail . '.uom_multiplier',
                                                  $table_detail . '.uom_name',
                                                  $table_detail . '.variant1_id',
                                                  $table_detail . '.variant2_id',
                                                  $table_detail . '.variant3_id',
                                                  $table_detail . '.variant4_id',
                                                  'product_variant1.value AS variant1_value',
                                                  'product_variant2.value AS variant2_value',
                                                  'product_variant3.value AS variant3_value',
                                                  'product_variant4.value AS variant4_value',
                                                  'product_variant1.name AS variant1_name',
                                                  'product_variant2.name AS variant2_name',
                                                  'product_variant3.name AS variant3_name',
                                                  'product_variant4.name AS variant4_name')
                                        ->leftJoin('product_variant1', $table_detail . '.variant1_id', '=', 'product_variant1.id')
                                        ->leftJoin('product_variant2', $table_detail . '.variant2_id', '=', 'product_variant2.id')
                                        ->leftJoin('product_variant3', $table_detail . '.variant3_id', '=', 'product_variant3.id')
                                        ->leftJoin('product_variant4', $table_detail . '.variant4_id', '=', 'product_variant4.id')
                                        ->join('product', $table_detail . '.product_id', '=', 'product.id')
                                        ->where($tx_type . '_id', '=', $tx_id)
                                        ->orderBy($table_detail . '.id')->get();

        //if it is a ASN ship or shipping transaction, add inventory numbers
        if( $tx_type == 'csr' || $tx_type == 'ship' )
        {
            //get inventory model object
            $inventory_model = new Inventory();

            //calculate inventory for each item
            foreach( $transaction_details as $item )
            {
                //get inventory
                $inventory = $inventory_model->getVariantInventoryTotal($item->product_id, $item->variant1_id,
                                        $item->variant2_id, $item->variant3_id, $item->variant4_id);

                //convert numbers to uom and don't divide if inventory is zero
                $item->inventory = $inventory > 0 ? $inventory / $item->uom_multiplier : $inventory;
            }
        }

        return $transaction_details;
    }

    /**
     * The log of all transaction changes
     *
     * @param $tx_id
     * @param $tx_table
     * @param $tx_activity_type_id
     * @param $tx_data
     *
     * @return mixed
     */
    public function addTransactionLog($tx_id, $tx_table, $tx_activity_type_id, $tx_data)
    {
        return TransactionLog::create(['user_id' => auth()->user()->id,
                                       'transaction_id' => $tx_id,
                                       'transaction_table' => $tx_table,
                                       'transaction_activity_type_id' => $tx_activity_type_id,
                                       'transaction_data' => json_encode($tx_data)]);
    }

    public function checkConverted($tx_type, $tx_id)
    {
        //get original transaction type that was converted
        $original_tx_type = $this->getConvertTxType($tx_type);

        //get class
        $classes = $this->getClasses($original_tx_type);

        //check to see if this was converted
        $transaction = new $classes['transaction'];
        $data = $transaction::where('id', '=', $tx_id)->pluck('converted_tx_id');

        return $data;
    }

    public function getConvertTxType($tx_type)
    {
        switch( $tx_type )
        {
            case 'receive':
                return 'asn';
                break;
            case 'ship':
                return 'csr';
                break;
            default:
                throw new Exception('Invalid transaction type for conversion');
        }
    }

    /**
     * This sets up the transaction model classes
     *
     * @param $tx_type
     *
     * @return mixed
     */
    public function getClasses($tx_type)
    {
        switch( $tx_type )
        {
            case 'asn':
                $classes['transaction'] = 'App\Models\Asn';
                $classes['transaction_detail'] = 'App\Models\AsnDetail';
                break;
            case 'receive';
                $classes['transaction'] = 'App\Models\Receive';
                $classes['transaction_detail'] = 'App\Models\ReceiveDetail';
                $classes['transaction_bin'] = 'App\Models\ReceiveBin';
                break;
            case 'csr';
                $classes['transaction'] = 'App\Models\Csr';
                $classes['transaction_detail'] = 'App\Models\CsrDetail';
                break;
            case 'ship';
                $classes['transaction'] = 'App\Models\Ship';
                $classes['transaction_detail'] = 'App\Models\ShipDetail';
                $classes['transaction_bin'] = 'App\Models\ShipBin';
                break;
        }

        //do an error check here in case the wrong tx_type or no tx_type was sent in
        if( !isset($classes) ) { throw new Exception('Invalid transaction type: ' . $tx_type); }

        return $classes;
    }
}
