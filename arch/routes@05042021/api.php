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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::group(['middleware' => 'auth:api'], function () {
	Route::post('details', 'API\UserController@details');
	Route::get('changePassword', 'API\UserController@changePassword');
	Route::get('my_reward', 'API\UserController@my_reward');
	Route::post('acceptReward', 'API\UserController@acceptReward');
	Route::get('my_commission', 'API\UserController@my_commission');
	Route::get('myEmi', 'API\UserController@myEmi');
	Route::get('transaction_hist', 'API\UserController@transaction_hist');
});
