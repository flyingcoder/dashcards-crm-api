<?php

use App\Http\Middleware\IsAppAdmins;

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
Route::group(['middleware' => ['api']], function () {
    Route::get('form/{slug}/online', 'FormController@formBySlug');
    Route::post('form/{id}/online', 'FormController@saveFormResponse');

    Route::get('configs', 'ConfigurationController@index');
    Route::get('configs/{key}', 'ConfigurationController@getByKey');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('configs/bulk', 'ConfigurationController@bulkSave')->middleware(IsAppAdmins::class);
        Route::post('configs', 'ConfigurationController@saveByKey')->middleware(IsAppAdmins::class);

        Route::get('logs/activities', 'LogsController@getActivityLogs')->middleware(IsAppAdmins::class);
        Route::get('logs/emails', 'EmailController@emailLogs')->middleware(IsAppAdmins::class);
        Route::get('logs', 'LogsController@index')->middleware(IsAppAdmins::class);
        Route::post('logs/clear', 'LogsController@clear')->middleware(IsAppAdmins::class);

        Route::get('companies', 'CompanyController@companies')->middleware(IsAppAdmins::class);
        Route::post('companies/{id}/status', 'CompanyController@companyStatus')->middleware(IsAppAdmins::class);
        Route::get('subscribers/statistics', 'CompanyController@subscribersStatistics')->middleware(IsAppAdmins::class);

        Route::get('database', 'DatabaseController@index')->middleware(IsAppAdmins::class);
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'logout'], function () {

    Route::post('/', 'Auth\ApiLoginController@logout');

});

Route::group(['middleware' => ['api'], 'prefix' => 'register'], function () {

    Route::post('/', 'Auth\ApiRegisterController@create');

    Route::post('/set-password', 'Auth\ApiRegisterController@setPassword');

    Route::post('/get-user-id', 'Auth\ApiRegisterController@getUserId');

});

Route::group(['middleware' => 'auth:api', 'prefix' => 'verify'], function () {

    Route::get('is-belong-to', 'VerificationController@isBelongToCompany');

});

Route::group(['middleware' => 'auth:api', 'prefix' => 'emails'], function () {

    Route::post('send', 'EmailController@sendEmail');

    Route::get('templates', 'EmailController@coreTemplates');
});

Route::post('login', 'Auth\ApiLoginController@login');

Route::group(['middleware' => ['api'], 'prefix' => 'password'], function () {

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

    Route::get('conversations/list', 'MessageController@conversationList');

    Route::post('conversations', 'MessageController@newGroupConversation');

    Route::get('conversations/user/{id}', 'MessageController@conversationByUser');

    Route::post('conversations/{id}/messages', 'MessageController@sendConversationMessage');

    Route::get('conversations/{id}/messages', 'MessageController@fetchConversationMessages');

    Route::post('conversations/{id}/members', 'MessageController@manageConversationMembers');

    Route::post('conversations/{id}/mark-as-read', 'MessageController@readConversation');

    Route::get('group/list', 'MessageController@groupList');

    Route::get('group/private/{convo_id}', 'MessageController@fetchGroupMessages');

    Route::get('mark-read', 'MessageController@markAllAsRead');

    Route::get('/private/{friend_id}', 'MessageController@fetchPrivateMessages');

    Route::get('group/{type}/{project_id}', 'MessageController@getGroupInfo'); //type : client,team

    Route::post('/private', 'MessageController@sendPrivateMessage');

    Route::post('group/private', 'MessageController@sendGroupMessage');

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

Route::group(['middleware' => 'auth:api', 'prefix' => 'stripe'], function () {

    Route::get('account', 'StripeController@getStripeAccount');

    Route::get('plans', 'StripeController@getStripePlans');

    Route::post('plans', 'StripeController@createStripePlans');

    Route::put('plans', 'StripeController@updateStripePlans');

    Route::post('connect', 'StripeController@connectToStripe');

    Route::post('disconnect', 'StripeController@disconnectFromStripe');

    Route::get('payment-intent/{id}', 'StripeController@createPaymentIntent');

});

Route::group(['middleware' => 'auth:api', 'prefix' => 'upgrade'], function () {

    Route::get('plan', 'PaymentController@plan');

    Route::post('checkout', 'PaymentController@checkout');

});

Route::group(['middleware' => 'auth:api', 'prefix' => 'report'], function () {

    Route::get('/', 'ReportController@index');

    Route::post('/', 'ReportController@newReport');

    Route::post('/via-template', 'ReportController@newReportViaTemplate');

    Route::put('{id}', 'ReportController@updateReport');

    Route::put('{id}/via-template', 'ReportController@updateReportViaTemplate');

    Route::delete('{id}', 'ReportController@deleteReport');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'autocomplete'], function () {

    Route::get('search', 'SearchController@globalSearch');

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

    Route::get('user/{id}', 'PermissionController@userPermissions');
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

    Route::get('clients', 'CompanyController@clients');

    Route::get('invoices/statistics', 'InvoiceController@statistics');

    Route::get('invoices/{id?}', 'InvoiceController@index');

    Route::get('{id}/info', 'CompanyController@info');

    Route::put('{id}/info', 'CompanyController@updateInfo');

    Route::post('{id}/logo/via-url', 'CompanyController@setLogoViaUrl');

    Route::post('{id}/logo', 'CompanyController@uploadLogo');

    Route::get('{id}/settings', 'CompanyController@settings');

    Route::post('{id}/settings', 'CompanyController@updateSettings');
});

// Tasks
Route::group(['middleware' => 'auth:api', 'prefix' => 'task'], function () {

    Route::get('mine', 'TaskController@mine');

    Route::get('/', 'TaskController@index');

    Route::post('/', 'TaskController@store'); //for independent task no milestone

    Route::get('statistics/{id}', 'TaskController@stats');

    Route::put('{id}/mark-as-complete', 'TaskController@markAsComplete');

    Route::put('{id}/mark-as-urgent', 'TaskController@markAsUrgent');

    Route::get('{id}/comments', 'TaskController@comments');

    Route::post('{id}/comments', 'TaskController@addComments');

    Route::get('{id}', 'TaskController@task');

    Route::delete('{id}', 'TaskController@delete');

    Route::put('{id}', 'TaskController@update');

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

    Route::delete('template/{parent_id}/milestone/bulk-delete', 'MilestoneController@bulkDelete');

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

    Route::post('{id}/participants', 'EventController@addParticipants');

    Route::delete('{id}/participants/{user_id?}', 'EventController@leaveEvent');

    Route::delete('{id}', 'EventController@delete');

    Route::get('/attributes', 'EventController@attributes');

    Route::get('{id}/comments', 'EventController@getComments');

    Route::post('{id}/comments', 'EventController@addComment');

    Route::delete('{id}/comments/{comment_id}', 'EventController@removeComment');

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

//notifications
Route::group(['middleware' => 'auth:api', 'prefix' => 'notifications'], function () {

    Route::get('/', 'NotificationController@index');

    Route::get('{type}/count', 'NotificationController@notificationCounts')->where('type', 'chat|company');

    Route::put('/chat/mark-as-read', 'NotificationController@markAllChatAsRead');

    Route::put('/company/mark-as-read', 'NotificationController@markAllCompanyAsRead');

    Route::put('/chat/{id}/mark-as-read', 'NotificationController@chatMarkAsRead');

    Route::put('/company/{id}/mark-as-read', 'NotificationController@notificationMarkAsRead');
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

    Route::get('{user_id}/task-timers', 'UserController@userTaskTimers');

    Route::get('{user_id}/global-timers', 'UserController@userGlobalTimers');
});

//timer
Route::group(['middleware' => 'auth:api', 'prefix' => 'timer'], function () {

    Route::get('/', 'TimerController@index');

    Route::post('/', 'TimerController@task');

    Route::get('status/{user_id?}', 'TimerController@status');

    Route::post('{type}/force-stop', 'TimerController@forceStopTimer')->where('type', 'global|task');

    Route::post('{action}', 'TimerController@timer');

    Route::get('tasks', 'TimerController@taskTimers');

    Route::get('global', 'TimerController@globalTimers');

    Route::get('{action}', 'TimerController@timer');

});

// Templates
Route::group(['middleware' => 'auth:api', 'prefix' => 'template'], function () {

    Route::get('{type}/tree-view', 'TemplateController@treeView')->where('type', 'milestone|invoice');

    Route::get('/', 'TemplateController@index');

    Route::post('/', 'TemplateController@store');

    Route::get('reports', 'TemplateController@reports');

    Route::get('reports/{id}', 'TemplateController@report');

    Route::post('reports', 'TemplateController@storeReportTemplate');

    Route::put('reports/{id}', 'TemplateController@updateReportTemplate');

    Route::delete('reports/{id}', 'TemplateController@deleteReportTemplate');

    Route::get('invoices', 'TemplateController@invoices');

    Route::post('invoices', 'TemplateController@saveInvoiceTemplates');

    Route::put('invoices', 'TemplateController@updateInvoiceTemplates');

    Route::delete('invoices/{id}', 'TemplateController@deleteInvoiceTemplates');

    Route::get('invoices/fields', 'TemplateController@getInvoiceFields');

    Route::get('email-templates/{type?}', 'TemplateController@getEmailTemplates');

    Route::post('email-templates/global', 'TemplateController@saveGlobalEmailTemplate');

    Route::post('email-templates', 'TemplateController@saveEmailTemplate');

    Route::get('{id}', 'TemplateController@template');

    Route::put('{id}', 'TemplateController@update');

    Route::delete('bulk-delete', 'TemplateController@bulkDelete');

    Route::delete('{id}', 'TemplateController@delete');

});

// Services//Campaign
Route::group(['middleware' => 'auth:api', 'prefix' => 'services'], function () {

    Route::get('{id}', 'CampaignController@service');

    Route::get('/', 'CampaignController@index'); // services

    Route::post('/', 'CampaignController@store');

    Route::post('validate', 'CampaignController@isValid');

    Route::put('{id}', 'CampaignController@update');

    Route::delete('bulk-delete', 'CampaignController@bulkDelete');

    Route::delete('{id}', 'CampaignController@delete');

    Route::get('{id}/timeline', 'ActivityController@service');

    Route::get('{id}/invoice', 'CampaignController@invoices');

});

//Service list
Route::group(['middleware' => 'auth:api', 'prefix' => 'services-list'], function () {

    Route::get('list', 'ServiceListController@list');

    Route::get('{id}', 'ServiceListController@service');

    Route::get('/', 'ServiceListController@index');

    Route::post('/', 'ServiceListController@store');

    Route::post('/set-icon', 'ServiceListController@setIcon');

    Route::put('{id}', 'ServiceListController@update');

    Route::delete('{id}', 'ServiceListController@delete');
});

//media
Route::group(['middleware' => 'auth:api', 'prefix' => 'file'], function () {
    Route::post('/image-upload', 'MediaController@uploadImage');

    Route::post('/{id}/status', 'MediaController@updateStatus');

    Route::post('/{id}/comment', 'MediaController@addComment');

    Route::get('/{id}/comment', 'MediaController@fetchComments');

    Route::delete('{id}/comment/{commment_id}', 'MediaController@deleteComment');

    Route::delete('{id}', 'MediaController@delete');
});

// Projects
Route::group(['middleware' => 'auth:api', 'prefix' => 'projects'], function () {

    Route::get('/', 'ProjectController@index');// project

    Route::get('/user-projects/{user_id}', 'ProjectController@projectByUser');// project

    Route::delete('bulk-delete', 'ProjectController@bulkDelete');

    Route::delete('{id}', 'ProjectController@delete');

    Route::get('{id}', 'ProjectController@project');

    Route::get('{id}/info', 'ProjectController@projectInfo');

    Route::post('{id}/milestone-import', 'ProjectController@milestoneImport');

    Route::get('{id}/messages', 'ProjectController@messages');

    Route::post('{id}/messages', 'ProjectController@sendMessages');

    Route::get('{id}/tasks/mine', 'ProjectController@myProjectTasks');// project-hq

    Route::get('{id}/tasks', 'ProjectController@allProjectTasks');// project-hq

    Route::get('{id}/tasks/search', 'ProjectController@searchTasks');

    Route::post('/', 'ProjectController@store');

    Route::put('{id}', 'ProjectController@update'); //no more edit

    Route::post('{id}/comments', 'ProjectController@addComments');

    Route::get('{id}/comments', 'ProjectController@comments');

    Route::put('{id}/status', 'ProjectController@updateStatus');

    Route::get('count', 'ProjectController@countProject');

    Route::get('{id}/timer', 'ProjectController@timer');

    Route::get('{id}/timers', 'ProjectController@myTimers');

    Route::get('{id}/project-tasks-timers', 'ProjectController@projectTaskTimers');

    Route::get('{id}/member', 'ProjectController@members');// project-hq

    Route::post('{id}/member', 'ProjectController@assignMember');// project-hq

    Route::delete('{id}/member/bulk-delete', 'ProjectController@bulkRemoveMember');// project-hq

    Route::delete('{id}/member/{member_id}', 'ProjectController@removeMember');// project-hq

    Route::get('{id}/members-all', 'ProjectController@membersAll');// project-hq

    Route::get('{id}/new-members', 'ProjectController@newMembers');// project-hq

    Route::get('{id}/files-count', 'ProjectController@filesCount');

    Route::get('{id}/file', 'MediaController@projectMedia');// project-hq

    Route::post('{id}/file', 'MediaController@projectFileUpload');// project-hq

    Route::post('{id}/link', 'MediaController@addMediaLink');

    Route::delete('{id}/file/bulk-delete', 'MediaController@bulkDeleteFiles');

    Route::get('{id}/file/grid', 'MediaController@projectMediaAll');

    Route::get('{id}/timeline', 'ActivityController@project');

    Route::get('{id}/invoice', 'ProjectController@invoice');

    Route::get('{id}/tasks-for-invoice', 'ProjectController@forInvoice');

    Route::post('{id}/invoice', 'ProjectController@saveInvoice');

    Route::get('{id}/report', 'ProjectController@reports');

    Route::post('{id}/report', 'ProjectController@newReport');

    Route::put('{id}/report/{report_id}', 'ProjectController@updateReport');

    Route::delete('{id}/report/{report_id}', 'ProjectController@deleteReport');

    Route::get('{id}/folder/{source}', 'ProjectFolderController@projectFolders');//->where('source', 'google-drive|dropbox');

    Route::post('{id}/folder/{source}', 'ProjectFolderController@store');

    Route::delete('{id}/folder-id/{source}/{folder_id}', 'ProjectFolderController@deleteByFolderId');

    Route::delete('{id}/folder/{source}/{folder_id}', 'ProjectFolderController@delete');
});

// Forms
Route::group(['middleware' => 'auth:api', 'prefix' => 'forms'], function () {

    Route::get('/', 'FormController@index');

    Route::get('list', 'FormController@list');

    Route::get('{id}', 'FormController@form');

    Route::get('{id}/responses', 'FormController@formResponses');

    Route::delete('{id}', 'FormController@delete');

    Route::post('send-email-form', 'FormController@sendForm');

    Route::put('/', 'FormController@update');

    Route::post('/', 'FormController@store');

});

// Clients
Route::group(['middleware' => 'auth:api', 'prefix' => 'clients'], function () {

    Route::get('/', 'ClientController@index'); // project

    Route::get('/per-company', 'ClientController@perCompany'); // project

    Route::get('{id}', 'ClientController@client'); // project

    Route::post('{id}/image', 'UserController@editProfilePicture');

    Route::post('/', 'ClientController@store'); // client add

    Route::put('/{id}', 'ClientController@update'); // client update

    Route::delete('bulk-delete', 'ClientController@bulkDelete');

    Route::delete('{id}', 'ClientController@delete');

    Route::get('{id}/tasks', 'ClientController@tasks');

    Route::get('{id}/staffs', 'ClientController@staffs');

    Route::post('{id}/staffs', 'ClientController@addStaffs');

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

    Route::post('user/update-roles', 'GroupController@updateRoles');

    Route::post('user/restore-delete', 'GroupController@restoreDelete');
});


// Invoices
Route::group(['middleware' => 'auth:api', 'prefix' => 'invoice'], function () {

    Route::get('/', 'InvoiceController@index');

    Route::post('/', 'InvoiceController@store');

    Route::post('bulk-remind', 'InvoiceController@invoiceReminder');

    Route::get('{id}/download', 'InvoiceController@getPDFInvoice');

    Route::get('{id}/parse-template', 'InvoiceController@getParseInvoice');

    Route::get('{id}', 'InvoiceController@invoice');

    Route::post('{id}', 'InvoiceController@update');

    Route::delete('bulk-delete', 'InvoiceController@bulkDelete');

    Route::delete('{id}', 'InvoiceController@delete');
});

// Forms
Route::group(['middleware' => 'auth:api', 'prefix' => 'forms'], function () {
    Route::get('/', 'FormController@index');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'schedule-tasks'], function () {
    Route::post('/{id}/toggle-status', 'ScheduleTaskController@toggleStatus');

    Route::post('/', 'ScheduleTaskController@store');

    Route::put('/{id}', 'ScheduleTaskController@update');

    Route::delete('/{id}', 'ScheduleTaskController@forceDelete');

    Route::get('/{id}/history', 'ScheduleTaskController@histories');

    Route::get('/', 'ScheduleTaskController@index');
});
