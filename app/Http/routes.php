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

// Registration routes for testing data only
Route::get('/auth/register', 'Auth\AuthController@getRegister');
Route::post('/auth/register', 'Auth\AuthController@postRegister');

/* HOME PAGE AND ROUTE TO LOGIN/LOGOUT PAGES */
Route::get('/', 'Auth\AuthController@getIndex');
Route::get('/home', 'UserController@getDashboard');
Route::get('/dashboard', 'UserController@getDashboard');
Route::get('/login', 'Auth\AuthController@getIndex');
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
Route::controller('/user', 'UserController');
Route::get('/users', 'UserController@getList');

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