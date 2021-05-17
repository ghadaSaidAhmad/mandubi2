<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Events\OrderStatusChanged;

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

/*======= unauthenticated Route =======*/

//governorates Route 
Route::resource('/governorates','Api\GovernorateController')->only(['index','show']);
Route::get('/governorates/{id}/cities','Api\GovernorateController@showCities');

//shippingTypes Route 
Route::resource('/shippingTypes','Api\ShippingTypeController')->only(['index']);
Route::get('/ShippingSpecifications','Api\ShippingSpecificationsController@index');
//shippingTypes Route 
Route::resource('/orderStates','Api\OrderStateContrller')->only(['index']);
Route::post('/client/trackOrder', "Api\ClientController@trackOrder");
Route::get('/getPaymentMethods','Api\ListsController@getPaymentMethods');





/*======= End unauthenticated Route =======*/
//client Route
Route::post('/client/login', "Api\Auth\ClientController@login");
Route::post('/client/register', "Api\Auth\ClientController@register");
Route::post('/client/completeRegister', "Api\Auth\ClientController@completeRegister");
Route::post('/client/verifyMobile', "Api\Auth\ClientController@verifyMobile");




//mandoub Route
Route::post('/mandub/login', "Api\Auth\MandubController@login");
Route::post('/mandub/register', "Api\Auth\MandubController@register");
Route::post('/mandub/verifyMobile', "Api\Auth\MandubController@verifyMobile");

// Only for customers
Route::middleware(['auth:clients','user.verify'])->group(function () {
    Route::get('/client/messages','Api\ClientController@getMessages');
    Route::get('/client/orders', "Api\ClientController@getOrders");
    Route::post('/client/filterOrders', "Api\ClientController@filterOrders");
    Route::post('/client/addOrder', "Api\ClientController@addOrder");
    Route::get('/client/mandubs', "Api\MandubController@getAll");
    Route::post('/client/acceptMandub', "Api\ClientController@acceptMandub");
    Route::post('/client/showOrder', "Api\ClientController@showOrder");
    Route::post('/client/updateFcmToken', "Api\ClientController@updateFcmToken");
    Route::post('/client/updateInfo', "Api\ClientController@updateInfo");
    Route::get('/client/showMandub/{id}', "Api\ClientController@showMandub");

    Route::get('/client/{id}', "Api\ClientController@show");
    Route::get('/client/{id}/{notification}', "Api\ClientController@showNotification");
    Route::post('/client/rateMandub', "Api\ClientController@rateMandub");
    Route::post('/client/addSuggation', "Api\AddSuggationController@AddClientSuggation");
    Route::get('/client/mandubs', "Api\ClientController@getMandubs");



});

//mandub route 
Route::group(['middleware' => 'auth:mandubs','user.verify'], function () {

    Route::get('/mandub/messages','Api\MandubController@messages');
    Route::get('/mandub/orders', "Api\MandubController@getOrders");    
    Route::post('/mandub/filterOrders', "Api\MandubController@filterOrders");
    Route::post('/mandub/setActive', "Api\MandubController@setActive");
    Route::get('/mandub/getState', "Api\MandubController@getState");
    Route::post('/mandub/uploadReceipt', "Api\PaymentReceiptController@uploadReceipt");
    Route::post('/mandub/updateFcmToken', "Api\MandubController@updateFcmToken");
    Route::post('/mandub/updateInfo', "Api\MandubController@updateInfo");
    Route::get('/mandub/{id}', "Api\MandubController@show");
    Route::get('/mandub/showClient/{id}', "Api\MandubController@showclient");
    Route::get('/mandub/{id}/{notification}', "Api\MandubController@showNotification");
    Route::post('/mandub/rateClient', "Api\MandubController@rateClient");
    Route::post('/mandub/addSuggation', "Api\AddSuggationController@AddMandubSuggation");


    //order cycle

    Route::group(['middleware' => 'user.AllowedBalance'], function () {
        Route::post('/mandub/acceptOrder', "Api\MandubController@acceptOrder");
        Route::post('/mandub/startTrip', "Api\MandubController@startTrip");
        Route::post('/mandub/arrived', "Api\MandubController@arrived");
        Route::post('/mandub/enterArrivedCode', "Api\MandubController@enterArrivedCode");
        Route::post('/mandub/cancelTrip', "Api\MandubController@cancelTrip");
        Route::post('/mandub/backToClient', "Api\MandubController@backToClient");
        Route::post('/mandub/mandubArrived', "Api\MandubController@mandubArrived");
        Route::post('/mandub/enterDeliveryCode', "Api\MandubController@enterDeliveryCode");

        Route::post('/mandub/showOrder', "Api\MandubController@showOrder");
        Route::post('/mandub/getMandubBalance', "Api\MandubController@getMandubBalance");



    });

});


