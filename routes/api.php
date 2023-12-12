<?php

use Illuminate\Support\Facades\Route;

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

Route::post('auth/login', 'Apps\Default\Http\Controllers\AuthController@login');

Route::group(['middleware' => 'auth:api'], static function () {
    // Auth
    Route::group([
        'prefix' => 'auth',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
        Route::patch('me', 'AuthController@updateProfile');
    });

    // Circuit Reservations
    Route::group([
        'prefix' => 'circuit-reservations',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::post('/search', 'CircuitsReservationsController@search');
        Route::post('/summary', 'CircuitsReservationsController@summary');
        Route::post('/create', 'CircuitsReservationsController@create');
        Route::patch('/mark-as-used', 'CircuitsReservationsController@markAsUsed');
        Route::put('/update', 'CircuitsReservationsController@update');
        Route::delete('/delete', 'CircuitsReservationsController@delete');
        Route::get('/schedules-pdf/{date}', 'CircuitsReservationsController@schedulesPdf');
        Route::get('/{id}', 'CircuitsReservationsController@show');
        Route::post('/send-email', 'CircuitsReservationsController@sendEmail');
    });

    // Clients
    Route::group([
        'prefix' => 'clients',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'ClientsController@show');
        Route::post('/search', 'ClientsController@search');
        Route::post('/export-excel', 'ClientsController@exportExcel');
        Route::post('/duplicates', 'ClientsController@duplicates');
        Route::post('/create', 'ClientsController@create');
        Route::delete('/delete', 'ClientsController@delete');
        Route::put('/update', 'ClientsController@update');
        Route::get('/download-template/{client}', 'ClientsController@downloadTemplate');
        Route::post('/send-consent-email', 'ClientsController@sendConsentEmail');
    });

    // Client Notes
    Route::group([
        'prefix' => 'client-notes',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'ClientNotesController@show');
        Route::post('/search', 'ClientNotesController@search');
        Route::post('/create', 'ClientNotesController@create');
        Route::put('/update', 'ClientNotesController@update');
        Route::delete('/delete', 'ClientNotesController@delete');
    });

    // Client Files
    Route::group([
        'prefix' => 'client-files',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::post('/search', 'ClientFilesController@search');
        Route::post('/create', 'ClientFilesController@create');
        Route::put('/update', 'ClientFilesController@update');
        Route::delete('/delete', 'ClientFilesController@delete');
        Route::get('/{id}/file', 'ClientFilesController@file');
        Route::get('/{id}', 'ClientFilesController@show');
    });

    // Discounts
    Route::group([
        'prefix' => 'discounts',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'DiscountsController@show');
        Route::post('/search', 'DiscountsController@search');
        Route::post('/create', 'DiscountsController@create');
        Route::delete('/delete', 'DiscountsController@delete');
        Route::put('/update', 'DiscountsController@update');
    });

    // Employees
    Route::group([
        'prefix' => 'employees',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'EmployeesController@show');
        Route::post('/search', 'EmployeesController@search');
        Route::post('/create', 'EmployeesController@create');
        Route::delete('/delete', 'EmployeesController@delete');
        Route::put('/update', 'EmployeesController@update');
    });

    // Employees Time Off
    Route::group([
        'prefix' => 'employee-time-off',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'EmployeeTimeOffController@show');
        Route::post('/search', 'EmployeeTimeOffController@search');
        Route::post('/create', 'EmployeeTimeOffController@create');
        Route::delete('/delete', 'EmployeeTimeOffController@delete');
        Route::put('/update', 'EmployeeTimeOffController@update');
    });

    // Employees Time Off
    Route::group([
        'prefix' => 'employees-order',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'EmployeeOrderController@show');
        Route::post('/search', 'EmployeeOrderController@search');
        Route::post('/create', 'EmployeeOrderController@create');
        Route::delete('/delete', 'EmployeeOrderController@delete');
        Route::put('/update', 'EmployeeOrderController@update');
    });

    // Festive
    Route::group([
        'prefix' => 'festives',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'FestivesController@show');
        Route::post('/search', 'FestivesController@search');
        Route::post('/create', 'FestivesController@create');
        Route::delete('/delete', 'FestivesController@delete');
        Route::put('/update', 'FestivesController@update');
    });

    //TreatmentScheduleNotes
    Route::group([
        'prefix' => 'treatment-schedule-notes',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'TreatmentScheduleNotesController@show');
        Route::post('/search', 'TreatmentScheduleNotesController@search');
        Route::post('/create', 'TreatmentScheduleNotesController@create');
        Route::delete('/delete', 'TreatmentScheduleNotesController@delete');
        Route::put('/update', 'TreatmentScheduleNotesController@update');
    });

    // Localities
    Route::group([
        'prefix' => 'localities',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'LocalitiesController@show');
        Route::post('/provinces', 'LocalitiesController@provinces');
        Route::post('/search', 'LocalitiesController@search');
        Route::post('/create', 'LocalitiesController@create');
    });

    // Order
    Route::group([
        'prefix' => 'orders',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::post('/search', 'OrdersController@search');
        Route::post('/ticket', 'OrdersController@ticket');
        Route::post('/production-report', 'OrdersController@productionReport');
        Route::post('/send-ticket-email', 'OrdersController@sendTicketEmail');
        Route::post('/create', 'OrdersController@create');
        Route::put('/update', 'OrdersController@update');
        Route::delete('/delete', 'OrdersController@delete');
        Route::get('/{id}', 'OrdersController@show');
        Route::patch('/mark-used-purchase', 'OrdersController@markUsedPurchase');
        Route::patch('/edit-note', 'OrdersController@editNote');
    });

    // Order Details
    Route::group([
        'prefix' => 'order-details',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'OrderDetailsController@show');
        Route::post('/search', 'OrderDetailsController@search');
        Route::post('/create', 'OrderDetailsController@create');
        Route::put('/update', 'OrderDetailsController@update');
        Route::delete('/delete', 'OrderDetailsController@delete');
    });

    // Orders Approval
    Route::group([
        'prefix' => 'orders-approval',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'OrdersApprovalController@show');
        Route::post('/search', 'OrdersApprovalController@search');
        Route::post('/process', 'OrdersApprovalController@process');
        Route::delete('/delete', 'OrdersApprovalController@delete');
    });

    // Payments
    Route::group([
        'prefix' => 'payments',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'PaymentsController@show');
        Route::post('/search', 'PaymentsController@search');
        Route::post('/create', 'PaymentsController@create');
        Route::delete('/delete', 'PaymentsController@delete');
        Route::put('/update', 'PaymentsController@update');
    });

    // Gym Fee Types
    Route::group([
        'prefix' => 'gym-fee-types',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'GymFeeTypesController@show');
        Route::post('/search', 'GymFeeTypesController@search');
        Route::post('/create', 'GymFeeTypesController@create');
        Route::delete('/delete', 'GymFeeTypesController@delete');
        Route::put('/update', 'GymFeeTypesController@update');
    });

    // Gym Subscriptions
    Route::group([
        'prefix' => 'gym-subscriptions',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'GymSubscriptionsController@show');
        Route::post('/search', 'GymSubscriptionsController@search');
        Route::post('/create', 'GymSubscriptionsController@create');
        Route::delete('/delete', 'GymSubscriptionsController@delete');
        Route::put('/update', 'GymSubscriptionsController@update');
    });

    // Gym Subscription Members
    Route::group([
        'prefix' => 'gym-subscription-members',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'GymSubscriptionMembersController@show');
        Route::post('/search', 'GymSubscriptionMembersController@search');
        Route::post('/create', 'GymSubscriptionMembersController@create');
        Route::delete('/delete', 'GymSubscriptionMembersController@delete');
        Route::put('/update', 'GymSubscriptionMembersController@update');
    });

    // Gym Subscription Member Access
    Route::group([
        'prefix' => 'gym-subscription-member-access',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'GymSubscriptionMemberAccessController@show');
        Route::post('/search', 'GymSubscriptionMemberAccessController@search');
        Route::post('/create', 'GymSubscriptionMemberAccessController@create');
        Route::delete('/delete', 'GymSubscriptionMemberAccessController@delete');
        Route::put('/update', 'GymSubscriptionMemberAccessController@update');
    });

    // Gym Subscription Member Access Rights
    Route::group([
        'prefix' => 'gym-subscription-member-access-rights',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'GymSubscriptionMemberAccessRightsController@show');
        Route::post('/search', 'GymSubscriptionMemberAccessRightsController@search');
        Route::post('/create', 'GymSubscriptionMemberAccessRightsController@create');
        Route::delete('/delete', 'GymSubscriptionMemberAccessRightsController@delete');
        Route::put('/update', 'GymSubscriptionMemberAccessRightsController@update');
    });

    // Gym Subscription Quotas
    Route::group([
        'prefix' => 'gym-subscription-quotas',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'GymSubscriptionQuotasController@show');
        Route::post('/search', 'GymSubscriptionQuotasController@search');
        Route::post('/create', 'GymSubscriptionQuotasController@create');
        Route::delete('/delete', 'GymSubscriptionQuotasController@delete');
        Route::put('/update', 'GymSubscriptionQuotasController@update');
    });

    // Invoices
    Route::group([
        'prefix' => 'invoices',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'InvoicesController@show');
        Route::post('/search', 'InvoicesController@search');
        Route::post('/create', 'InvoicesController@create');
        Route::delete('/delete', 'InvoicesController@delete');
        Route::put('/update', 'InvoicesController@update');
    });

    // Products
    Route::group([
        'prefix' => 'products',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'ProductsController@show');
        Route::post('/search', 'ProductsController@search');
        Route::post('/create', 'ProductsController@create');
        Route::delete('/delete', 'ProductsController@delete');
        Route::put('/update', 'ProductsController@update');
    });

    // Product Discounts
    Route::group([
        'prefix' => 'product-discounts',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'ProductDiscountsController@show');
        Route::post('/search', 'ProductDiscountsController@search');
        Route::post('/create', 'ProductDiscountsController@create');
        Route::delete('/delete', 'ProductDiscountsController@delete');
        Route::put('/update', 'ProductDiscountsController@update');
    });

    // Product Types
    Route::group([
        'prefix' => 'product-types',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'ProductTypesController@show');
        Route::post('/search', 'ProductTypesController@search');
        Route::post('/create', 'ProductTypesController@create');
        Route::delete('/delete', 'ProductTypesController@delete');
        Route::put('/update', 'ProductTypesController@update');
    });

    // Sale Sessions
    Route::group([
        'prefix' => 'sale-sessions',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::patch('/close', 'SaleSessionsController@close');
        Route::post('/search', 'SaleSessionsController@search');
        Route::post('/active', 'SaleSessionsController@activeSession');
        Route::post('/orders-pdf', 'SaleSessionsController@ordersPdf');
        Route::post('/payments-pdf', 'SaleSessionsController@paymentsPdf');
        Route::post('/reopen', 'SaleSessionsController@reopen');
        Route::post('/create', 'SaleSessionsController@create');
        Route::delete('/delete', 'SaleSessionsController@delete');
        Route::put('/update', 'SaleSessionsController@update');
        Route::get('/{id}', 'SaleSessionsController@show');
    });

    // Treatments Reservations
    Route::group([
        'prefix' => 'treatment-reservations',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::post('/search', 'TreatmentsReservationsController@search');
        Route::post('/summary', 'TreatmentsReservationsController@summary');
        Route::post('/create', 'TreatmentsReservationsController@create');
        Route::patch('/mark-as-used', 'TreatmentsReservationsController@markAsUsed');
        Route::put('/update', 'TreatmentsReservationsController@update');
        Route::delete('/delete', 'TreatmentsReservationsController@delete');
        Route::get('/{id}', 'TreatmentsReservationsController@show');
        Route::get('/schedules-pdf/{date}', 'TreatmentsReservationsController@schedulesPdf');
        Route::get('/schedules-pdf/{date}/{employee}', 'TreatmentsReservationsController@schedulesEmployeePdf');
        Route::post('/send-email', 'TreatmentsReservationsController@sendEmail');
    });

    // Users
    Route::group([
        'prefix' => 'users',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'UsersController@show');
        Route::post('/search', 'UsersController@search');
        Route::post('/create', 'UsersController@create');
        Route::delete('/delete', 'UsersController@delete');
        Route::put('/update', 'UsersController@update');
    });

    // Categories
    Route::group([
        'prefix' => 'categories',
        'namespace' => 'Apps\Default\Http\Controllers\\',
    ], static function () {
        Route::get('/{id}', 'CategoryController@show');
        Route::post('/search', 'CategoryController@search');
        Route::post('/create', 'CategoryController@create');
        Route::delete('/delete', 'CategoryController@delete');
        Route::put('/update', 'CategoryController@update');
    });
});
