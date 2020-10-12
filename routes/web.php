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
    Route::get('/adminbudget', 'AdminController@budget')->name('adminbudget');
    Route::get('adminbudget_get', 'AdminController@getBudget');


    Route::post('adminedit_budget/{id}', 'AdminController@editBudget');
    Route::post('adminadd_budget', 'AdminController@addBudget');

    Route::get('/adminrecord', 'AdminController@record')->name('adminrecord');
    Route::get('adminrecord_get', 'AdminController@getRecord');
    Route::get('adminrecord_get1', 'AdminController@getRecord1');

    Route::get('adminpatient_get', 'AdminController@getPatient');
});
Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
    Route::get('/', 'UserController@index')->name('user');
    //Budget
    Route::get('/budget', 'UserController@budget')->name('budget');
    Route::get('budget_get', 'UserController@getBudget');
    Route::post('add_budget', 'UserController@addBudget');
    Route::post('/edit_budget/{id}', 'UserController@editBudget');
    Route::post('delete_budget/{id}', 'UserController@deleteBudget');
    //Staffs
    Route::get('/personnel', 'UserController@personnel')->name('personnel');
    Route::get('personnel_get', 'UserController@getPersonnel');
    Route::post('add_personnel', 'UserController@addPersonnel');
    Route::post('personnel_delete/{id}', 'UserController@deletePersonnel');
    //Patients
    Route::get('/patients', 'UserController@patients')->name('patients');
    Route::get('patients_get', 'UserController@getPatients');
    Route::post('add_patient', 'UserController@addPatient');
    Route::post('patients_delete/{id}', 'UserController@deletePatients');
    Route::post('patients_edit/{id}', 'UserController@editPatients');
    //Records
    Route::get('/record', 'UserController@record')->name('record');
    Route::get('record_get', 'UserController@getRecord');
    Route::post('personnel_get/{id}', 'UserController@getPersonnel');
    Route::post('delete_record/{id}', 'UserController@deleteRecord');

    //Restore 
    Route::get('/restore', 'UserController@restore')->name('restore');
    Route::get('restore_get', 'UserController@getRestore');//to get deleted medical records
    Route::post('edit_restore/{id}', 'UserController@editRestore');
});
Route::group(['prefix' => 'observer', 'middleware' => 'auth'], function () {
    Route::get('/', 'ObserverController@index')->name('observer');
});
Route::get('/home', 'HomeController@index')->name('home');
