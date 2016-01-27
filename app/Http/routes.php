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

Route::get('/', function()
{
    return view('welcome');
});

/* CUSTOMER */
Route::controller('/customer', 'CustomerController');
Route::get('/customers', 'CustomerController@getList');

/* WAREHOUSE */
Route::controller('/warehouse', 'WarehouseController');
Route::get('/warehouses', 'WarehouseController@getList');

/* COUNTRY */
Route::controller('/country', 'CountryController');
Route::get('/countries', 'CountryController@getList');

/* PROVINCE/STATE */