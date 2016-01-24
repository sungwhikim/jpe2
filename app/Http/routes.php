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

Route::get('/test', 'WarehouseController@getList');
Route::get('/test/add', 'WarehouseController@addTestItem');
Route::get('/test/delete/{id}', 'WarehouseController@deleteItem');
Route::get('/test/addCountry', 'CountryController@addItem');
Route::get('/test/deleteCountry', 'CountryController@deleteItem');
Route::controller('/test/country', 'CountryController');


Route::get('/', function()
{
    return view('welcome');
});

/* WAREHOUSES */
Route::controller('/warehouse', 'WarehouseController');
Route::get('/warehouses', 'WarehouseController@getList');
Route::get('/warehouses/{filer}/{value}', 'WarehouseController@getList');
Route::get('/warehouse/{id}', 'WarehouseController@getItem');

/* COUNTRIES */
Route::controller('/country', 'CountryController');
Route::get('/countries', 'CountryController@getList');

/* PROVINCES/STATES */