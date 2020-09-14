<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'admin_routes', 'prefix' => 'admin'], function()
{
    Auth::routes();
    Route::match(['get', 'post'], '/', 'AdminController@index')->name('admin');
    // Route::get('/admin', 'AdminController@index')->name('admin');
});

Route::get('/user', 'UserController@index')->name('user');

Route::get('/superadmin', 'SuperAdminController@index')->name('superadmin');
Route::get('/observer', 'ObserverController@index')->name('observer');
Auth::routes();

