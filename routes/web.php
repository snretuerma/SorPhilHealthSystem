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
    return view('auth.login');
});

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', 'AdminController@index')->name('admin');
    Route::get('resetadmin', 'AdminController@resetViewAdmin')->name('resetadmin');
    Route::post('resetpassadmin', 'AdminController@resetPassAdmin');
    //Budget
    Route::get('/adminbudget', 'AdminController@budget')->name('adminbudget');
    Route::get('adminbudget_get', 'AdminController@getBudget');
    Route::post('adminedit_budget/{id}', 'AdminController@editBudget');
    Route::post('adminadd_budget', 'AdminController@addBudget');
    Route::post('budget_import', 'AdminController@importExcel');
    Route::get('/budget_export', 'AdminController@exportExcel');

    //Staffs
    Route::get('/adminPersonnel', 'AdminController@personnel')->name('adminPersonnel');
    Route::get('personnel_get', 'AdminController@getPersonnels');
    Route::post('add_personnel', 'AdminController@addPersonnel');
    Route::post('edit_personnel/{id}', 'AdminController@editPersonnel');
    Route::post('personnels_import', 'AdminController@importExcel');
    Route::get('/personnels_export', 'AdminController@exportExcel');

    //Patient
    Route::get('/adminPatient', 'AdminController@patient')->name('adminPatient');
    Route::get('patients_get', 'AdminController@getPatient');
    Route::post('patient_add', 'AdminController@addPatient');
    Route::post('patient_edit/{id}', 'AdminController@editPatient');
    //Record
    Route::post('patients_import', 'AdminController@importExcel');
    Route::get('/patients_export', 'AdminController@exportExcel');

    Route::get('/adminrecord', 'AdminController@record')->name('adminrecord');
    Route::get('adminrecord_get', 'AdminController@getRecord');
    Route::get('adminrecord_get1', 'AdminController@getRecord1');
    Route::get('adminpersonnel_get/{id}', 'AdminController@getPersonnel');

    //Users
    Route::get('/adminUsers', 'AdminController@users')->name('users');
    Route::get('hospitals_get', 'AdminController@getHospitals');
    Route::post('add_hospital', 'AdminController@addHospital');
    Route::post('hospital_edit/{id}', 'AdminController@editHospital');
    Route::post('add_user', 'AdminController@addUser');
    Route::post('user_edit/{id}', 'AdminController@editUser');
    Route::post('user_edit_resetpass/{id}', 'AdminController@editUserResetPass');
    //Route::post('add_patient', 'AdminController@addPatient');
    //Route::post('edit_patient/{id}', 'AdminController@editPatient');
});
Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
    Route::get('/', 'UserController@index')->name('user');
    Route::get('reset', 'UserController@resetView')->name('reset');
    Route::post('resetpass', 'UserController@resetPass');

    //Doctors
    Route::get('/doctors', 'UserController@doctors')->name('doctors');
    //Budget

    //Staffs
    Route::get('/doctors', 'UserController@doctors')->name('doctors');
    Route::get('doctors_get', 'UserController@getDoctors');
    Route::post('add_personnel', 'UserController@addPersonnel');
    Route::post('/edit_personnel/{id}', 'UserController@editPersonnel');
    Route::post('personnel_delete/{id}', 'UserController@deletePersonnel');
    Route::post('personnels_import', 'UserController@importExcel');
    Route::get('/personnels_export', 'UserController@exportExcel');
    Route::post('personnelcontribution_get', 'UserController@getPersonnelContribution');

    //Patients

    //Records
    Route::get('/records', 'UserController@records')->name('records');

    //Restore
    Route::get('/restore', 'UserController@restore')->name('restore');
    Route::get('restore_get', 'UserController@getRestore');//to get deleted medical records
    Route::post('edit_restore/{id}', 'UserController@editRestore');

    Route::get('get_common_disease', 'UserController@getCommonDisease');

    //Dashboard
    Route::get('recentMedicalRecord_get', 'UserController@getRecentMedicalRecord');
    Route::get('recentContribution_get', 'UserController@getRecentContribution');
    //Contribution
    Route::post('contrirecord_add', 'UserController@addContributionRecord');
    Route::post('contribution_delete/{id}', 'UserController@deleteContribution');
    Route::post('contribution_edit/{id}', 'UserController@editContribution');

    //Setting
    Route::get('/setting', 'UserController@setting')->name('setting');
    Route::post('update_setting', 'UserController@updateSetting');

});
Route::group(['prefix' => 'observer', 'middleware' => 'auth'], function () {
    Route::get('/', 'ObserverController@index')->name('observer');

    //Budget
    Route::get('/budget', 'ObserverController@budgetView')->name('observerBudgetView');
    Route::get('observerBudgetList', 'ObserverController@budgetList');

    //Staffs
    Route::get('/personnels', 'ObserverController@personnelsView')->name('observerPersonnelsView');
    Route::get('observerPersonnelList', 'ObserverController@personnelsList');

    //Patients
    Route::get('/patients', 'ObserverController@patientsView')->name('observerPatientsView');
    Route::get('observerPatientList', 'ObserverController@patientsList');

    //Records
    Route::get('/records', 'ObserverController@recordsView')->name('observerRecordsView');
    Route::get('observerRecordList', 'ObserverController@recordsList');
    Route::get('observerPersonnelList/{id}', 'ObserverController@personnelsListOnRecords');

    //Users
    Route::get('/users', 'ObserverController@usersView')->name('observerUsersView');
    Route::get('observerHospitalList', 'ObserverController@hospitalsList');

    //Reset Password
    Route::get('/reset-password', 'ObserverController@resetPasswordView')->name('observerResetPasswordView');
    Route::post('resetPassObserver', 'ObserverController@resetPass');
});
Route::get('/home', 'HomeController@index')->name('home');
