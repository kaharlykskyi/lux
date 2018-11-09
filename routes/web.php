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
    Route::post('/change-user-info', 'ProfileController@changePassword')->name('change_user_info');
});