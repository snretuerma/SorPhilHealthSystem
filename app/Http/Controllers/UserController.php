<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Hospital;
use Auth;
use DB;
use Dotenv\Store\File\Paths;;

use Carbon\Carbon;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:user');
    }

    public function index()
    {
        return view('roles.user.index');
    }

    public function patients()
    {
        return view('roles.user.patients');
    }

    public function getPatients()
    {
        return Patient::where('hospital_id', Auth::user()->hospital_id)->get();
    }

    public function addPatient(Request $request)
    {
        $date = Carbon::parse($request->birthdate)->format('Y-m-d');
        $patient = new Patient;
        $patient->first_name = $request->first_name;
        $patient->middle_name = $request->middle_name;
        $patient->last_name = $request->last_name;
        $patient->name_suffix = $request->name_suffix;
        $patient->sex = $request->sex;
        $patient->birthdate = $date;
        $patient->marital_status = $request->marital_status;
        $patient->philhealth_number = $request->philhealth_number;
        $patient->hospital()->associate(Hospital::find(1)->id);
        $patient->save();
    }

    public function editPatient(Request $request)
    {
        $patient = Patient::where('id', $request->id)->first();
    }

    public function deletePatients(Request $req)
    {
        return Patient::where('id', $req->id)->delete();
    }
}
