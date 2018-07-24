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

Route::post('/user/signin', [
    'uses' => 'UsersController@signin',
    'as' => 'user.signin'
]);

Route::post('/user/rider/signin', [
    'uses' => 'RiderController@signinRider',
    'as' => 'user.rider.signin'
]);

Route::group(['middleware' => 'auth.jwt'], function () {
	Route::post('/txn', [
         'uses' => 'TxnsController@postcreateTxn',
         'as' => 'txn.create'
    ]);

    Route::get('/txn/created', [
         'uses' => 'TxnsController@getcreatedTxn',
         'as' => 'txn.listcreated'
    ]);

    Route::get('/txn/booked', [
         'uses' => 'TxnsController@getbookedTxn',
         'as' => 'txn.listbooked'
    ]);

    Route::get('/txn/details/{id}', [
         'uses' => 'TxnsController@getTxnDet',
         'as' => 'txn.txndet'
    ]);

    Route::get('/txn/statusdetails/{id}', [
         'uses' => 'TxnsController@getTxnStatusDet',
         'as' => 'txn.statusdet'
    ]);

    Route::get('/txn/dailysumm', [
         'uses' => 'TxnsController@getDailySumm',
         'as' => 'txn.dailysumm'
    ]);

    Route::post('/txn/dispatch/{id}', [
         'uses' => 'TxnsController@postdispatchTxn',
         'as' => 'txn.dispatch'
    ]);

    Route::post('/txn/dispatches', [
         'uses' => 'TxnsController@postdispatchTxns',
         'as' => 'txn.dispatches'
    ]);

    Route::get('/txn/origindispatched', [
         'uses' => 'TxnsController@getorigindispatchedTxn',
         'as' => 'txn.listorigindispatched'
    ]);

    Route::get('/txn/destdispatched', [
         'uses' => 'TxnsController@getdestdispatchedTxn',
         'as' => 'txn.listdestdispatched'
    ]);

    Route::post('/txn/deliver/{id}', [
         'uses' => 'TxnsController@postdeliverTxn',
         'as' => 'txn.deliver'
    ]);

    Route::post('/txn/deliveries', [
         'uses' => 'TxnsController@postdeliverTxns',
         'as' => 'txn.deliveries'
    ]);

    Route::get('/txn/delivered', [
         'uses' => 'TxnsController@getdeliveredTxn',
         'as' => 'txn.listdelivered'
    ]);

    Route::post('/txn/receive/{id}', [
         'uses' => 'TxnsController@postreceiveTxn',
         'as' => 'txn.receive'
    ]);

    Route::get('/txn/received', [
         'uses' => 'TxnsController@getreceivedTxn',
         'as' => 'txn.listreceived'
    ]);

    Route::get('/user/{username}', [
        'uses' => 'UsersController@getuserdetails'
    ]);

    Route::post('/user/{username}/changepassword', [
        'uses' => 'UsersController@changePassword'
    ]);

    Route::get('/getdrivers', [
        'uses' => 'UsersController@getdrivers'
    ]);

    Route::get('/getvehicles', [
        'uses' => 'VehiclesController@getvehicles'
    ]);

    Route::get('/tostations/{id}', [
    	'uses' => 'StationsController@getstations'
    ]);

    Route::get('/parceltypes', [
         'uses' => 'ParcelsController@getparcelTypes',
         'as' => 'parcel.parceltypes'
    ]);

    //Rider routes
    Route::get('/riderstations', [
        'uses' => 'RiderController@getRiderstations',
        'as' => 'rider.stations'
    ]);

    Route::get('/ridercustomers', [
        'uses' => 'RiderController@getRidercustomers',
        'as' => 'rider.customers'
    ]);

    Route::get('/riderparceltypes', [
         'uses' => 'RiderController@getRiderparcelTypes',
         'as' => 'rider.parceltypes'
    ]);

    Route::get('/user/rider/{username}', [
        'uses' => 'RiderController@getRideruserdetails',
        'as' => 'rider.userdetails'
    ]);

    Route::post('/rider/txn', [
         'uses' => 'RiderController@postRidercreateTxn',
         'as' => 'rider.postcreatetxn'
    ]);

    Route::get('/rider/txn/booked', [
         'uses' => 'RiderController@getRiderbookedTxn',
         'as' => 'rider.listbooked'
    ]);

    Route::get('/rider/txn/listpickups', [
         'uses' => 'RiderController@getRiderpickupTxn',
         'as' => 'rider.listpickups'
    ]);

    Route::get('/rider/txn/listdrops', [
         'uses' => 'RiderController@getRiderdropTxn',
         'as' => 'rider.listdrops'
    ]);

    Route::get('/rider/txn/completedpickups', [
         'uses' => 'RiderController@getcompletedRiderpickups',
         'as' => 'rider.completedpickups'
    ]);

    Route::get('/rider/txn/completeddrops', [
         'uses' => 'RiderController@getcompletedRiderdrops',
         'as' => 'rider.completeddrops'
    ]);

    Route::get('/rider/txn/booked/cust/{id}', [
         'uses' => 'RiderController@getRidercustbookedTxn',
         'as' => 'rider.listcustbooked'
    ]);

    Route::post('/rider/txn/pick', [
         'uses' => 'RiderController@postRiderpickTxn',
         'as' => 'rider.postpicktxn'
    ]);

    Route::get('/rider/txn/picked', [
         'uses' => 'RiderController@getRiderpickedTxn',
         'as' => 'rider.listpicked'
    ]);

    Route::get('/rider/txn/picked/cust/{id}', [
         'uses' => 'RiderController@getRidercustpickedTxn',
         'as' => 'rider.listcustpicked'
    ]);

    Route::post('/rider/txn/receive/{id}', [
         'uses' => 'RiderController@postRiderreceiveTxn',
         'as' => 'rider.postreceivetxn'
    ]);

    Route::get('/rider/txn/received', [
         'uses' => 'RiderController@getRiderreceivedTxn',
         'as' => 'rider.listreceived'
    ]);

    Route::get('/rider/txn/received/cust/{id}', [
         'uses' => 'RiderController@getRidercustreceivedTxn',
         'as' => 'rider.listcustreceived'
    ]);

    Route::get('/rider/txn/details/{id}', [
         'uses' => 'RiderController@getRiderTxnDet',
         'as' => 'rider.txndet'
    ]);

    Route::get('/rider/txn/dailysumm', [
         'uses' => 'RiderController@getRiderDailySumm',
         'as' => 'rider.dailysumm'
    ]);

});
