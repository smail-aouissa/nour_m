<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/layout', 'HomeController');
Route::get('/home', 'HomeController@index');


Route::get('/categories', 'CategoryController');
Route::post('/category/{id}', 'CategoryController@show');

Route::get('/collections', 'CollectionController');
Route::post('/collection/{id}', 'CollectionController@show');

Route::get('/product/{id}', 'ProductController@show');


Route::get('/provinces', 'ProvinceController');
Route::get('/province/{id}', 'ProvinceController@show');

Route::post('/order', 'OrderController@store');
