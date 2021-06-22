<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Example Routes
Route::get('/home', function () {
    return redirect('/');
});
Route::view('/', 'landing');
Route::match(['get', 'post'], '/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Auth::routes();

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::resource('categories', 'CategoryController')->middleware('auth');
Route::resource('foodcategories', 'FoodCategoryController')->middleware('auth');
Route::resource('fooditems', 'FoodItemController')->middleware('auth');
Route::resource('recipes', 'RecipeController')->middleware('auth');
Route::resource('sports', 'SportController')->middleware('auth');
Route::resource('customers', 'CustomerController')->middleware('auth');
