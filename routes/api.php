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
Route::post('reset_password', 'API\CustomerController@resetPassword');
Route::resource('fooditems', 'API\FoodItemController');
Route::get('recipes/list', 'API\RecipeController@list');
Route::resource('recipes', 'API\RecipeController');
Route::resource('categories', 'API\CategoryController');
Route::resource('food_categories', 'API\FoodCategoryController');
Route::resource('sports', 'API\SportController');

Route::middleware('auth:api')->group(function () {
    Route::get('profile', 'API\CustomerController@profile');
    Route::get('check_available', 'API\CustomerController@checkAvailable');
    Route::post('purchase_membership', 'API\CustomerController@purchaseMembership');
    Route::resource('settings', 'API\SettingController');
    Route::get('informations/getInformation', 'API\CustomerInformationController@getInformation');
    Route::get('informations/get_graph_values', 'API\CustomerInformationController@getGraphValues');
    Route::post('informations/save_dietmode', 'API\CustomerInformationController@saveDietmode');
    Route::post('informations/save_weight', 'API\CustomerInformationController@saveWeight');
    Route::post('informations/save_water', 'API\CustomerInformationController@saveWater');
    Route::resource('informations', 'API\CustomerInformationController');
    Route::get('meals/list', 'API\MealController@list');
    Route::resource('meals', 'API\MealController');
});
