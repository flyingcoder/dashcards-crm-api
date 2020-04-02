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
Route::group(['middleware' => 'auth:api', 'prefix' => 'logout'], function () {

  Route::post('/', 'Auth\ApiLoginController@logout');

});

Route::group(['middleware' => ['api', 'cors'], 'prefix' => 'register'], function () {

  Route::post('/', 'Auth\ApiRegisterController@create');
  
  //Route::post('/set-password', 'Auth\ApiRegisterController@setPassword');

  //Route::post('/get-user-id', 'Auth\ApiRegisterController@getUserId');

});

Route::group(['middleware' => 'auth:api', 'prefix' => 'verify'], function () {

  Route::get('is-belong-to', 'VerificationController@isBelongToCompany');

});

Route::post('login', 'Auth\ApiLoginController@login');

Route::group(['middleware' => ['api', 'cors'], 'prefix' => 'password'], function () {

  Route::post('email', 'Auth\ApiForgotPasswordController@sendResetLinkEmail');

  Route::post('reset', 'Auth\ApiResetPasswordController@reset');

});

Route::group(['middleware' => 'auth:api', 'prefix' => 'note'], function () {

  Route::get('/', 'NoteController@index');

  Route::put('{id}/{action}', 'NoteController@pinning')->where('action', 'pin|unpin');

  Route::put('{id}', 'NoteController@update');

  Route::post('/', 'NoteController@store');

  Route::post('{id}/collaborators', 'NoteController@collaborators');

  Route::delete('{id}', 'NoteController@delete');

});

Route::group(['middleware' => 'auth:api', 'prefix' => 'chat'], function () {

  Route::get('unread', 'MessageController@unRead');

  Route::get('list', 'MessageController@list');

  Route::get('group/list', 'MessageController@groupList');

  Route::get('mark-read', 'MessageController@markRead');

  Route::get('/private/{friend_id}', 'MessageController@fetchPrivateMessages');

  Route::get('group/private/{convo_id}', 'MessageController@fetchGroupMessages');

  Route::post('/private', 'MessageController@sendPrivateMessage');

  Route::post('group/private', 'MessageController@sendGroupMessage');

  Route::get('/private', 'MessageController@sendPrivateMessage');

  Route::post('/group', 'MessageController@createGroupChat');

  Route::post('group/remove-member', 'MessageController@removeFromGroup');

  Route::post('group/update-members', 'MessageController@updateGroupChatMembers');

  Route::get('group/members/{convo_id}', 'MessageController@groupChatMembers');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'activities'], function () {

  Route::get('/', 'ActivityController@index');

  Route::get('log', 'ActivityController@log');

  Route::get('{id}/mark-read', 'ActivityController@markRead');

  Route::get('unread', 'ActivityController@unread');

});

Route::group(['middleware' => 'auth:api', 'prefix' => 'upgrade'], function () {

  Route::get('plan', 'PaymentController@plan');

  Route::post('checkout', 'PaymentController@checkout');

});

Route::group(['middleware' => 'auth:api', 'prefix' => 'report'], function () {
  
  Route::get('/', 'ReportController@index');

  Route::post('/', 'ReportController@newReport');

  Route::put('{id}', 'ReportController@updateReport');

  Route::delete('{id}', 'ReportController@deleteReport');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'autocomplete'], function () {
  
  Route::get('{model}', 'SearchController@autocomplete');

});

//permission
Route::group(['middleware' => 'auth:api', 'prefix' => 'permission'], function () {

  Route::get('/', 'PermissionController@index');

  Route::post('/', 'PermissionController@store');

  Route::get('autocomplete', 'PermissionController@search');

  Route::put('{id}', 'PermissionController@update');

  Route::delete('{id}', 'PermissionController@delete');

  Route::get('/defaults', 'PermissionController@defaultPermissions');
});


//permission
Route::group(['middleware' => 'auth:api', 'prefix' => 'roles'], function () {

  Route::get('/company', 'RoleController@companyRoles');

  Route::get('/default', 'RoleController@defaultRoles');

  Route::get('{id}/permissions', 'RoleController@getPermissionByRole');

  Route::put('{id}/permissions', 'RoleController@updateRolePermissions');

});

//company
Route::group(['middleware' => 'auth:api', 'prefix' => 'company'], function () {

  Route::get('members', 'CompanyController@members');

  Route::get('teams', 'CompanyController@teams');

  Route::get('teams/{id}', 'CompanyController@member');

  Route::delete('teams/bulk-delete', 'TeamController@bulkDelete');

  Route::delete('teams/{id}', 'TeamController@delete');

  Route::post('teams', 'TeamController@store');

  Route::put('teams/{id}', 'TeamController@update');

  Route::get('invoices/statistics', 'InvoiceController@statistics');

  Route::get('invoices/{id?}', 'InvoiceController@index');

});

// Tasks
Route::group(['middleware' => 'auth:api', 'prefix' => 'task'], function () {
  
  Route::get('/', 'TaskController@index');

  Route::get('mine', 'TaskController@mine');

  Route::post('/', 'TaskController@store'); //for independent task no milestone

  Route::get('statistics/{id}', 'TaskController@stats');

  Route::get('{id}', 'TaskController@task');

  Route::delete('{id}', 'TaskController@delete');

  Route::put('{id}', 'TaskController@update');
  
  Route::put('{id}/mark-as-complete', 'TaskController@markAsComplete');

  Route::get('{id}/comments', 'TaskController@comments');

  Route::post('{id}/comments', 'TaskController@addComments');

});

// Commments
Route::group(['middleware' => 'auth:api', 'prefix' => 'comments'], function () {
  Route::delete('{id}', 'CommentController@delete');
});

// Milestone
Route::group(['middleware' => 'auth:api', 'prefix' => 'milestone'], function () {

  Route::get('{id}/task', 'MilestoneController@tasks');

  Route::post('{milestone_id}/task', 'TaskController@store');

  Route::put('{milestone_id}/task/{task_id}', 'TaskController@updateTask');

  Route::delete('{milestone_id}/task/bulk-delete', 'TaskController@bulkDeleteTask');

  Route::delete('{milestone_id}/task/{task_id}', 'TaskController@deleteTask');

});

//new dynamic parent milestone api - alvin
Route::group(['middleware' => 'auth:api'], function () {

  Route::get('{parent}/{id}/milestone', 'MilestoneController@index')
         ->where('parent', 'project|template');

  Route::post('{parent}/{id}/milestone', 'MilestoneController@store')
         ->where('parent', 'project|template');

  Route::put('{parent}/{id}/milestone/{milestone_id}', 'MilestoneController@update')
         ->where('parent', 'project|template');

  Route::delete('{parent}/{parent_id}/milestone/{milestone_id}', 'MilestoneController@delete')
         ->where('parent', 'project|template');

   Route::get('{parent}/{parent_id}/milestone/{milestone_id}', 'MilestoneController@milestone')
         ->where('parent', 'project|template');
});

//events
Route::group(['middleware' => 'auth:api', 'prefix' => 'events'], function () {
  
  Route::get('/', 'EventController@index');

  Route::get('{id}/delete', 'EventController@delete');

  Route::post('/', 'EventController@store');

  Route::put('{id}', 'EventController@update');

  Route::delete('{id}/participants/{user_id?}', 'EventController@leaveEvent');

  Route::delete('{id}', 'EventController@delete');

  Route::get('/attributes', 'EventController@attributes');

});

//calendars
Route::group(['middleware' => 'auth:api', 'prefix' => 'calendars'], function () {

  Route::get('/', 'CalendarController@index');

  Route::post('/', 'CalendarController@store');

  Route::get('my-calendar', 'CalendarController@calendar');

  Route::get('attributes', 'CalendarController@attributes');

  Route::post('event-types', 'CalendarController@addEventType');

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

  Route::post('update-password', 'UserController@updatePassword');

  Route::post('{id}', 'UserController@editProfilePicture');

  Route::post('/', 'Auth\RegisterController@create');

  Route::get('/company/{key}', 'UserController@getMeta');

  Route::post('/company/details', 'UserController@addCompanyDetails');

  Route::post('/company/invoice-settings', 'UserController@addInvoiceSettings');

  Route::post('/company/invoice-settings', 'UserController@addInvoiceSettings');

  Route::post('/company/bank-transfer-details', 'UserController@addBankTransferDetails');

  Route::post('/company/paypal-details', 'UserController@addPaypalDetails');

  Route::get('/tasks', 'UserController@tasks');

  Route::get('{user_id}/tasks', 'UserController@userTasks');

  Route::get('/tasks/count', 'UserController@countTasks');

  Route::get('/projects', 'UserController@projects');

  Route::get('/clients', 'UserController@clients');

  Route::get('/notifications', 'NotificationController@unread');

  Route::get('/notifications/count', 'NotificationController@unreadcount');

  Route::put('/notifications/{id}', 'NotificationController@markRead');

  Route::get('{user_id}/timers', 'UserController@userTimers');
  
});

//timer
Route::group(['middleware' => 'auth:api', 'prefix' => 'timer'], function () {

  Route::get('/', 'TimerController@index');

  Route::post('/', 'TimerController@task');

  Route::post('{action}', 'TimerController@timer');

  Route::get('{action}', 'TimerController@timer');

});

// Templates
Route::group(['middleware' => 'auth:api', 'prefix' => 'template'], function () {

  Route::get('/', 'TemplateController@index');

  Route::post('/', 'TemplateController@store');

  Route::get('{id}', 'TemplateController@template');

  Route::put('{id}', 'TemplateController@update');

  Route::delete('bulk-delete', 'TemplateController@bulkDelete');

  Route::delete('{id}', 'TemplateController@delete');

});

// Services
Route::group(['middleware' => 'auth:api', 'prefix' => 'services'], function () {

	Route::get('/', 'ServiceController@index'); // projects //error

  Route::post('/', 'ServiceController@store');

  Route::post('validate', 'ServiceController@isValid');

  Route::get('{id}', 'ServiceController@getService');

  Route::put('{id}', 'ServiceController@update');

  Route::delete('bulk-delete', 'ServiceController@bulkDelete');

  Route::delete('{id}', 'ServiceController@delete');

});

//media
Route::group(['middleware' => 'auth:api', 'prefix' => 'file'], function () {

  Route::delete('{id}', 'MediaController@delete');

  Route::post('/image-upload', 'MediaController@uploadImage');
});

// Projects
Route::group(['middleware' => 'auth:api', 'prefix' => 'projects'], function () {

  Route::get('/', 'ProjectController@index');// project
  
  Route::delete('bulk-delete', 'ProjectController@bulkDelete');

  Route::delete('{id}', 'ProjectController@delete');

  Route::get('{id}', 'ProjectController@project');

  Route::post('{id}/milestone-import', 'ProjectController@milestoneImport');

  Route::get('{id}/messages', 'ProjectController@messages');

  Route::post('{id}/messages', 'ProjectController@sendMessages');

  Route::get('{id}/tasks', 'ProjectController@tasks');// project-hq

  Route::get('{id}/tasks/mine', 'ProjectController@myTasks');// project-hq

  Route::post('/', 'ProjectController@store');

  Route::put('{id}', 'ProjectController@update'); //no more edit

  Route::post('{id}/comments', 'ProjectController@addComments');

  Route::get('{id}/comments', 'ProjectController@comments');

	Route::put('{id}/status', 'ProjectController@updateStatus');

  Route::get('count', 'ProjectController@countProject');

  Route::get('{id}/timer', 'ProjectController@timer');

  Route::get('{id}/timers', 'ProjectController@myTimers');

  Route::get('{id}/member', 'ProjectController@members');// project-hq

  Route::post('{id}/member', 'ProjectController@assignMember');// project-hq

  Route::delete('{id}/member/bulk-delete', 'ProjectController@bulkRemoveMember');// project-hq

  Route::delete('{id}/member/{member_id}', 'ProjectController@removeMember');// project-hq

  Route::get('{id}/members-all', 'ProjectController@membersAll');// project-hq

  Route::get('{id}/new-members', 'ProjectController@newMembers');// project-hq
  
  Route::get('{id}/files-count', 'ProjectController@filesCount');

  Route::get('{id}/file', 'MediaController@projectMedia');// project-hq

	Route::post('{id}/file','MediaController@projectFileUpload');// project-hq

  Route::post('{id}/link', 'MediaController@addMediaLink');

  Route::get('{id}/file/grid', 'MediaController@projectMediaAll');

  Route::get('{id}/timeline', 'ActivityController@project');

  Route::get('{id}/invoice', 'ProjectController@invoice');

  Route::get('{id}/tasks-for-invoice', 'ProjectController@forInvoice');

  Route::post('{id}/invoice', 'ProjectController@saveInvoice');

  Route::get('{id}/report', 'ProjectController@reports');

  Route::post('{id}/report', 'ProjectController@newReport');

  Route::put('{id}/report/{report_id}', 'ProjectController@updateReport');

  Route::delete('{id}/report/{report_id}', 'ProjectController@deleteReport');

});

// Forms
Route::group(['middleware' => 'auth:api', 'prefix' => 'forms'], function () {

    Route::get('/', 'FormController@index');

    Route::post('/', 'FormController@store');

    Route::post('project-details', 'FormController@projectDetails');

    Route::get('project-details/{id}', 'FormController@getProjectDetails');

});

// Clients
Route::group(['middleware' => 'auth:api', 'prefix' => 'clients'], function () {

  Route::get('/', 'ClientController@index'); // project

  Route::get('{id}', 'ClientController@client'); // project

  Route::post('{id}/image', 'ClientController@updatePicture');
  
  Route::post('/', 'ClientController@store'); // client add
  
  Route::put('/{id}', 'ClientController@update'); // client update

  Route::delete('bulk-delete', 'ClientController@bulkDelete');

  Route::delete('{id}', 'ClientController@delete');

  Route::get('{id}/tasks', 'ClientController@tasks');

  Route::get('{id}/staffs', 'ClientController@staffs');

  Route::get('{id}/invoices', 'ClientController@invoices');

});

// Groups
Route::group(['middleware' => 'auth:api', 'prefix' => 'groups'], function () {

	Route::get('/', 'TeamController@groups');

	Route::post('/', 'GroupController@store');

  Route::get('roles', 'TeamController@role');

  Route::get('{id}', 'TeamController@editgroup');

  Route::get('{id}/members', 'GroupController@members');

  Route::put('{id}', 'TeamController@updategroup');

  Route::delete('{id}', 'TeamController@deletegroup');

  Route::post('{id}/permission', 'GroupController@assignPermission');

  Route::get('{id}/permission', 'PermissionController@permissions');

});



// Invoices
Route::group(['middleware' => 'auth:api', 'prefix' => 'invoice'], function () {

  Route::get('/', 'InvoiceController@index');

  Route::post('/', 'InvoiceController@store');

  Route::get('{id}', 'InvoiceController@invoice');

  Route::post('{id}', 'InvoiceController@update');

  Route::delete('bulk-delete', 'InvoiceController@bulkDelete');

  Route::delete('{id}', 'InvoiceController@delete');
});

// Forms
Route::group(['middleware' => 'auth:api', 'prefix' => 'forms'], function () {
  
  Route::get('/', 'FormController@index');

});
