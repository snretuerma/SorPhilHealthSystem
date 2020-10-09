<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Hospital;
use App\Models\Budget;
use App\Models\Personnel;
use App\Models\User;
use Auth;
use DB;
use Dotenv\Store\File\Paths;
use App\Http\Requests\userAddBudgetRequest;
use App\Http\Requests\userAddPatientRequest;
use App\Http\Requests\userAddPersonnelRequest;
use App\Http\Requests\userEditBudgetRequest;
use App\Http\Requests\userEditPatientRequest;
use App\Http\Requests\userEditPersonnelRequest;
use App\Http\Requests\resetPassRequest;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function resetView()
    {
        return view('roles.user.reset');
    }

    public function resetPass(resetPassRequest $request)
    {
        $new_pass = User::find(Auth::user()->id);
        $new_pass->password = Hash::make($request->password);
        $new_pass->save();
    }

    //Budget
    public function budget()
    {
        return view('roles.user.budget');
    }

    public function getBudget()
    {
        return Budget::where('hospital_id', Auth::user()->hospital_id)->orderby('start_date', 'desc')->get();
    }

    public function addBudget(userAddBudgetRequest $request)
    {
        $budget = new Budget;
        $startdate = Carbon::parse($request->start_date)->format('Y-m-d');
        $enddate = Carbon::parse($request->end_date)->format('Y-m-d');
        $budget->start_date = $startdate;
        $budget->total = $request->total;
        $budget->end_date = $enddate;
        $budget->hospital()->associate(Hospital::find(auth()->user()->hospital_id)->id);
        $budget->save();
        return $budget;
    }

    public function editBudget(userEditBudgetRequest $request)
    {
        $budget = Budget::find($request->id);
        $budget->total = $request->total;
        $budget->start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $budget->end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        $budget->save();
    }

    public function deleteBudget(Request $request)
    {
        return Budget::where('id', $request->id)->delete();
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

    public function addPersonnel(userAddPersonnelRequest $request)
    {
        $date = Carbon::parse($request->birthdate)->format('Y-m-d');
        $personnel = new Personnel;
        $personnel->first_name = $request->first_name;
        $personnel->middle_name = $request->middle_name;
        $personnel->last_name = $request->last_name;
        $personnel->name_suffix = $request->name_suffix;
        $personnel->is_private = $request->is_private;
        $personnel->sex = $request->sex;
        $personnel->birthdate = $date;
        $personnel->hospital()->associate(Hospital::find(auth()->user()->hospital_id)->id);
        $personnel->save();
        return $personnel;
    }

    public function editPersonnel(userEditPersonnelRequest $request)
    {
        $personnel = Personnel::where('id', $request->id)->first();
        $personnel->first_name = $request->first_name;
        $personnel->middle_name = $request->middle_name;
        $personnel->last_name = $request->last_name;
        $personnel->name_suffix = $request->name_suffix;
        $personnel->is_private = $request->is_private;
        $personnel->sex = $request->sex;
        $personnel->birthdate = Carbon::parse($request->birthdate)->format('Y-m-d');
        $personnel->save();
    }

    public function deletePersonnel(Request $request)
    {
        return Personnel::where('id', $request->id)->delete();
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

    public function addPatient(userAddPatientRequest $request)
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
        $patient->hospital()->associate(Hospital::find(auth()->user()->hospital_id)->id);
        $patient->save();
        return $patient;
    }

    public function editPatient(userEditPatientRequest $request)
    {
        $patient = Patient::where('id', $request->id)->first();
        $patient->first_name = $request->first_name;
        $patient->middle_name = $request->middle_name;
        $patient->last_name = $request->last_name;
        $patient->name_suffix = $request->name_suffix;
        $patient->sex = $request->sex;
        $patient->birthdate = Carbon::parse($request->birthdate)->format('Y-m-d');
        $patient->marital_status = $request->marital_status;
        $patient->philhealth_number = $request->philhealth_number;
        $patient->save();
    }

    public function deletePatient(Request $request)
    {
        return Patient::where('id', $request->id)->delete();
    }
}
