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

//events
Route::group(['middleware' => 'auth:api', 'prefix' => 'events'], function () {
  
  Route::get('/', 'EventController@index');

});

//calendars
Route::group(['middleware' => 'auth:api', 'prefix' => 'calendars'], function () {

  Route::get('/', 'CalendarController@index');

  Route::post('/', 'CalendarController@store');

  Route::get('{id}', 'CalendarController@calendar');

  Route::get('{id}/events', 'CalendarController@events');
  
}); 

//dashitems
Route::group(['middleware' => 'auth:api', 'prefix' => 'dashitems'], function () {
  
  Route::get('/', 'DashitemController@index'); // dashboard/index

  Route::put('{dashboard_id}/order', 'DashitemController@changeOrder');

});

//dashboard
Route::group(['middleware' => 'auth:api', 'prefix' => 'dashboard'], function () {

  Route::get('counts', 'DashboardController@counts'); // template

  Route::get('default/dashitems', 'DashboardController@defaultDashitems'); // template

  Route::post('default/dashitems', 'DashboardController@addDashitems'); // template

  Route::delete('default/dashitems', 'DashboardController@hideAllDashitem'); // template

  Route::delete('default/dashitems/{id}', 'DashboardController@hideDashitem'); 

  Route::get('{id}/dashitems', 'DashboardController@dashitems'); 

});

//users
Route::group(['middleware' => 'auth:api', 'prefix' => 'user'], function () {

  Route::get('/', 'UserController@user');

  Route::get('/tasks', 'UserController@tasks');

  Route::get('/tasks/count', 'UserController@countTasks');

  Route::get('/projects', 'UserController@projects');

  Route::get('/clients', 'UserController@clients');
  
});

//timer
Route::group(['middleware' => 'auth:api', 'prefix' => 'timer'], function () {

  Route::post('task/{action}', 'TimerController@taskTimer');

  Route::post('{action}', 'TimerController@timer');

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

	Route::get('/', 'ServiceController@index'); // projects //error

  Route::post('/', 'ServiceController@store');

  Route::get('{id}', 'ServiceController@service');

  Route::put('{id}', 'ServiceController@update');

  Route::delete('{id}', 'ServiceController@delete');

});

// Projects
Route::group(['middleware' => 'auth:api', 'prefix' => 'projects'], function () {

  Route::get('/', 'ProjectController@index');// project
    
  Route::delete('{id}/delete', 'ProjectController@delete');

  Route::get('{id}', 'ProjectController@project');

  Route::get('{id}/tasks', 'ProjectController@tasks');// project-hq

  Route::post('/', 'ProjectController@store');

  Route::put('{id}/edit', 'ProjectController@update');

  Route::post('{id}/comments', 'ProjectController@addComments');

  Route::get('{id}/comments', 'ProjectController@comments');

	Route::put('{id}/status', 'ProjectController@updateStatus');

  Route::get('{id}/tasks/mine', 'ProjectController@myTasks');// project-hq

  // not used
  // Route::get('{id}/overview', 'ProjectController@overview'); 

  Route::get('count', 'ProjectController@countProject');

  Route::get('{id}/timer', 'ProjectController@timer');

  Route::get('{id}/milestones', 'MilestoneController@projectMilestone');

  Route::get('{id}/members', 'ProjectController@members');// project-hq
  
  Route::get('{id}/files-count', 'ProjectController@filesCount');

  Route::get('{id}/files', 'MediaController@projectMedia');// project-hq
	Route::post('{id}/files','MediaController@projectFileUpload');// project-hq

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

  Route::get('{id}/comments', 'TaskController@comments');

  Route::post('{id}/comments', 'TaskController@addComments');

});

// Forms
Route::group(['middleware' => 'auth:api', 'prefix' => 'forms'], function () {

    Route::get('/', 'FormController@index');

    Route::post('/', 'FormController@store');

});

// Clients
Route::group(['middleware' => 'auth:api', 'prefix' => 'clients'], function () {

	Route::get('/', 'ClientController@index'); // project

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
