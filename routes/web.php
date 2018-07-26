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
	//user admin
    Route::get('/users/logout', [
        'uses' => 'UsersController@getLogout',
        'as' => 'users.logout'
    ]);

    Route::get('/dashboard/courier', [
        'uses' => 'DashboardController@courier' , 
        'as' => 'dashboard.courier'
    ]);


    //dashboard
    // Route::group(['middleware' => 'auth.courclerk'] , function () {
        Route::get('/', 
            ['uses' => 'DashboardController@index' , 
            'as' => 'dashboard.index']
        );

        Route::get('/dashboard', [
            'uses' => 'DashboardController@index' , 
            'as' => 'dashboard.index'
        ]);

        

        //transactions
        Route::get('/shipments/add', [
            'uses' => 'TxnsController@addShipment',
            'as' => 'shipments.add'
        ]);

        Route::post('/shipments/store', [
            'uses' => 'TxnsController@storeShipment' , 
            'as' => 'shipments.store'
        ]);

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

        //invoices
        Route::get('/invoice', [
            'uses' => 'InvoicesController@getInvoices',
            'as' => 'invoice.index'
        ]);

        Route::post('/invoice', [
            'uses' => 'InvoicesController@getInvoices',
            'as' => 'invoice.index'
        ]);

        Route::get('/invoice/show/{id}', [
            'uses' => 'InvoicesController@showInvoice',
            'as' => 'invoice.show'
        ]);

        Route::get('/invoice/seltxns/{id}', [
            'uses' => 'InvoicesController@selTxns',
            'as' => 'invoice.seltxns'
        ]);

        Route::get('/invoice/add', [
            'uses' => 'InvoicesController@addInvoice',
            'as' => 'invoice.add'
        ]);

        Route::post('/invoice/store', [
            'uses' => 'InvoicesController@storeInvoice' , 
            'as' => 'invoice.store'
        ]);

        Route::get('/invoice/add2', [
            'uses' => 'InvoicesController@addInvoice2',
            'as' => 'invoice.add2'
        ]);

        Route::post('/invoice/store2', [
            'uses' => 'InvoicesController@storeInvoice2' , 
            'as' => 'invoice.store2'
        ]);

        Route::match(array('PUT', 'PATCH'), '/invoice/voidinv/{id}', [
            'uses' => 'InvoicesController@voidInvoice' , 
            'as' => 'invoice.voidinv'
        ]);

        Route::get('/invoice/print/{id}', [
            'uses' => 'InvoicesController@printInvoice',
            'as' => 'invoice.print'
        ]);

        //users

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

        //Contracts
        Route::get('/contracts', [
            'uses' => 'ContractsController@index' , 
            'as' => 'contracts.index'
        ]);

        Route::get('/contracts/create', [
            'uses' => 'ContractsController@create' , 
            'as' => 'contracts.create'
        ]);

        Route::post('/contracts/store', [
            'uses' => 'ContractsController@store' , 
            'as' => 'contracts.store'
        ]);

        Route::get('/contracts/edit/{id}', [
            'uses' => 'ContractsController@edit' , 
            'as' => 'contracts.edit'
        ]);

        Route::match(array('PUT', 'PATCH'), '/contracts/update/{id}', [
            'uses' => 'ContractsController@update' , 
            'as' => 'contracts.update'
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

        Route::get('/cususers/{id}', [
            'uses' => 'CustomersController@cususers' , 
            'as' => 'cususers.index'
        ]);

        Route::delete('/cususers/{id}', [
            'uses' => 'CustomersController@cususerdestroy' , 
            'as' => 'cususers.cususerdestroy'
        ]);

        Route::get('/cususers/new/create', [
            'uses' => 'CustomersController@cuscreate' , 
            'as' => 'cususers.create'
        ]);

        Route::post('/cususers/new/store', [
            'uses' => 'CustomersController@cusstore' , 
            'as' => 'cususers.store'
        ]);
    // });
    //////Customer portal
    // Route::group(['middleware' => 'auth.custclerk'] , function () {
        //dashboard
        Route::get('/portal/dashboard', 
            ['uses' => 'DashboardController@customer' , 
            'as' => 'dashboard.customer']
        );
        //users
        
        Route::get('/portal/users/profile', [
            'uses' => 'CusportalController@getProfile',
            'as' => 'portal.users.profile'
        ]);

        Route::get('/portal/users/{id}/edit', [
            'uses' => 'CusportalController@editUser' , 
            'as' => 'portal.users.edit'
        ]);

        Route::match(array('PUT', 'PATCH'), '/portal/users/{id}', [
            'uses' => 'CusportalController@updateUser' , 
            'as' => 'portal.users.update'
        ]);

        Route::get('/portal/users/resetpass', [
            'uses' => 'CusportalController@resetpass',
            'as' => 'portal.users.resetindividualpass'
        ]);

        Route::post('/portal/users/resetpass', [
            'uses' => 'CusportalController@postResetpass',
            'as' => 'portal.users.postResetindividualpass'
        ]);

        //shipment transactions
        Route::get('/portal/shipments', [
            'uses' => 'CusportalController@getShipments',
            'as' => 'portal.shipments.index'
        ]);

        Route::post('/portal/shipments', [
            'uses' => 'CusportalController@getShipments',
            'as' => 'portal.shipments.index'
        ]);

        Route::get('/portal/shipments/add', [
            'uses' => 'CusportalController@addShipment',
            'as' => 'portal.shipments.add'
        ]);

        Route::post('/portal/shipments/store', [
            'uses' => 'CusportalController@storeShipment' , 
            'as' => 'portal.shipments.store'
        ]);

        Route::get('/portal/shipment/{txn}/edit', [
            'uses' => 'CusportalController@edit' , 
            'as' => 'portal.shipments.edit'
        ]);

        Route::get('/portal/shipment/print/{awb}', [
            'uses' => 'CusportalController@print_awb' , 
            'as' => 'portal.shipments.print'
        ]);

        Route::match(array('PUT', 'PATCH'), '/portal/shipment/{awb}', [
            'uses' => 'CusportalController@update' , 
            'as' => 'portal.shipments.update'
        ]);

        Route::match(array('PUT', 'PATCH'), '/portal/shipment/cancel/{awb}', [
            'uses' => 'CusportalController@cancel' , 
            'as' => 'portal.shipments.cancel'
        ]);

        //Rates
        Route::get('/portal/rates', [
            'uses' => 'CusportalController@getRates',
            'as' => 'portal.rates.index'
        ]);

        //Awb search

        Route::get('/portal/awb', [
            'uses' => 'CusportalController@getAwb',
            'as' => 'portal.shipments.awb'
        ]);

        Route::post('/portal/awb', [
            'uses' => 'CusportalController@getAwb',
            'as' => 'portal.shipments.awb'
        ]);

        //Parcels
        Route::get('/portal/parcel', [
            'uses' => 'CusportalController@getParcels',
            'as' => 'portal.parcel.index'
        ]);

        //Customer admin portal
        // Route::group(['middleware' => 'auth.custadmin'] , function () {
            Route::get('/portal/users', [
                'uses' => 'CusportalController@cususers' , 
                'as' => 'portal.users.index'
            ]);

            Route::get('/portal/users/create', [
                'uses' => 'CusportalController@cuscreate' , 
                'as' => 'portal.users.create'
            ]);

            Route::post('/portal/users/store', [
                'uses' => 'CusportalController@cusstore' , 
                'as' => 'portal.users.store'
            ]);

            //Company edit
            Route::get('/portal/company/{id}/edit', [
                'uses' => 'CusportalController@editCompany' , 
                'as' => 'portal.company.edit'
            ]);

            Route::match(array('PUT', 'PATCH'), '/portal/company/{id}', [
                'uses' => 'CusportalController@updateCompany' , 
                'as' => 'portal.company.update'
            ]);
        // });
        ///end custadmin portal
    // });

    //////End Customer portal

    // Route::group(['middleware' => 'auth.couradmin'] , function () {
        //stations admin        
        Route::resource('customer', 'CustomersController');

        //stations admin        
        Route::resource('station', 'StationsController');

        //zones admin        
        Route::resource('zone', 'ZonesController');

        //vehicles admin        
        Route::resource('vehicle', 'VehiclesController');

        //parcels admin        
        Route::resource('parcel', 'ParcelsController');

        //users admin
        Route::resource('users', 'UsersController');
    // });
});
