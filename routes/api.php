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

Route::get('activities', 'ActivityController@index');

Route::group(['middleware' => 'auth:api', 'prefix' => 'user'], function () {

  Route::get('/', 'UserController@user');

  Route::get('/tasks', 'UserController@tasks');

  Route::get('/tasks/count', 'UserController@countTasks');
  
});

// Templates
Route::group(['middleware' => 'auth:api', 'prefix' => 'template'], function () {

  Route::get('/', 'TemplateController@index');

  Route::post('/', 'TemplateController@store');

  Route::get('{id}', 'TemplateController@template');

  Route::put('{id}', 'TemplateController@update');

  Route::delete('{id}', 'TemplateController@delete');

  Route::post('/{id}/milestone', 'TemplateController@saveMilestone');

  Route::get('/{id}/milestone', 'TemplateController@milestone');

});

// Services
Route::group(['middleware' => 'auth:api', 'prefix' => 'services'], function () {

	Route::get('/', 'ServiceController@index');

  Route::post('/', 'ServiceController@store');

  Route::get('{id}', 'ServiceController@service');

  Route::put('{id}', 'ServiceController@update');

  Route::delete('{id}', 'ServiceController@delete');

});

// Projects
Route::group(['middleware' => 'auth:api', 'prefix' => 'projects'], function () {

  Route::get('/', 'ProjectController@index');

  Route::get('personal', 'ProjectController@myProjects');

  Route::get('count', 'ProjectController@countProject');

  Route::get('{id}', 'ProjectController@project');

  Route::delete('{id}/delete', 'ProjectController@delete');

  Route::get('{id}/tasks', 'ProjectController@tasks');

  Route::post('new', 'ProjectController@store');
	Route::post('{id}/edit', 'ProjectController@update');


  // not used
  // Route::get('{id}/overview', 'ProjectController@overview'); 

  Route::get('{id}/milestones', 'MilestoneController@projectMilestone');
  
  Route::get('{id}/invoices', 'ProjectController@invoices');

  Route::get('{id}/members', 'ProjectController@invoices');
  
  Route::get('{id}/files-count', 'ProjectController@filesCount');

  Route::get('{id}/files', 'MediaController@projectMedia');

  Route::post('{id}/links', 'MediaController@addMediaLink');

  Route::get('{id}/files/grid', 'MediaController@projectMediaAll');

  Route::get('{id}/timeline', 'ActivityController@project');

});


// Tasks
Route::group(['middleware' => 'auth:api', 'prefix' => 'tasks'], function () {
  
  Route::get('/', 'TaskController@index');

  Route::post('/', 'TaskController@store');

  Route::get('statistics/{id}', 'TaskController@stats');

  Route::get('{id}', 'TaskController@task');

  Route::delete('{id}', 'TaskController@delete');

  Route::put('{id}', 'TaskController@update');

});

// Forms
Route::group(['middleware' => 'auth:api', 'prefix' => 'forms'], function () {

    Route::get('/', 'FormController@index');

    Route::post('/', 'FormController@store');

});

// Clients
Route::group(['middleware' => 'auth:api', 'prefix' => 'clients'], function () {

	Route::get('/', 'ClientController@index');

  Route::delete('{id}/delete', 'ClientController@delete');

});

// Groups
Route::group(['middleware' => 'auth:api', 'prefix' => 'groups'], function () {

	Route::get('/', 'TeamController@groups');

	Route::post('/', 'GroupController@store');

  Route::get('roles', 'TeamController@role');

  Route::get('{id}/permissions', 'PermissionController@rolePermissions');

  Route::get('{id}', 'TeamController@editgroup');

  Route::get('{id}/members', 'GroupController@members');

  Route::put('{id}', 'TeamController@updategroup');

  Route::delete('{id}/delete', 'TeamController@deletegroup');
});

// Invoices
Route::group(['middleware' => 'auth:api', 'prefix' => 'invoices'], function () {

	Route::get('/', 'InvoiceController@index');

  Route::post('/', 'InvoiceController@store');

  Route::get('{id}', 'InvoiceController@invoice');

  Route::put('{id}', 'InvoiceController@update');

  Route::delete('{id}', 'InvoiceController@delete');
});

// Milestones
Route::group(['middleware' => 'auth:api', 'prefix' => 'milestones'], function () {

  Route::post('{id}', 'MilestoneController@store');
  Route::get('select/{id}', 'MilestoneController@selectMilestone');
  
});

// Forms
Route::group(['middleware' => 'auth:api', 'prefix' => 'forms'], function () {
  
  Route::get('/', 'FormController@index');

});
