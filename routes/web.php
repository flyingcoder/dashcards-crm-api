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

/*
Route::get('/', function () {
	//Cache::put('foo', 'bar', 10);
	//return Cache::get('foo');
	return view('welcome');
});
// sidebar pages
Route::view('bugs', 'pages.bugs')->name('bugs');
Route::view('calendar', 'pages.calendar')->name('calendar');
Route::view('templates', 'pages.templates')->name('templates');
Route::view('milestones', 'pages.milestones')->name('milestones');
Route::view('buzz-forms', 'pages.forms')->name('buzz-forms');
Route::view('invoices', 'pages.invoices')->name('invoices');
Route::view('payments', 'pages.payments')->name('payments');
Route::view('timers', 'pages.timers')->name('timers');
Route::view('cloud', 'pages.cloud')->name('cloud');
Route::view('teams', 'pages.teams')->name('teams')->middleware('auth');
Route::view('chat', 'pages.chat')->name('chat');
Route::view('reports', 'pages.reports')->name('reports');
Route::view('supports', 'pages.supports')->name('supports');
Route::view('bugs', 'pages.bugs')->name('bugs');
Route::view('services', 'pages.services')->name('services');

Route::view('settings', 'pages.settings')->name('settings');
Route::view('project-details', 'pages.project-details')->name('project-details');

Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');

Route::get('project-details', 'TemplateController@projectDetails')->name('project-details');

// Groups
Route::prefix('groups')->group(function () {
	Route::get('/', 'TeamController@groups')->name('groups');
});

// Templates
Route::group(['middleware' => 'auth', 'prefix' => 'template'], function () {
	Route::get('{id}/milestones', 'TemplateController@milestonesTask')->name('milestones-task');
	//Route::get('{id}/milestones', 'TemplateController@milestones')->name('milestones-task');
});

// Services
// Route::group(['middleware' => 'auth', 'prefix' => 'services'], function () {
// 	Route::get('/', 'ServiceController@index')->name('services');
// 	Route::get('{id}', 'ServiceController@index')->name('service');
// 	Route::get('new', 'ServiceController@save')->name('new-service');
// });

// Clients
Route::group(['middleware' => 'auth', 'prefix' => 'clients'], function () {
	Route::get('/', 'ClientController@index')->name('clients');
	Route::get('new', 'ClientController@save')->name('new-client');
	Route::post('new', 'ClientController@store')->name('store-client');
	Route::get('{id}', 'ClientController@details')->name('client');
	Route::get('{id}/edit', 'ClientController@edit')->name('edit-client');
	Route::post('{id}/edit', 'ClientController@update')->name('update-client');
});

// Invoices
// Route::group(['middleware' => 'auth', 'prefix' => 'invoices'], function () {
// 	Route::get('/', 'InvoiceController@index')->name('invoices');
// 	Route::get('form', 'InvoiceController@form')->name('invoice-form');
// 	Route::get('template', 'InvoiceController@template')->name('invoice-template');
// });

// Calendar
Route::group(['middleware' => 'auth', 'prefix' => 'calendar'], function () {
	Route::get('/', 'CalendarController@index')->name('calendar');
});

// Milestones
Route::group(['middleware' => 'auth', 'prefix' => 'milestones'], function () {

	Route::get('{id}', 'MilestoneController@tasks')->name('milestone-tasks');


	// Route::get('/', 'TemplateController@index')->name('milestones');
	// Route::get('{id}', 'TemplateController@milestone')->name('milestone');
	// Route::get('new', 'TemplateController@save')->name('new-milestone');
	Route::get('/', 'MilestoneTemplateController@index')->name('milestones');
	
	Route::get('new', 'MilestoneTemplateController@save')->name('new-milestone');
});

// ================

// // Team
// Route::prefix('teams')->group(function () {
// 	Route::get('/', 'TeamController@index')->name('team');
// 	Route::get('profile/{id}', 'TeamController@memberProfile')->name('profile');
// 	Route::get('new', 'TeamController@save')->name('new-team');
// 	Route::post('new', 'TeamController@store')->name('store-team');
// });

// Personal Project
Route::group(['middleware' => 'auth', 'prefix' => 'personal'], function () {
	Route::get('/', 'ProjectController@myProjects')->name('my-projects');
	Route::get('/{status}', 'ProjectController@myProjectStatus')->name('my-status-projects');
});

// Forms
Route::group(['middleware' => 'auth', 'prefix' => 'forms'], function () {
	Route::get('/', 'FormController@index')->name('questionnaires');
	Route::get('new', 'FormController@save')->name('new-questionnaire');
	Route::post('new', 'FormController@store')->name('store-questionnaire');
	Route::get('/edit', 'FormController@edit')->name('edit-questionnaire');
	Route::view('/load', 'questionnaire-load')->name('load-questionnaire');
	Route::get('/quotataions', 'FormController@quotations')->name('quotations');
});

// Projects
Route::group(['middleware' => 'auth', 'prefix' => 'projects'], function () {
	Route::post('new', 'ProjectController@store')->name('store-project');
	Route::get('new', 'ProjectController@save')->name('new-project');
	Route::get('/', 'ProjectController@index')->name('projects');
	Route::get('{id}/edit', 'ProjectController@edit')->name('project-edit');
	Route::post('{id}/edit', 'ProjectController@update')->name('update-project');
	Route::delete('{id}/delete', 'ProjectController@delete')->name('delete-project');
	Route::get('/{status}', 'ProjectController@status')->name('status-projects');

});

// Project HQ
Route::group(['middleware' => 'auth', 'prefix' => 'project-hq/{project_id}'], function () {
	Route::get('/', 'ProjectController@getOverview')->name('project-hq-overview');
	Route::post('tasks/new', 'TaskController@store')->name('store-task');
	Route::post('milestone/new', 'MilestoneController@store')->name('store-milestone');
	Route::delete('milestone/{id}/delete', 'MilestoneController@delete')->name('delete-milestone');
	Route::post('files','MediaController@projectFileUpload')->name('project-media-upload');
});

//Auth::routes();

Route::get('home', 'HomeController@index')->name('home');

//temp this is use for testing purposes
Route::post('avatars', function () {
	request()->file('file')->store('avatars');

	return back();
});

// Views
//Route::view('dashboard', 'pages.dashboard')->name('dashboard');
//Route::view('projects', 'pages.projects')->name('projects');
//Route::view('clients', 'pages.clients')->name('clients');
*/
