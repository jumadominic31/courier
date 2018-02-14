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

//signin
Route::get('/users/signin', [
    'uses' => 'UsersController@getSignin',
    'as' => 'users.signin'
]);

Route::post('/users/signin', [
    'uses' => 'UsersController@postSignin',
    'as' => 'users.signin'
]);

Route::get('/awbsearch', [
    'uses' => 'TxnsController@getAwbsearch',
    'as' => 'awbsearch.index'
]);

Route::post('/awbsearch', [
    'uses' => 'TxnsController@getAwbsearch',
    'as' => 'awbsearch.index'
]);

Route::group(['middleware' => 'auth'] , function () {
	//dashboard
    Route::get('/', 
        ['uses' => 'DashboardController@index' , 
        'as' => 'dashboard.index']
    );

    Route::get('/dashboard', [
        'uses' => 'DashboardController@index' , 
        'as' => 'dashboard.index'
    ]);

    //transactions
    Route::get('/shipments', [
        'uses' => 'TxnsController@getShipments',
        'as' => 'shipments.index'
    ]);

    Route::post('/shipments', [
        'uses' => 'TxnsController@getShipments',
        'as' => 'shipments.index'
    ]);

    Route::get('/shipment/{txn}/edit', [
        'uses' => 'TxnsController@edit' , 
        'as' => 'shipments.edit'
    ]);

    Route::post('/shipments/resetdriver/{id}', [
        'uses' => 'TxnsController@resetDrivercode' , 
        'as' => 'shipments.resetdriver'
    ]);

    Route::post('/shipments/resetreceiver/{id}', [
        'uses' => 'TxnsController@resetReceivercode' , 
        'as' => 'shipments.resetreceiver'
    ]);

    Route::match(array('PUT', 'PATCH'), '/shipment/{awb}', [
        'uses' => 'TxnsController@update' , 
        'as' => 'shipments.update'
    ]);

    Route::get('/awb', [
        'uses' => 'TxnsController@getAwb',
        'as' => 'shipments.awb'
    ]);

    Route::post('/awb', [
        'uses' => 'TxnsController@getAwb',
        'as' => 'shipments.awb'
    ]);

    //user admin
    Route::get('/users/logout', [
        'uses' => 'UsersController@getLogout',
        'as' => 'users.logout'
    ]);

    Route::get('/users/profile', [
        'uses' => 'UsersController@getProfile',
        'as' => 'users.profile'
    ]);

    Route::get('/users/resetpass', [
        'uses' => 'UsersController@resetpass',
        'as' => 'users.resetindividualpass'
    ]);

    Route::post('/users/resetpass', [
        'uses' => 'UsersController@postResetpass',
        'as' => 'users.postResetindividualpass'
    ]);

    Route::get('/users/{user}/resetotherpass', [
        'uses' => 'UsersController@resetOtherpass',
        'as' => 'users.resetOtherpass'
    ]);

    //company edit
    Route::get('/company/{company}/edit', [
        'uses' => 'CompaniesController@edit' , 
        'as' => 'company.edit'
    ]);

    Route::match(array('PUT', 'PATCH'), '/company/{company}', [
        'uses' => 'CompaniesController@update' , 
        'as' => 'company.update'
    ]);

    //company admin as superadmin
    Route::get('/company', [
        'uses' => 'CompaniesController@index' , 
        'as' => 'company.index'
    ]);

    Route::get('/company/create', [
        'uses' => 'CompaniesController@create' , 
        'as' => 'company.create'
    ]);

    Route::get('/company/{company}', [
        'uses' => 'CompaniesController@show' , 
        'as' => 'company.show'
    ]);

    Route::post('/company/store', [
        'uses' => 'CompaniesController@store' , 
        'as' => 'company.store'
    ]);

    Route::delete('/company/{company}', [
        'uses' => 'CompaniesController@destroy' , 
        'as' => 'company.destroy'
    ]);

    //stations admin        
    Route::resource('station', 'StationsController');

    //vehicles admin        
    Route::resource('vehicle', 'VehiclesController');

    //parcels admin        
    Route::resource('parcel', 'ParcelsController');

    //users admin
    Route::resource('users', 'UsersController');
});
