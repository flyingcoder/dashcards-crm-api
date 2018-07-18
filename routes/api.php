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

Route::group(['middleware' => 'auth:api', 'prefix' => 'activities'], function () {
  Route::get('/', 'ActivityController@index');
});

Route::post('login', 'Auth\ApiLoginController@login');

//company
Route::group(['middleware' => 'auth:api', 'prefix' => 'company'], function () {
  Route::get('members', 'CompanyController@members');
  Route::get('teams', 'CompanyController@teams');
  Route::get('teams/{id}', 'CompanyController@member');
  Route::delete('teams/{id}', 'TeamController@delete');
  Route::post('teams', 'TeamController@store');
  Route::put('teams/{id}', 'TeamController@update');

});

Route::group(['middleware' => ['api', 'cors'], 'prefix' => 'register'], function () {

  Route::post('/', 'Auth\ApiRegisterController@create');

});

//events
Route::group(['middleware' => 'auth:api', 'prefix' => 'events'], function () {
  
  Route::get('/', 'EventController@index');
  Route::get('{id}/delete', 'EventController@delete');

});

//calendars
Route::group(['middleware' => 'auth:api', 'prefix' => 'calendars'], function () {

  Route::get('/', 'CalendarController@index');

  Route::post('/', 'CalendarController@store');

  Route::get('{id}', 'CalendarController@calendar');

  Route::get('{id}/events', 'CalendarController@events');

  Route::post('{id}/events', 'EventController@index');
  
}); 

//dashitems
Route::group(['middleware' => 'auth:api', 'prefix' => 'dashitems'], function () {
  
  Route::get('/', 'DashitemController@index'); // dashboard/index

  Route::put('{dashboard_id}/order', 'DashitemController@changeOrder');

  Route::put('{dashboard_id}/visibility', 'DashitemController@visibility');

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

  Route::post('/', 'Auth\RegisterController@create');

  Route::get('/company/{key}', 'UserController@getMeta');

  Route::post('/company/details', 'UserController@addCompanyDetails');

  Route::post('/company/invoice-settings', 'UserController@addInvoiceSettings');

  Route::post('/company/invoice-settings', 'UserController@addInvoiceSettings');

  Route::post('/company/bank-transfer-details', 'UserController@addBankTransferDetails');

  Route::post('/company/paypal-details', 'UserController@addPaypalDetails');

  Route::get('/tasks', 'UserController@tasks');

  Route::get('/tasks/count', 'UserController@countTasks');

  Route::get('/projects', 'UserController@projects');

  Route::get('/clients', 'UserController@clients');

  Route::get('/notifications', 'NotificationController@unread');

  Route::get('/notifications/count', 'NotificationController@unreadcount');

  Route::put('/notifications/{id}', 'NotificationController@markRead');
  
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


// Milestone
Route::group(['middleware' => 'auth:api', 'prefix' => 'milestone'], function () {

  /* alvin added */
  Route::get('{id}/tasks', 'MilestoneController@tasks');

  Route::post('{id}/tasks', 'MilestoneController@addTasks');

  Route::get('{id}', 'MilestoneController@milestone');

  Route::delete('{id}', 'MilestoneController@delete');


  /* next to useless routes */
  Route::post('/', 'MilestoneTemplateController@store');

  Route::put('{id}', 'MilestoneTemplateController@update');

  Route::get('all', 'MilestoneTemplateController@all');

  Route::post('{id}/import', 'MilestoneTemplateController@replicate');

  Route::group(['prefix' => 'mlt-milestone'], function (){

    Route::get('{id}', 'MltMilestoneController@index');

    Route::get('{id}/edit', 'MltMilestoneController@edit');

    Route::get('{id}/all', 'MltMilestoneController@all');

    Route::post('{id}', 'MltMilestoneController@store');

    Route::put('{id}', 'MltMilestoneController@update');

    Route::delete('{id}', 'MltMilestoneController@destroy');

  });

});

// Services
Route::group(['middleware' => 'auth:api', 'prefix' => 'services'], function () {

	Route::get('/', 'ServiceController@index'); // projects //error

  Route::post('/', 'ServiceController@store');

  Route::post('validate', 'ServiceController@isValid');

  Route::get('{id}', 'ServiceController@getService');

  Route::put('{id}', 'ServiceController@update');

  Route::delete('{id}', 'ServiceController@delete');

});

// Projects
Route::group(['middleware' => 'auth:api', 'prefix' => 'projects'], function () {

  Route::get('/', 'ProjectController@index');// project
    
  Route::delete('{id}/delete', 'ProjectController@delete');

  Route::get('{id}', 'ProjectController@project');

  Route::post('{id}/milestone-import', 'ProjectController@milestoneImport');

  Route::get('{id}/tasks', 'ProjectController@tasks');// project-hq

  Route::post('/', 'ProjectController@store');

  Route::put('{id}', 'ProjectController@update'); //no more edit

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
  Route::get('{id}/members-all', 'ProjectController@membersAll');// project-hq
  
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

  Route::get('{id}', 'ClientController@client'); // project

  Route::post('{id}/image', 'ClientController@updatePicture');
  
  Route::post('/', 'ClientController@store'); // client add
  
  Route::put('/{id}', 'ClientController@update'); // client update

  Route::delete('{id}', 'ClientController@delete');

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
Route::group(['middleware' => 'auth:api', 'prefix' => 'project-milestones'], function () {

  Route::get('{id}', 'MilestoneController@index');

  Route::post('{id}', 'MilestoneController@store');
  
  Route::get('select/{id}', 'MilestoneController@selectMilestone');
  
});

// Forms
Route::group(['middleware' => 'auth:api', 'prefix' => 'forms'], function () {
  
  Route::get('/', 'FormController@index');

});
