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

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('/', 'AdminController@index')->name('admin');
});
Route::group(['prefix' => 'user', 'middleware' => 'auth'], function() {
    Route::get('/', 'UserController@index')->name('user');
    Route::get('/patients', 'UserController@patients')->name('patients');
});
Route::group(['prefix' => 'observer', 'middleware' => 'auth'], function() {
    Route::get('/', 'ObserverController@index')->name('observer');
});
Route::get('/home', 'HomeController@index')->name('home');

<<<<<<< HEAD

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
=======
>>>>>>> 6e0b81e8909d244bda30b2b7ebb9f750ff729a3a
