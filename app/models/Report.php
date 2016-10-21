<?php
namespace App\Models;

use App\Enum\TransactionTypeName;
use App\Enum\TxStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\Warehouse;
use App\Models\Client;
use App\Models\Product;
use App\Models\Csr;
use App\Models\Transaction;

use DB;

/**
 * Class Report
 *
 *  ---
 *      IMPORTANT NOTE: If you add a new report or change a report criteria, you need to edit both getCriteria() and
 *      getDisplayCriteria() to make sure they are formatted properly
 *  ---
 *
 * @package App\Models
 */
class Report extends Model
{
    protected $paginate_num_items = 25;

    public function __construct()
    {
        parent::__construct();

        $this->paginate_num_items = env('REPORT_PAGINATE_NUM_ITEMS');
    }

    /**
     * This is for the Inventory By Warehouse report
     *
     * @param $request
     * @param $paginate
     *
     * @return mixed
     */
    public function inventoryWarehouse($request, $paginate)
    {
        /* VALIDATE DATA */

        //get data
        $query = Inventory::select('product.sku', 'product.name', 'product.active',
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
                        ->whereRaw("(inventory.updated_at <= '" . $request->get('as_of_date') . "' OR inventory.updated_at IS NULL)")
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
        $this->addZeroQuantityFilter($query, $request->get('zero_inventory_items'));

        //add the product filter
        $this->addProductFilter($query, $request->get('product_id'));

        //get data with accounting for whether it needs to be paginated or not
        $data['body'] = $this->getResult($query, $paginate);

        //get total data
        $total = Inventory::select( DB::raw('lower(product_type.uom1) AS uom1'), DB::raw('lower(product_type.uom2) AS uom2'),
                                    DB::raw('lower(product_type.uom3) AS uom3'), DB::raw('lower(product_type.uom4) AS uom4'),
                                    DB::raw('lower(product_type.uom5) AS uom5'), DB::raw('lower(product_type.uom6) AS uom6'),
                                    DB::raw('lower(product_type.uom7) AS uom7'), DB::raw('lower(product_type.uom8) AS uom8'),
                                    DB::raw('SUM(inventory.quantity) AS quantity1'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 AS quantity2'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 AS quantity3'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 AS quantity4'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 / product.uom5 AS quantity5'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 / product.uom5 / product.uom6 AS quantity6'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 / product.uom5 / product.uom6 / product.uom7 AS quantity7'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 / product.uom5 / product.uom6 / product.uom7 / product.uom8 AS quantity8'))
                            ->rightJoin('bin', 'inventory.bin_id', '=', 'bin.id')
                            ->rightJoin('product', 'bin.product_id', '=', 'product.id')
                            ->join('product_type', 'product.product_type_id', '=', 'product_type.id')
                            ->where('product.warehouse_id', '=', $request->get('warehouse_id'))
                            ->where('product.client_id', '=', $request->get('client_id'))
                            ->whereRaw("(inventory.updated_at <= '" . $request->get('as_of_date') . "' OR inventory.updated_at IS NULL)")
                            ->groupBy('bin.product_id', 'product_type.uom1', 'product_type.uom2', 'product_type.uom3', 'product_type.uom4',
                                'product_type.uom5', 'product_type.uom6', 'product_type.uom7', 'product_type.uom8',
                                'product.uom1', 'product.uom2', 'product.uom3', 'product.uom4', 'product.uom5', 'product.uom6', 'product.uom7', 'product.uom8');

        //add filter for zero quantities
        $this->addZeroQuantityFilter($total, $request->get('zero_inventory_items'));

        //add the product filter
        $this->addProductFilter($total, $request->get('product_id'));

        //calculate the totals
        $data['total'] = $this->getQuantityTotal($total->get());

        return $data;
    }

    /**
     * This is for the Inventory Pallet Detail Report
     *
     * @param $request
     * @param $paginate
     *
     * @return mixed
     */
    public function inventoryPalletDetail($request, $paginate)
    {
        /* VALIDATE DATA */

        //get data
        $query = Inventory::select('product.id AS product_id', 'product.sku', 'product.name', 'product.active',
                                'bin.aisle', 'bin.section', 'bin.tier', 'bin.position',
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
                        ->whereRaw("(inventory.updated_at <= '" . $request->get('as_of_date') . "' OR inventory.updated_at IS NULL)")
                        ->groupBy('bin.id', 'bin.aisle', 'bin.section', 'bin.tier', 'bin.position',
                            'product.id', 'product.sku', 'product.name', 'product.active', 'product_variant1.value',
                            'product_variant2.value', 'product_variant3.value', 'product_variant4.value',
                            'product_type.uom1', 'product_type.uom2', 'product_type.uom3', 'product_type.uom4',
                            'product_type.uom5', 'product_type.uom6', 'product_type.uom7', 'product_type.uom8',
                            'product_variant1.name', 'product_variant2.name', 'product_variant3.name', 'product_variant4.name',
                            'product_variant1.value', 'product_variant2.value', 'product_variant3.value', 'product_variant4.value',
                            'product.uom1', 'product.uom2', 'product.uom3', 'product.uom4', 'product.uom5', 'product.uom6', 'product.uom7', 'product.uom8')
                        ->orderBy('product.sku')->orderBy('product.id')->orderBy('product_variant1.name')->orderBy('product_variant2.name')
                        ->orderBy('product_variant3.name')->orderBy('product_variant4.name')->orderBy('product_variant1.value')
                        ->orderBy('product_variant2.value')->orderBy('product_variant3.value')->orderBy('product_variant4.value')
                        ->orderBy('bin.aisle')->orderBy('bin.section')->orderBy('bin.tier')->orderBy('bin.position');

        //add the filters
        $this->addZeroQuantityFilter($query, $request->get('zero_inventory_items'));
        $this->addProductFilter($query, $request->get('product_id'));

        //get data with accounting for whether it needs to be paginated or not
        $data['body'] = $this->getResult($query, $paginate);

        //get total data
        $total = Inventory::select( DB::raw('lower(product_type.uom1) AS uom1'), DB::raw('lower(product_type.uom2) AS uom2'),
                                    DB::raw('lower(product_type.uom3) AS uom3'), DB::raw('lower(product_type.uom4) AS uom4'),
                                    DB::raw('lower(product_type.uom5) AS uom5'), DB::raw('lower(product_type.uom6) AS uom6'),
                                    DB::raw('lower(product_type.uom7) AS uom7'), DB::raw('lower(product_type.uom8) AS uom8'),
                                    DB::raw('SUM(inventory.quantity) AS quantity1'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 AS quantity2'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 AS quantity3'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 AS quantity4'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 / product.uom5 AS quantity5'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 / product.uom5 / product.uom6 AS quantity6'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 / product.uom5 / product.uom6 / product.uom7 AS quantity7'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 / product.uom5 / product.uom6 / product.uom7 / product.uom8 AS quantity8'))
                        ->rightJoin('bin', 'inventory.bin_id', '=', 'bin.id')
                        ->rightJoin('product', 'bin.product_id', '=', 'product.id')
                        ->join('product_type', 'product.product_type_id', '=', 'product_type.id')
                        ->where('product.warehouse_id', '=', $request->get('warehouse_id'))
                        ->where('product.client_id', '=', $request->get('client_id'))
                        ->whereRaw("(inventory.updated_at <= '" . $request->get('as_of_date') . "' OR inventory.updated_at IS NULL)")
                        ->groupBy('bin.product_id', 'product_type.uom1', 'product_type.uom2', 'product_type.uom3', 'product_type.uom4',
                            'product_type.uom5', 'product_type.uom6', 'product_type.uom7', 'product_type.uom8',
                            'product.uom1', 'product.uom2', 'product.uom3', 'product.uom4', 'product.uom5', 'product.uom6', 'product.uom7', 'product.uom8');

        //add the filters
        $this->addZeroQuantityFilter($total, $request->get('zero_inventory_items'));
        $this->addProductFilter($total, $request->get('product_id'));

        //calculate the totals
        $data['total'] = $this->getQuantityTotal($total->get());

        return $data;
    }

    /**
     * This is for the Zero Inventory report
     *
     * @param $request
     * @param $paginate
     *
     * @return mixed
     */
    public function inventoryZero($request, $paginate)
    {
        /* VALIDATE DATA */

        //get data
        $query = Inventory::select('product.sku', 'product.name', 'product.active',
                                    DB::raw('MAX(ship.tx_date) AS tx_date'),
                                    DB::raw('MAX(inventory.updated_at) AS inv_date'),
                                    'product_type.uom1', 'product_type.uom2', 'product_type.uom3', 'product_type.uom4',
                                    'product_type.uom5', 'product_type.uom6', 'product_type.uom7', 'product_type.uom8',
                                    'product_variant1.name AS variant1_name', 'product_variant1.value AS variant1_value',
                                    'product_variant2.name AS variant2_name', 'product_variant2.value AS variant2_value',
                                    'product_variant3.name AS variant3_name', 'product_variant3.value AS variant3_value',
                                    'product_variant4.name AS variant4_name', 'product_variant4.value AS variant4_value')
                            ->rightJoin('bin', 'inventory.bin_id', '=', 'bin.id')
                            ->rightJoin('product', 'bin.product_id', '=', 'product.id')
                            ->join('product_type', 'product.product_type_id', '=', 'product_type.id')
                            ->leftJoin('product_variant1', 'inventory.variant1_id', '=', 'product_variant1.id')
                            ->leftJoin('product_variant2', 'inventory.variant2_id', '=', 'product_variant2.id')
                            ->leftJoin('product_variant3', 'inventory.variant3_id', '=', 'product_variant3.id')
                            ->leftJoin('product_variant4', 'inventory.variant4_id', '=', 'product_variant4.id')
                            ->leftJoin('ship_detail', 'product.id', '=', 'ship_detail.product_id')
                            ->leftJoin('ship', 'ship_detail.ship_id', '=', 'ship.id')
                            ->where('product.warehouse_id', '=', $request->get('warehouse_id'))
                            ->where('product.client_id', '=', $request->get('client_id'))
                            ->groupBy('bin.product_id', 'product.sku', 'product.name', 'product.active', 'product_variant1.value',
                                'product_variant2.value', 'product_variant3.value', 'product_variant4.value',
                                'product_type.uom1', 'product_type.uom2', 'product_type.uom3', 'product_type.uom4',
                                'product_type.uom5', 'product_type.uom6', 'product_type.uom7', 'product_type.uom8',
                                'product_variant1.name', 'product_variant2.name', 'product_variant3.name', 'product_variant4.name',
                                'product_variant1.value', 'product_variant2.value', 'product_variant3.value', 'product_variant4.value')
                            ->havingRaw('(SUM(inventory.quantity) = 0 OR SUM(inventory.quantity) IS NULL)')
                            ->orderBy('product.sku')->orderBy('product_variant1.name')->orderBy('product_variant2.name')
                            ->orderBy('product_variant3.name')->orderBy('product_variant4.name')->orderBy('product_variant1.value')
                            ->orderBy('product_variant2.value')->orderBy('product_variant3.value')->orderBy('product_variant4.value');

        //get data with accounting for whether it needs to be paginated or not
        $data['body'] = $this->getResult($query, $paginate);

        return $data;
    }

    /**
     * This is for the Bin Location Detail report
     *
     * @param $request
     * @param $paginate
     *
     * @return mixed
     */
    public function binLocationDetail($request, $paginate)
    {
        /* VALIDATE DATA */

        //get data
        $query = Inventory::select( 'product.id AS product_id', 'product.sku', 'product.name',
                                    'bin.aisle', 'bin.section', 'bin.tier', 'bin.position',
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
                            ->where('product.active', '=', true)
                            ->where('product.warehouse_id', '=', $request->get('warehouse_id'))
                            ->where('product.client_id', '=', $request->get('client_id'))
                            ->groupBy('bin.id', 'bin.aisle', 'bin.section', 'bin.tier', 'bin.position',
                                'product.id', 'product.sku', 'product.name', 'product.active', 'product_variant1.value',
                                'product_variant2.value', 'product_variant3.value', 'product_variant4.value',
                                'product_type.uom1', 'product_type.uom2', 'product_type.uom3', 'product_type.uom4',
                                'product_type.uom5', 'product_type.uom6', 'product_type.uom7', 'product_type.uom8',
                                'product_variant1.name', 'product_variant2.name', 'product_variant3.name', 'product_variant4.name',
                                'product_variant1.value', 'product_variant2.value', 'product_variant3.value', 'product_variant4.value',
                                'product.uom1', 'product.uom2', 'product.uom3', 'product.uom4', 'product.uom5', 'product.uom6', 'product.uom7', 'product.uom8')
                            ->orderBy('product.sku')->orderBy('product.id')->orderBy('product_variant1.name')->orderBy('product_variant2.name')
                            ->orderBy('product_variant3.name')->orderBy('product_variant4.name')->orderBy('product_variant1.value')
                            ->orderBy('product_variant2.value')->orderBy('product_variant3.value')->orderBy('product_variant4.value')
                            ->orderBy('bin.aisle')->orderBy('bin.section')->orderBy('bin.tier')->orderBy('bin.position');

        //add the filters
        $this->addZeroQuantityFilter($query, $request->get('zero_inventory_items'));
        $this->addProductFilter($query, $request->get('product_id'));

        //get data with accounting for whether it needs to be paginated or not
        $data['body'] = $this->getResult($query, $paginate);

        //get total data
        $total = Inventory::select( DB::raw('lower(product_type.uom1) AS uom1'), DB::raw('lower(product_type.uom2) AS uom2'),
                                    DB::raw('lower(product_type.uom3) AS uom3'), DB::raw('lower(product_type.uom4) AS uom4'),
                                    DB::raw('lower(product_type.uom5) AS uom5'), DB::raw('lower(product_type.uom6) AS uom6'),
                                    DB::raw('lower(product_type.uom7) AS uom7'), DB::raw('lower(product_type.uom8) AS uom8'),
                                    DB::raw('SUM(inventory.quantity) AS quantity1'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 AS quantity2'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 AS quantity3'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 AS quantity4'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 / product.uom5 AS quantity5'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 / product.uom5 / product.uom6 AS quantity6'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 / product.uom5 / product.uom6 / product.uom7 AS quantity7'),
                                    DB::raw('SUM(inventory.quantity) / product.uom2 / product.uom3 / product.uom4 / product.uom5 / product.uom6 / product.uom7 / product.uom8 AS quantity8'))
                        ->rightJoin('bin', 'inventory.bin_id', '=', 'bin.id')
                        ->rightJoin('product', 'bin.product_id', '=', 'product.id')
                        ->join('product_type', 'product.product_type_id', '=', 'product_type.id')
                        ->where('product.active', '=', true)
                        ->where('product.warehouse_id', '=', $request->get('warehouse_id'))
                        ->where('product.client_id', '=', $request->get('client_id'))
                        ->groupBy('bin.product_id', 'product_type.uom1', 'product_type.uom2', 'product_type.uom3', 'product_type.uom4',
                            'product_type.uom5', 'product_type.uom6', 'product_type.uom7', 'product_type.uom8',
                            'product.uom1', 'product.uom2', 'product.uom3', 'product.uom4', 'product.uom5', 'product.uom6', 'product.uom7', 'product.uom8');

        //add the filters
        $this->addZeroQuantityFilter($total, $request->get('zero_inventory_items'));
        $this->addProductFilter($total, $request->get('product_id'));

        //calculate the totals
        $data['total'] = $this->getQuantityTotal($total->get());

        return $data;
    }

    /**
     * This is for the Transaction Chronological report
     *
     * @param $request
     * @param $paginate
     *
     * @return mixed
     */
    public function transactionChronological($request, $paginate)
    {
        //assign tx type
        $tx_type = $request->get('tx_type');

        //get the transaction classes from the transaction model
        $classes = Transaction::getClasses($tx_type);

        //get main class
        $tx_class = new $classes['transaction'];

        //define tables
        $tx_table = $tx_type;
        $tx_detail_table = $tx_type . '_detail';

        //get data
        $query = $tx_class::select( $tx_table . '.id AS tx_id', 'tx_date', 'po_number', 'product.id AS product_id',
                                    'product.sku', 'product.name', 'tx_status.name as tx_status_name',
                                    'product_variant1.name AS variant1_name', 'product_variant1.value AS variant1_value',
                                    'product_variant2.name AS variant2_name', 'product_variant2.value AS variant2_value',
                                    'product_variant3.name AS variant3_name', 'product_variant3.value AS variant3_value',
                                    'product_variant4.name AS variant4_name', 'product_variant4.value AS variant4_value',
                                    'uom_name', DB::raw('quantity / uom_multiplier AS quantity'))
                          ->join($tx_detail_table, $tx_table . '.id', '=', $tx_detail_table . '.' . $tx_table . '_id')
                          ->join('product', $tx_detail_table . '.product_id', '=', 'product.id')
                          ->join('tx_status', $tx_table . '.tx_status_id', '=', 'tx_status.id')
                          ->leftJoin('product_variant1', $tx_detail_table . '.variant1_id', '=', 'product_variant1.id')
                          ->leftJoin('product_variant2', $tx_detail_table . '.variant2_id', '=', 'product_variant2.id')
                          ->leftJoin('product_variant3', $tx_detail_table . '.variant3_id', '=', 'product_variant3.id')
                          ->leftJoin('product_variant4', $tx_detail_table . '.variant4_id', '=', 'product_variant4.id')
                          ->where($tx_table . '.warehouse_id', '=', $request->get('warehouse_id'))
                          ->where($tx_table . '.client_id', '=', $request->get('client_id'))
                          ->where($tx_table . '.tx_date', '>=', $request->get('from_date'))
                          ->where($tx_table . '.tx_date', '<=', $request->get('to_date'))
                          ->orderBy('tx_date')->orderBy('po_number');

        //add product filter
        $product_id = $request->get('product_id');
        if( $product_id != null && $product_id != 'undefined' && is_numeric($product_id) && intval($product_id) > 0 )
        {
            $query->where($tx_detail_table . '.product_id', '=', $product_id);
        }

        return ['body' => $this->getResult($query, $paginate)];
    }

    /**
     * This is for the CSR open report
     *
     * @param $request
     * @param $paginate
     *
     * @return mixed
     */
    public function csrOpen($request, $paginate)
    {
        /* VALIDATE DATA */

        //get data
        $query = csr::select('csr.id AS tx_id', 'client.short_name AS client_short_name', 'csr.tx_date', 'csr.po_number', 'tx_status.name AS tx_status_name')
                    ->join('client_warehouse', 'csr.client_id', '=', 'client_warehouse.client_id')
                    ->join('client', 'csr.client_id', '=', 'client.id')
                    ->join('tx_status', 'csr.tx_status_id', '=', 'tx_status.id')
                    ->where('csr.warehouse_id', '=', $request->get('warehouse_id'))
                    ->where('csr.tx_status_id', '=', TxStatus::active)
                    ->orderBy('client.short_name')->orderBy('csr.tx_date', 'DESC')->orderBy('csr.updated_at', 'DESC');

        //get data with accounting for whether it needs to be paginated or not
        $data['body'] = $this->getResult($query, $paginate);

        return $data;
    }

    /**
     * This is for the Shipping report
     *
     * @param $request
     * @param $paginate
     *
     * @return mixed
     */
    public function shipping($request, $paginate)
    {
        //get the clients
        $query = Client::select('client.id AS id', 'client.short_name AS client_short_name')
                         ->join('client_warehouse', 'client.id', '=', 'client_warehouse.client_id')
                         ->where('client_warehouse.warehouse_id', '=', $request->get('warehouse_id'))
            //->where('client.id', '=', 243)
                         ->orderBy('client.short_name');
        $clients = $this->getResult($query, $paginate);

        //get data for each client
        foreach( $clients as $client )
        {
            //get data
            $result = Ship::select(DB::raw('COUNT(ship.id) AS tx_count'), DB::raw("date_part('MONTH', ship.tx_date) AS month"),
                                   'ship_detail.uom_name', DB::raw('SUM(ship_detail.quantity) AS total'))
                           ->join('ship_detail', 'ship.id', '=', 'ship_detail.ship_id')
                           ->whereNull('ship_detail.deleted_at')
                           ->where('ship.client_id', '=', $client->id)
                           ->where('ship.warehouse_id', '=', $request->get('warehouse_id'))
                           ->groupBy(DB::raw("date_part('MONTH', ship.tx_date)"), 'ship_detail.uom_name', 'ship.id')
                           ->havingRaw('SUM(ship_detail.quantity) > 0')
                           ->get();

            //build the full yearly data
            $client->tx_data = $this->buildYearlyTxDataShip($result);
        }

        //assign return array
        $data['body'] = $clients;

        //get grand total
        $result = Ship::select(DB::raw('COUNT(ship.id) AS tx_count'), DB::raw("date_part('MONTH', ship.tx_date) AS month"),
                               'ship_detail.uom_name', DB::raw('SUM(ship_detail.quantity) AS total'))
                        ->join('ship_detail', 'ship.id', '=', 'ship_detail.ship_id')
                        ->whereNull('ship_detail.deleted_at')
                        ->where('ship.warehouse_id', '=', $request->get('warehouse_id'))
                        ->groupBy(DB::raw("date_part('MONTH', ship.tx_date)"), 'ship_detail.uom_name', 'ship.id')
                        ->havingRaw('SUM(ship_detail.quantity) > 0')
                        ->get();

        $data['total'] = $this->buildYearlyTxDataShip($result);

        return $data;
    }

    /**
     * This is for the Transaction Yearly report
     *
     * @param $request
     * @param $paginate
     *
     * @return mixed
     */
    public function transactionYearly($request, $paginate)
    {
        //get the products
        $query = Product::select('product.id AS product_id', 'product.sku', 'product.name AS product_name',
                                 'product_variant1.id AS variant1_id', 'product_variant2.id AS variant2_id',
                                 'product_variant3.id AS variant1_id', 'product_variant4.id AS variant2_id',
                                 'product_variant1.name AS variant1_name', 'product_variant1.value AS variant1_value',
                                 'product_variant2.name AS variant2_name', 'product_variant2.value AS variant2_value',
                                 'product_variant3.name AS variant3_name', 'product_variant3.value AS variant3_value',
                                 'product_variant4.name AS variant4_name', 'product_variant4.value AS variant4_value')
                        ->leftJoin('product_variant1', 'product.id', '=', 'product_variant1.product_id')
                        ->leftJoin('product_variant2', 'product.id', '=', 'product_variant2.product_id')
                        ->leftJoin('product_variant3', 'product.id', '=', 'product_variant3.product_id')
                        ->leftJoin('product_variant4', 'product.id', '=', 'product_variant4.product_id')
                        ->where('product.warehouse_id', '=', $request->get('warehouse_id'))
                        ->where('product.client_id', '=', $request->get('client_id'))
                        ->where('product.active', '=', true)
                        ->orderBy('product.sku')->orderBy('product_variant1.name')->orderBy('product_variant1.value')
                        ->orderBy('product_variant2.name')->orderBy('product_variant2.value')->orderBy('product_variant3.name')
                        ->orderBy('product_variant3.value')->orderBy('product_variant4.name')->orderBy('product_variant4.value');

        //add product filter if need be
        $product_id = $request->get('product_id');
        if( $product_id != null && $product_id != 'undefined' && is_numeric($product_id) && $product_id > 0 )
        {
            $query->where('product.id', '=', $product_id);
        }

        $products = $this->getResult($query, $paginate);

        //get data for each product
        foreach( $products as $product )
        {
            //get data
            $ship_units = Ship::select(DB::raw("date_part('MONTH', ship.tx_date) AS month"), 'ship_detail.uom_name',
                                       DB::raw('SUM(ship_detail.quantity) AS total'))
                              ->join('ship_detail', 'ship.id', '=', 'ship_detail.ship_id')
                              ->whereNull('ship_detail.deleted_at')
                              ->where('ship_detail.product_id', '=', $product->product_id)
                              ->where('ship_detail.variant1_id', '=', $product->variant1_id)
                              ->where('ship_detail.variant2_id', '=', $product->variant2_id)
                              ->where('ship_detail.variant3_id', '=', $product->variant3_id)
                              ->where('ship_detail.variant4_id', '=', $product->variant4_id)
                              ->groupBy(DB::raw("date_part('MONTH', ship.tx_date)"), 'ship_detail.uom_name')
                              ->havingRaw('SUM(ship_detail.quantity) > 0')
                              ->get();

            $receive_units = Receive::select(DB::raw("date_part('MONTH', receive.tx_date) AS month"), 'receive_detail.uom_name',
                                             DB::raw('SUM(receive_detail.quantity) AS total'))
                                    ->join('receive_detail', 'receive.id', '=', 'receive_detail.receive_id')
                                    ->whereNull('receive_detail.deleted_at')
                                    ->where('receive_detail.product_id', '=', $product->product_id)
                                    ->where('receive_detail.variant1_id', '=', $product->variant1_id)
                                    ->where('receive_detail.variant2_id', '=', $product->variant2_id)
                                    ->where('receive_detail.variant3_id', '=', $product->variant3_id)
                                    ->where('receive_detail.variant4_id', '=', $product->variant4_id)
                                    ->groupBy(DB::raw("date_part('MONTH', receive.tx_date)"), 'receive_detail.uom_name')
                                    ->havingRaw('SUM(receive_detail.quantity) > 0')
                                    ->get();

            //build the full yearly data
            $product->tx_data = $this->buildYearlyUnitData($receive_units, $ship_units);
        }

        //assign return array
        $data['body'] = $products;

        //get grand total
        $ship_units = Ship::select(DB::raw("date_part('MONTH', ship.tx_date) AS month"), 'ship_detail.uom_name',
                                   DB::raw('SUM(ship_detail.quantity) AS total'))
                          ->join('ship_detail', 'ship.id', '=', 'ship_detail.ship_id')
                          ->join('product', 'ship_detail.product_id', '=', 'product.id')
                          ->whereNull('ship_detail.deleted_at')
                          ->where('ship_detail.variant1_id', '=', $product->variant1_id)
                          ->where('ship_detail.variant2_id', '=', $product->variant2_id)
                          ->where('ship_detail.variant3_id', '=', $product->variant3_id)
                          ->where('ship_detail.variant4_id', '=', $product->variant4_id)
                          ->where('product.warehouse_id', '=', $request->get('warehouse_id'))
                          ->where('product.client_id', '=', $request->get('client_id'))
                          ->groupBy(DB::raw("date_part('MONTH', ship.tx_date)"), 'ship_detail.uom_name')
                          ->havingRaw('SUM(ship_detail.quantity) > 0');

        //add product filter if need to
        if( $product_id != null && $product_id != 'undefined' && is_numeric($product_id) && $product_id > 0 )
        {
            $ship_units->where('product.id', '=', $product_id);
        }

        $receive_units = Receive::select(DB::raw("date_part('MONTH', receive.tx_date) AS month"), 'receive_detail.uom_name',
                                         DB::raw('SUM(receive_detail.quantity) AS total'))
                                ->join('receive_detail', 'receive.id', '=', 'receive_detail.receive_id')
                                ->join('product', 'receive_detail.product_id', '=', 'product.id')
                                ->whereNull('receive_detail.deleted_at')
                                ->where('receive_detail.variant1_id', '=', $product->variant1_id)
                                ->where('receive_detail.variant2_id', '=', $product->variant2_id)
                                ->where('receive_detail.variant3_id', '=', $product->variant3_id)
                                ->where('receive_detail.variant4_id', '=', $product->variant4_id)
                                ->where('product.warehouse_id', '=', $request->get('warehouse_id'))
                                ->where('product.client_id', '=', $request->get('client_id'))
                                ->groupBy(DB::raw("date_part('MONTH', receive.tx_date)"), 'receive_detail.uom_name')
                                ->havingRaw('SUM(receive_detail.quantity) > 0');

        //add product filter if need to
        if( $product_id != null && $product_id != 'undefined' && is_numeric($product_id) && $product_id > 0 )
        {
            $receive_units->where('product.id', '=', $product_id);
        }

        $data['total'] = $this->buildYearlyUnitData($receive_units->get(), $ship_units->get());

        return $data;
    }

    /**
     * This builds the full year of transaction data
     *
     * @param $data
     *
     * @return array
     */
    protected function buildYearlyTxDataShip($data)
    {
        //we need to build the full data set by month - this is a performance and clarity decision.  It could be faster
        //to do it in the view as we don't have to go through the data again, but it is much messier to do it there. We
        //want the data set in a way that each month is in its own array with the tx count and all the uom's
        $tx_data = [];
        for( $i = 1; $i <= 12; $i++ )
        {
            //set tx count
            $tx_count = $data->where('month', (string)$i);

            $tx_data[$i]['tx_count'] = ( count($tx_count) > 0 ) ? $tx_count->sum('tx_count') : 0;

            //set quantities
            $tx_data[$i]['units'] = ( count($tx_count) > 0 ) ? $this->getUnitTotals($tx_count) : [];
        }

        return $tx_data;
    }

    /**
     * This builds the full year of transaction data for receiving and
     *
     * @param $data
     *
     * @return array
     */
    protected function buildYearlyUnitData($receive_data, $ship_data)
    {
        //we need to build the full data set by month - this is a performance and clarity decision.  It could be faster
        //to do it in the view as we don't have to go through the data again, but it is much messier to do it there. We
        //want the data set in a way that each month is in its own array with the tx count and all the uom's
        $tx_data = [];
        for( $i = 1; $i <= 12; $i++ )
        {
            //set receive quantities
            $receive_units = $receive_data->where('month', (string)$i);
            $tx_data[$i]['receive_units'] = ( count($receive_units) > 0 ) ? $this->getUnitTotals($receive_units) : [];

            //set ship quantities
            $ship_units = $ship_data->where('month', (string)$i);
            $tx_data[$i]['ship_units'] = ( count($ship_units) > 0 ) ? $this->getUnitTotals($ship_units) : [];
        }

        return $tx_data;
    }

    /**
     * This is used to sum up all the uom keys and totals
     *
     * @param $data
     *
     * @return array
     */
    protected function getUnitTotals($data)
    {
        //init return array
        $return = [];

        //loop through and add to array
        foreach( $data as $row )
        {
            //set key with uc words
            $key = ucwords($row->uom_name);

            //set key if not set
            if( !isset($return[$key]) ) { $return[$key] = 0; }

            //increment
            $return[$key] += $row->total;
        }

        return $return;
    }

    /**
     * This is broken out into a separate function so it can be used by all report queries
     *
     * @param $query
     * @param $paginate
     *
     * @return mixed
     */
    protected function getResult($query, $paginate)
    {
        //get the data with paginate
        if( $paginate === true )
        {
            return $query->paginate($this->paginate_num_items);
        }

        //don't paginate if we are using it for anything other than display
        else { return $query->get(); }
    }

    /**
     * The reason we are removing the page number query string value this way is that we don't want to
     * pop it off of the $_GET global as it would be a bad practice to do so.  It is better to create a copy
     * with only the values we want.  We also need to filter it to the correct data types to pass it back
     * as a JSON object and have it parse properly.
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
        if( isset($data['product_id']) )
        {
            $data['product_id'] = ( $data['product_id'] == 'null' ||
                                        !is_numeric($data['product_id']) ||
                                            intval($data['product_id']) == 0 ) ? 'null' : intval($data['product_id']);
        }


        //convert boolean
        if( isset($data['zero_inventory_items']) )
        {
            $data['zero_inventory_items'] = filter_var($data['zero_inventory_items'], FILTER_VALIDATE_BOOLEAN);
        }

        return $data;
    }

    /**
     * This returns the report criteria converted to display friendly format.
     *
     * @param $items
     *
     * @return array
     */
    public function getCriteriaDisplay($items)
    {
        //set return array
        $data = [];

        //remove name from the list
        unset($items['name']);

        //loop through all the items and change the key to display friendly format as well as the values
        foreach( $items as $key => $value )
        {
            switch( $key )
            {
                //warehouse id
                case 'warehouse_id':
                    $name = Warehouse::where('id', '=', $value)->pluck('name');
                    $data['Warehouse'] = $name;
                    break;

                //client id
                case 'client_id':
                    $name = Client::where('id', '=', $value)->pluck('name');
                    $data['Client'] = $name;
                    break;

                //product id
                case 'product_id':
                    //if product was selected
                    if( $value != null && $value != 'undefined' && is_numeric($value) && $value > 0 )
                    {
                        $sku = Product::where('id', '=', $value)->pluck('sku');
                        $data['Product SKU'] = $sku;
                    }

                    //since no product was selected, all were returned
                    else { $data['Product SKU'] = 'All'; }
                    break;

                //zero inventory items toggle
                case 'zero_inventory_items':
                    $data['Show Zero Inventory Items'] = ( $key == 'true' ) ? 'Yes' : 'No';
                    break;

                //transaction type - NEED TO FIX
                case 'tx_type':
                    $data['Transaction Type'] = $value;//constant('TransactionTypeName::' . $value);
                    break;

                //from date
                case 'from_date':
                    $data['From Date'] = $this->reformatDate($value);
                    break;

                //to date
                case 'to_date':
                    $data['To Date'] = $this->reformatDate($value);
                    break;

                //as of date
                case 'as_of_date':
                    $data['As of Date'] = $this->reformatDate($value);
                    break;

                //for all unfound cases, just pass through the key and value
                default:
                    $data[ucwords(str_replace('_', ' ', $key))] = $value;
            }
        }

        return $data;
    }

    /**
     * Returns the UOM name and the total for each UOM by name and ordered by UOM levels
     *
     * @param $data
     *
     * @return array
     */
    public function getQuantityTotal($data)
    {
        //get uom names
        $uom_data = $this->getUomList($data);

        //find the uom and add to total
        for( $i = 1; $i <= 8; $i++ )
        {
            foreach( $uom_data as $key => $value )
            {
                //get all the items matching the uom key for this uom
                $result = $data->where('uom' . $i, $key);

                //if we have items, then sum and total it
                if( count($result) > 0 )
                {
                    $uom_data[$key] += ceil($result->sum('quantity' . $i));
                }
            }
        }

        return $uom_data;
    }

    /**
     * Returns a list of all the UOM's used in an array with the value of 0
     *
     * @param $data
     *
     * @return array
     */
    public function getUomList($data)
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
            foreach( $result as $row )
            {
                //only add if it does not already exist
                if( $row[$column] != null && array_key_exists($row[$column], $uom_list) === false )
                {
                    $uom_list[$row[$column]] = 0;
                }
            }
        }

        return $uom_list;
    }

    /* -----------------------------------------------------------------------------------------------------------------
        We are not going to use this version for now. This version collects totals by uom, then adds all possible names.
        I think it makes more sense to do it the other way where we find all uom of the same name, then total it.

    public function getQuantityTotal($data)
    {
        //initialize return array
        $result = [];

        //get uom names
        $uom_names = $this->getUomData($data);

        //loop through and find all items with this uom

        //calculate each total and add names
        for( $i = 1; $i <= 8; $i++ )
        {
            //make sure the UOM exists
            if( isset($uom_names[$i]) )
            {
                $result[] = ['name' => $uom_names[$i],
                             'quantity' => $data->sum('quantity' . $i)];
            }

            //since the UOM doesn't exist, it means no more will exist in the list so we can just break out of the loop
            else { break; }
        }

        return $result;
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
                if( count($uoms) > 0 ) { $uom_list[$i] = $uoms; }
            }
        }
        return $uom_list;
    }
    ------------------------------------------------------------------------------  */


    /**
     * Adds the zero product quantity filter if needed.  This is broken out so it can be used by multiple report data
     * generation methods.
     *
     * @param $data
     * @param $request
     */
    protected function addZeroQuantityFilter(&$data, $zero_inventory_items)
    {
        //add filter for zero quantities
        if( filter_var($zero_inventory_items, FILTER_VALIDATE_BOOLEAN) !== true)
        {
            $data->havingRaw('SUM(inventory.quantity) > 0');
        }
    }

    /**
     * Add the product filter if needed.
     *
     * @param $data
     * @param $product_id
     */
    protected function addProductFilter(&$data, $product_id)
    {
        //if we have valid product id, then filter by it
        if( $product_id != null && $product_id != 'undefined' && is_numeric($product_id) && $product_id > 0 )
        {
            $data->where('bin.product_id', '=', $product_id);
        }
    }

    /**
     * Take the YYYY-mm-dd and convert to mm-dd-YYYY
     *
     * @param $date
     *
     * @return string
     */
    public function reformatDate($date)
    {
        $date_array = explode('-', $date);

        return $date_array[1] . '-' . $date_array[2] . '-' . $date_array[0];
    }
}