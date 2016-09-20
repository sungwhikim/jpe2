<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

class Report extends Model
{
    protected $paginate_num_items = 25;

    public function __construct()
    {
        parent::__construct();

        $this->paginate_num_items = env('REPORT_PAGINATE_NUM_ITEMS');
    }

    public function inventoryWarehouse($request)
    {
        /* VALIDATE DATA */

        //get data
        $data = Inventory::select('product.sku', 'product.name', 'product.active',
                            'product_type.uom1', 'product_type.uom2', 'product_type.uom3', 'product_type.uom4',
                            'product_type.uom5', 'product_type.uom6', 'product_type.uom7', 'product_type.uom8',
                            'product_variant1.name AS variant1_name', 'product_variant1.value AS variant1_value',
                            'product_variant2.name AS variant2_name', 'product_variant2.value AS variant2_value',
                            'product_variant3.name AS variant3_name', 'product_variant3.value AS variant3_value',
                            'product_variant4.name AS variant4_name', 'product_variant4.value AS variant4_value',
                            'product.uom1 AS uom1_multiplier', 'product.uom2 AS uom2_multiplier', 'product.uom3 AS uom3_multiplier',
                            'product.uom4 AS uom4_multiplier', 'product.uom5 AS uom5_multiplier', 'product.uom6 AS uom6_multiplier',
                            'product.uom7 AS uom7_multiplier', 'product.uom8 AS uom8_multiplier',
                            DB::raw('SUM(inventory.quantity) AS quantity'))
                         ->rightJoin('bin', 'inventory.bin_id', '=', 'bin.id')
                         ->rightJoin('product', 'bin.product_id', '=', 'product.id')
                         ->join('product_type', 'product.product_type_id', '=', 'product_type.id')
                         ->leftJoin('product_variant1', 'inventory.variant1_id', '=', 'product_variant1.id')
                         ->leftJoin('product_variant2', 'inventory.variant2_id', '=', 'product_variant2.id')
                         ->leftJoin('product_variant3', 'inventory.variant3_id', '=', 'product_variant3.id')
                         ->leftJoin('product_variant4', 'inventory.variant4_id', '=', 'product_variant4.id')
                         ->where('product.warehouse_id', '=', $request->get('warehouse_id'))
                         ->where('product.client_id', '=', $request->get('client_id'))
                         ->whereRaw("(inventory.updated_at <= '" . $request->get('end_date') . "' OR inventory.updated_at IS NULL)")
                         ->groupBy('bin.product_id', 'product.sku', 'product.name', 'product.active', 'product_variant1.value',
                             'product_variant2.value', 'product_variant3.value', 'product_variant4.value',
                             'product_type.uom1', 'product_type.uom2', 'product_type.uom3', 'product_type.uom4',
                             'product_type.uom5', 'product_type.uom6', 'product_type.uom7', 'product_type.uom8',
                             'product_variant1.name', 'product_variant2.name', 'product_variant3.name', 'product_variant4.name',
                             'product_variant1.value', 'product_variant2.value', 'product_variant3.value', 'product_variant4.value',
                             'product.uom1', 'product.uom2', 'product.uom3', 'product.uom4', 'product.uom5', 'product.uom6', 'product.uom7', 'product.uom8')
                         ->orderBy('product.sku')->orderBy('product_variant1.name')->orderBy('product_variant2.name')
                         ->orderBy('product_variant3.name')->orderBy('product_variant4.name')->orderBy('product_variant1.value')
                         ->orderBy('product_variant2.value')->orderBy('product_variant3.value')->orderBy('product_variant4.value');

        //add filter for zero quantities
        $this->addZeroQuantityFilter($data, $request);

        //get the data
        $result = $data->paginate($this->paginate_num_items);

        //add the uom header info
        $result->uom_data = $this->getUomData($result);

        return $result;
    }

    /**
     * The reason we are removing the page number query string value this way is that we don't want to
     * pop it off of the $_GET global as it would be a bad practice to do so.  It is better to create a copy
     * with only the values we want.
     *
     * @return array
     */
    public function getCriteria()
    {
        //set return array
        $data = $_GET;

        //remove the page key
        unset($data['page']);

        //convert values into integers because we need to do this for the JavaScript to work properly later on
        if( isset($data['warehouse_id']) ) { $data['warehouse_id'] = intval($data['warehouse_id']); }
        if( isset($data['client_id']) ) { $data['client_id'] = intval($data['client_id']); }
        if( isset($data['product_id']) ) { $data['product_id'] = intval($data['product_id']); }

        //convert boolean
        if( isset($data['zero_inventory_items']) )
        {
            $data['zero_inventory_items'] = filter_var($data['zero_inventory_items'], FILTER_VALIDATE_BOOLEAN);
        }

        return $data;
    }

    public function getUomData($data)
    {
        //initialize the return array
        $uom_list = [];

        //go through each uom list and get the distinct list
        for( $i = 1; $i <= 8; $i++ )
        {
            //set column name
            $column = 'uom' . $i;

            //get list of unique values
            $result = $data->unique($column);

            //if there are any unique values, add them to uom list
            if( count($result) > 0 )
            {
                //initialize array
                $uoms = [];

                //loop through and add them
                foreach( $result as $row )
                {
                    if( $row[$column] != null ) { $uoms[] = $row[$column]; }
                }

                //add to main return array if there is something to add
                if( count($uoms) > 0 ) { $uom_list[] = $uoms; }
            }
        }
;
        return $uom_list;
    }

    /**
     * Adds the zero product quantity filter if needed
     *
     * @param $data
     * @param $request
     */
    protected function addZeroQuantityFilter(&$data, $request)
    {
        //add filter for zero quantities
        if( filter_var($request->get('zero_inventory_items'), FILTER_VALIDATE_BOOLEAN) !== true)
        {
            $data->havingRaw('SUM(inventory.quantity) > 0');
        }
    }
}