<?php

use Illuminate\Support\Facades\Route;
use App\Events\OrderStatusChanged;
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
/**
* Authentication routes
*/

Route::group(['namespace' => 'Admin'], function () {

   // Route::get('/', 'DashboardController@index');
    Route::get('login', 'AuthController@getIndex')->name('login');
    Route::get('logout', 'AuthController@getLogout');
    Route::post('login', 'AuthController@postLogin')->name('login');;

});



Route::group([
    'namespace' => 'Admin',
    'middleware' => 'auth'
], function () {
    //dashboard
    Route::get('/', 'DashboardController@index');

    //users
    Route::post('/users/search', 'UsersController@search')->name('users.search');
    Route::resource('/users', 'UsersController');
});




// Demo routes

Route::get('/datatables', 'PagesController@datatables');
Route::get('/ktdatatables', 'PagesController@ktDatatables');
Route::get('/select2', 'PagesController@select2');
Route::get('/jquerymask', 'PagesController@jQueryMask');
Route::get('/icons/custom-icons', 'PagesController@customIcons');
Route::get('/icons/flaticon', 'PagesController@flaticon');
Route::get('/icons/fontawesome', 'PagesController@fontawesome');
Route::get('/icons/lineawesome', 'PagesController@lineawesome');
Route::get('/icons/socicons', 'PagesController@socicons');
Route::get('/icons/svg', 'PagesController@svg');

// Quick search dummy route to display html elements in search dropdown (header search)
Route::get('/quick-search', 'PagesController@quickSearch')->name('quick-search');
