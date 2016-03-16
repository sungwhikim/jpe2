<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    protected $table = 'inventory_log';

    public function addItem($type, $product_id, $bin_id, $bin_item)
    {
        $bin_log = new InventoryLog();
        $bin_log->type = $type;
        $bin_log->bin_id = $bin_id;
        $bin_log->product_id = $product_id;
        $bin_log->user_id = auth()->user()->id;
        $bin_log->variant1_id = ( !empty($bin_item['variant1_id']) ) ? $bin_item['variant1_id'] : null;
        $bin_log->variant2_id = ( !empty($bin_item['variant2_id']) ) ? $bin_item['variant2_id'] : null;
        $bin_log->variant3_id = ( !empty($bin_item['variant3_id']) ) ? $bin_item['variant3_id'] : null;
        $bin_log->variant4_id = ( !empty($bin_item['variant4_id']) ) ? $bin_item['variant4_id'] : null;
        $bin_log->receive_date = $bin_item['receive_date'];
        $bin_log->quantity_change = $bin_item['quantity_new'] - $bin_item['quantity'];
        $bin_log->quantity = $bin_item['quantity'];
        $bin_log->save();
    }
}