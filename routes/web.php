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

Route::get('/', function () {
    return view('welcome');
});

// Views
Route::view('dashboard', 'pages.dashboard')->name('dashboard');
Route::view('projects', 'pages.projects')->name('projects');
Route::view('clients', 'pages.clients')->name('clients');
