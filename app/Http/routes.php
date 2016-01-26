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

/* WAREHOUSES */
Route::controller('/warehouse', 'WarehouseController');
Route::get('/warehouses', 'WarehouseController@getList');

/* COUNTRIES */
Route::controller('/country', 'CountryController');
Route::get('/countries', 'CountryController@getList');

/* PROVINCES/STATES */