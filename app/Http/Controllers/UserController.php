<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Budget;
use App\Models\Personnel;
use App\Models\Hospital;
use Auth;
use DB;
use Dotenv\Store\File\Paths;;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\addPatientRequest;
use App\Http\Requests\addPersonnelRequest;

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
    //Budget
    public function budget()
    {
        return view('roles.user.budget');
    }
    //Staffs
    public function personnel()
    {
        return view('roles.user.personnel');
    }

    public function getPersonnel()
    {
        return Personnel::where('hospital_id', Auth::user()->hospital_id)->get();
    }

    public function addPersonnel(addPersonnelRequest $request)
    {
        $date = Carbon::parse($request->birthdate)->format('Y-m-d');
        $personnel = new Personnel;
        $personnel->first_name = $request->first_name;
        $personnel->middle_name = $request->middle_name;
        $personnel->last_name = $request->last_name;
        $personnel->name_suffix = $request->name_suffix;
        $personnel->sex = $request->sex;
        $personnel->birthdate = $date;
        $personnel->hospital()->associate(Hospital::find(1)->id);
        $personnel->save();
    }

    public function deletePersonnel(Request $req)
    {
        return Personnel::where('id', $req->id)->delete();
    }
    //Patients

    public function patients()
    {
        return view('roles.user.patients');
    }
    public function getPatients()
    {
        return Patient::where('hospital_id', Auth::user()->hospital_id)->get();
    }
    public function getBudget()
    {
        return Budget::where('hospital_id', Auth::user()->hospital_id)->get();
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
        $patient->hospital()->associate(Hospital::find($request->id)->id);
        $patient->save();
    }
    public function addBudget(Request $request)
    {
        $budget = new Budget;
        $startdate = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        $enddate= Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        $budget->start_date=$startdate;
        $budget->total = $request->total;
        $budget->end_date=$enddate;
        $budget->hospital()->associate(Hospital::find(auth()->user()->hospital_id)->id);
        $budget->save();
    }

    public function editPatient(Request $request)
    {
        $patient = Patient::where('id', $request->id)->first();
    }
    public function editBudget(Request $request)
    {
        $budget =Budget::find($request->id);
        $budget->total = $request->total;
        $budget->start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        $budget->end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        $budget->save();
        
    }

    public function deletePatients(Request $req)
    {
        return Patient::where('id', $req->id)->delete();
    }
    public function deleteBudget(Request $req)
    {
        return Budget::where('id', $req->id)->delete();
    //Records
    }
    public function records()
    {
        return view('roles.user.records');
    }
}
