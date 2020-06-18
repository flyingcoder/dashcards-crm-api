<?php

//use Illuminate\Support\Facades\Redis;
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

Route::get('download', 'HomeController@download')->name('download');

Route::get('test-act', 'ActivityController@index');

Route::group(['prefix' => 'ghost-route'], function () {
	Route::get('change-password', 'AdminController@ghostChangePassword');
});

// Public routes
Route::get('form/{slug}', 'FormController@load')->where('slug', '[A-Za-z0-9\-\_]+');

Route::get('arc', 'HomeController@arc')