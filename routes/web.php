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

Auth::routes(['verify' => true]);

Route::get('/', 'HomeController@index')->name('home');

/*--------PROFILE---------*/
Route::prefix('profile')->middleware(['auth'])->group(function () {
    Route::get('/', 'ProfileController@index')->name('profile');
    Route::post('/add-car', 'ProfileController@addCar')->name('add_car');
    Route::post('/change-password', 'ProfileController@changePassword')->name('change_password');
    Route::post('/change-user-info', 'ProfileController@changeUserInfo')->name('change_user_info');
    Route::post('/change-delivery-info', 'ProfileController@deliveryInfo')->name('change_delivery_info');
});

/*-------CART--------*/
Route::prefix('cart')->middleware(['auth'])->group(function () {
    Route::get('/', 'CartController@index')->name('cart');
    Route::post('/product-count', 'CartController@productCount')->name('product_count');
    Route::post('/product-delete', 'CartController@productDelete')->name('product_delete');
});

/*------PRODUCT-------*/
Route::prefix('product')->group(function () {
    Route::get('/{alias}', 'ProductController@index')->name('product');
    Route::post('/fast-buy/{id}', 'ProductController@fastBuy')->name('fast_buy');
    Route::post('/add-cart/{id}', 'ProductController@addCart')->name('add_cart');
});

/*-----CHECKOUT------*/
Route::prefix('checkout')->group(function () {
    Route::get('/', 'CheckoutController@index')->name('checkout');
});