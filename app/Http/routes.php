<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*Route::get('/', function()
{
    return view('welcome');
});*/

/* HOME PAGE AND ROUTE TO LOGIN/LOGOUT PAGES */
Route::get('/', 'Auth\AuthController@getIndex');
Route::get('/login', 'Auth\AuthController@getIndex');
Route::get('/home', 'UserController@getDashboard');
Route::get('/dashboard', 'UserController@getDashboard');
Route::post('/login', 'Auth\AuthController@postLogin');
Route::get('/logout', 'Auth\AuthController@getLogout');

/* PASSWORD RESET ROUTES */
// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');
// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

/* USER */
Route::put('/user/update-warehouse-client/{warehouse_id}/{client_id}', 'UserController@setWarehouseClient');
Route::get('/users', 'UserController@getListView');
Route::controller('/user', 'UserController');

/* USER GROUP */
Route::controller('/user-group', 'UserGroupController');
Route::get('/user-groups', 'UserGroupController@getListView');

/* USER FUNCTION */
Route::controller('/user-function', 'UserFunctionController');
Route::get('/user-functions', 'UserFunctionController@getListView');

/* USER FUNCTION CATEGORY */
Route::controller('/user-function-category', 'UserFunctionCategoryController');
Route::get('/user-function-categories', 'UserFunctionCategoryController@getListView');

/* CUSTOMER */
Route::controller('/customer', 'CustomerController');
Route::get('/customers', 'CustomerController@getListView');

/* WAREHOUSE */
Route::controller('/warehouse', 'WarehouseController');
Route::get('/warehouses', 'WarehouseController@getListView');

/* COUNTRY */
Route::controller('/country', 'CountryController');
Route::get('/countries', 'CountryController@getListView');

/* COMPANY */
Route::controller('/company', 'CompanyController');
Route::get('/companies', 'CompanyController@getListView');

/* PROVINCE/STATE */
Route::controller('/province', 'ProvinceController');
Route::get('/provinces', 'ProvinceController@getListView');

/* CLIENT */
Route::controller('/client', 'ClientController');
Route::get('/clients', 'ClientController@getListView');

/* CARRIER */
Route::get('/carrier/list-by-wc/{type}', 'CarrierController@getListByUserClientWarehouse');
Route::controller('/carrier', 'CarrierController');
Route::get('/carriers', 'CarrierController@getListView');

/* PRODUCT */
Route::get('/products', 'ProductController@getListView');
Route::get('/product/tx-detail/{product_id}/{get_inventory}', 'ProductController@getTxDetail');
Route::controller('/product', 'ProductController');

/* PRODUCT TYPE */
Route::controller('/product-type', 'ProductTypeController');
Route::get('/product-types', 'ProductTypeController@getListView');

/* INVENTORY */
Route::get('/inventory/product-list', 'InventoryController@getProductList');
Route::get('/inventory/product-inventory/{product_id}', 'InventoryController@getProductInventory');
Route::post('/inventory/new-bin', 'BinController@postNew');
Route::put('/inventory/bin/delete/{id}', 'BinController@putDelete');
Route::controller('/inventory', 'InventoryController');

/* COMMON TO ALL TRANSACTIONS */
Route::get('/transaction/product-list', 'TransactionController@getUserProductList');
Route::post('/transaction/check-po-number/{transaction_type}', 'TransactionController@checkPoNumber');

/* TRANSACTION FINDER */
Route::get('/transaction-finder', 'TransactionFinderController@getTransactionFinder');
Route::get('/transaction/find-type/{tx_type}', 'TransactionFinderController@getTxFinderData');

/* ASN - RECEIVE */
Route::get('/transaction/asn/receive/new', 'AsnReceiveController@getNew');
Route::get('/transaction/asn/receive/{tx_id}', 'AsnReceiveController@getIndex');
Route::controller('/transaction/asn/receive', 'AsnReceiveController');

/* ASN - SHIP */
//Route::controller('/transaction/asn/ship', 'AsnShipController');

/* RECEIVE */
Route::get('/transaction/receive/new', 'ReceiveController@getNew');
Route::get('/transaction/receive/{tx_id}', 'ReceiveController@getIndex');
Route::controller('/transaction/receive', 'ReceiveController');

/* SHIP */
//Route::controller('/transaction/ship', 'ShipController');