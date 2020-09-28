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
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', 'AdminController@index')->name('admin');
});
Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
    Route::get('/', 'UserController@index')->name('user');
    Route::get('/patients', 'UserController@patients')->name('patients');

    
    Route::get('patients_get', 'UserController@getPatients');
    Route::post('add_patient', 'UserController@addPatient');
    Route::post('patients_delete/{id}', 'UserController@deletePatients');
    Route::post('/edit_patient/{id}', 'UserController@editPatient');

    Route::get('/budget', 'UserController@budget')->name('budget');
    Route::get('budget_get', 'UserController@getBudget');
    Route::post('add_budget', 'UserController@addBudget');
    Route::post('/edit_budget/{id}', 'UserController@editBudget');
    Route::post('delete_budget/{id}', 'UserController@deleteBudget');
});
Route::group(['prefix' => 'observer', 'middleware' => 'auth'], function () {
    Route::get('/', 'ObserverController@index')->name('observer');
});
Route::get('/home', 'HomeController@index')->name('home');
