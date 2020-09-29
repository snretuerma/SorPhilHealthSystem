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
    //Budget
    Route::get('/budget', 'UserController@budget')->name('budget');
    //Staffs
    Route::get('/personnel', 'UserController@personnel')->name('personnel');
    Route::get('personnel_get', 'UserController@getPersonnel');
    Route::post('add_personnel', 'UserController@addPersonnel');
    Route::post('personnel_delete/{id}', 'UserController@deletePersonnel');
    //Patients
    Route::get('/patients', 'UserController@patients')->name('patients');
    Route::post('add_patient', 'UserController@addPatient');
    Route::get('patients_get', 'UserController@getPatients');
    Route::post('patients_delete/{id}', 'UserController@deletePatients');
    Route::post('/edit_patient/{id}', 'UserController@editPatient');
    //Records
    Route::get('/records', 'UserController@records')->name('records');
    
});
Route::group(['prefix' => 'observer', 'middleware' => 'auth'], function () {
    Route::get('/', 'ObserverController@index')->name('observer');
});
Route::get('/home', 'HomeController@index')->name('home');
