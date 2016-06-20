<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;

use DB;

class Inventory extends Model
{
    protected $table = 'inventory';

    public function addInventoryItem($quantity, $bin_id, $receive_date, $variant1_id, $variant2_id, $variant3_id,
                                     $variant4_id, $inventory_activity_type_id, $activity_table, $activity_id)
    {
        //we can't add partial quantities
        if( is_numeric($quantity) && floor($quantity) != $quantity )
        {
            throw new Exception("Quantities must be a whole number and can't have decimals");
        }

        //first check the current inventory total to make sure we can't add a negative value
        $current_total = $this->getBinItemQuantity($bin_id, $receive_date, $variant1_id, $variant2_id, $variant3_id, $variant4_id);

        if( $current_total + $quantity < 0 ) { throw new Exception('The inventory would be negative if this record is added.'); }

        //add entry to inventory table
        $inventory_item = new Inventory();
        $inventory_item->user_id = auth()->user()->id;
        $inventory_item->inventory_activity_type_id = $inventory_activity_type_id;
        $inventory_item->activity_table = $activity_table;
        $inventory_item->activity_id = $activity_id;
        $inventory_item->bin_id = $bin_id;
        $inventory_item->quantity = $quantity;
        $inventory_item->receive_date = $receive_date;
        $inventory_item->variant1_id = $variant1_id;
        $inventory_item->variant2_id = $variant2_id;
        $inventory_item->variant3_id = $variant3_id;
        $inventory_item->variant4_id = $variant4_id;

        $inventory_item->save();
    }

    public function getBinItemQuantity($bin_id, $receive_date, $variant1_id, $variant2_id, $variant3_id, $variant4_id)
    {
        $result = Inventory::where('bin_id', '=', $bin_id)
                           ->where('receive_date', '=', $receive_date)
                           ->where('variant1_id', '=', $variant1_id)
                           ->where('variant2_id', '=', $variant2_id)
                           ->where('variant3_id', '=', $variant3_id)
                           ->where('variant4_id', '=', $variant4_id)
                           ->sum('quantity');

        return $result;
    }

    public function getProductInventory($product_id)
    {
        //first grab the bins and the rollup total in each and the product type data to get the variants
        $bins = Inventory::select('bin.id', 'bin.aisle', 'bin.section', 'bin.tier', 'bin.position', 'bin.active', 'bin.default',
                                  DB::raw('SUM(inventory.quantity) as total'),
                                  'product_type.variant1', 'product_type.variant2', 'product_type.variant3', 'product_type.variant4')
                        ->rightJoin('bin', 'inventory.bin_id', '=', 'bin.id')
                        ->join('product', 'bin.product_id', '=', 'product.id')
                        ->join('product_type', 'product.product_type_id', '=', 'product_type.id')
                        ->where('bin.product_id', '=', $product_id)
                        ->groupBy('bin.id', 'bin.aisle', 'bin.section', 'bin.tier', 'bin.position', 'bin.active',
                                  'product_type.variant1', 'product_type.variant2', 'product_type.variant3', 'product_type.variant4')
                        ->orderBy('bin.aisle')->orderBy('bin.section')->orderBy('bin.tier')->orderBy('bin.position')
                        ->get();

        //now add the variants and items from each receive date
        foreach( $bins as $bin )
        {
            $bin_data = Inventory::select('inventory.receive_date',
                                            DB::raw('SUM(inventory.quantity) AS quantity'),
                                            'bin.default',
                                            'product_variant1.value as variant1_value',
                                            'product_variant2.value as variant2_value',
                                            'product_variant3.value as variant3_value',
                                            'product_variant4.value as variant4_value')
                                ->join('bin', 'inventory.bin_id', '=', 'bin.id')
                                ->leftJoin('product_variant1', 'inventory.variant1_id', '=', 'product_variant1.id')
                                ->leftJoin('product_variant2', 'inventory.variant2_id', '=', 'product_variant2.id')
                                ->leftJoin('product_variant3', 'inventory.variant3_id', '=', 'product_variant3.id')
                                ->leftJoin('product_variant4', 'inventory.variant4_id', '=', 'product_variant4.id')
                                ->where('inventory.bin_id', '=', $bin->id)
                                ->groupBy('inventory.bin_id', 'inventory.receive_date', 'product_variant1.value',
                                    'product_variant2.value', 'product_variant3.value', 'product_variant4.value', 'bin.default')
                                ->orderBy('product_variant1.value')
                                ->orderBy('product_variant2.value')
                                ->orderBy('product_variant3.value')
                                ->orderBy('product_variant4.value')
                                ->orderBy('inventory.receive_date')
                                ->get();

            $bin->bin_items = $bin_data;
        }

        return $bins;
    }

    public function getProductInventoryTotal($product_id)
    {
        $result = Inventory::join('bin', 'inventory.bin_id', '=', 'bin.id')
                            ->join('product', 'bin.product_id', '=', 'product.id')
                            ->where('bin.product_id', '=', $product_id)
                            ->groupBy('bin.product_id')
                            ->sum('inventory.quantity');

        return $result;
    }

    public function getVariantInventoryTotal($product_id, $variant1_id, $variant2_id, $variant3_id, $variant4_id)
    {
        $result = Inventory::join('bin', 'inventory.bin_id', '=', 'bin.id')
                            ->join('product', 'bin.product_id', '=', 'product.id')
                            ->where('bin.product_id', '=', $product_id)
                            ->where('inventory.variant1_id', '=', $variant1_id)
                            ->where('inventory.variant2_id', '=', $variant2_id)
                            ->where('inventory.variant3_id', '=', $variant3_id)
                            ->where('inventory.variant4_id', '=', $variant4_id)
                            ->groupBy('bin.product_id')
                            ->sum('inventory.quantity');
        return $result;
    }

    /* Not used right now.  We are doing an ajax call to get the total in the method above as when there are multiple variants, the combo gets a bit complicated.
    public function getProductInventoryByVariant($product_id)
    {
        $result = Inventory::select('product.id', DB::raw('SUM(inventory.quantity) as total'),
                                     'inventory.variant1_id', 'inventory.variant2_id', 'inventory.variant3_id', 'inventory.variant4_id')
                            ->join('bin', 'inventory.bin_id', '=', 'bin.id')
                            ->join('product', 'bin.product_id', '=', 'product.id')
                            ->where('bin.product_id', '=', $product_id)
                            ->groupBy('bin.product_id', 'inventory.variant1', 'inventory.variant2', 'inventory.variant3', 'inventory.variant4')
                            ->get();

        return $result;
    } */
}