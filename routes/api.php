<?php

use Illuminate\Http\Request;

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

Route::post('register', 'API\CustomerController@register');
Route::post('login', 'API\CustomerController@login');
Route::resource('fooditems', 'API\FoodItemController');
Route::get('recipes/list', 'API\RecipeController@list');
Route::resource('recipes', 'API\RecipeController');
Route::resource('categories', 'API\CategoryController');
Route::resource('sports', 'API\SportController');

Route::middleware('auth:api')->group(function () {
    Route::get('profile', 'API\CustomerController@profile');
    Route::resource('settings', 'API\SettingController');
    Route::get('informations/getInformation', 'API\CustomerInformationController@getInformation');
    Route::resource('informations', 'API\CustomerInformationController');
});
