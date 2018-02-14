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

Route::group(['middleware' => 'auth.jwt'], function () {
	Route::post('/txn', [
         'uses' => 'TxnsController@postcreateTxn',
         'as' => 'txn.create'
    ]);

    Route::get('/txn/created', [
         'uses' => 'TxnsController@getcreatedTxn',
         'as' => 'txn.listcreated'
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

});
