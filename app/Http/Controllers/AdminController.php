<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Budget;
use App\Models\Hospital;
use App\Models\Personnel;
use App\Models\User;
use App\Models\MedicalRecord;
use Auth;
use DB;
use Dotenv\Store\File\Paths;;

use App\Http\Requests\adminAddBudgetRequest;
use App\Http\Requests\adminAddPatientRequest;
use App\Http\Requests\adminAddPersonnelRequest;
use App\Http\Requests\adminEditBudgetRequest;
use App\Http\Requests\adminEditPatientRequest;
use App\Http\Requests\adminEditPersonnelRequest;
use App\Http\Requests\resetPassRequest;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        return view('roles.admin.index');
    }

    public function resetViewAdmin()
    {
        return view('roles.admin.reset');
    }

    public function resetPassAdmin(resetPassRequest $request)
    {
        $new_pass = User::find(Auth::user()->id);
        $new_pass->password = Hash::make($request->password);
        $new_pass->save();
    }

    //Personnel
    public function personnel()
    {
        return view('roles.admin.personnel');
    }

    public function getPersonnel()
    {
        $personnel = DB::table('personnels')
            ->join('hospitals', 'personnels.hospital_id', '=', 'hospitals.id')
            ->select('personnels.*', 'hospitals.hospital_code')
            ->whereNull('personnels.deleted_at')
            ->get();
        return $personnel;
    }

    public function addPersonnel(adminAddPersonnelRequest $request)
    {
        $personnel = new Personnel;
        $date = Carbon::parse($request->birthdate)->format('Y-m-d');
        $personnel->birthdate = $date;
        $personnel->first_name = $request->first_name;
        $personnel->middle_name = $request->middle_name;
        $personnel->last_name = $request->last_name;
        $personnel->name_suffix = $request->name_suffix;
        $personnel->is_private = $request->is_private;
        $personnel->sex = $request->sex;
        $personnel->hospital_id = $request->codeholder;
        $personnel->save();
        return $personnel;
    }

    public function editPersonnel(adminEditPersonnelRequest $request)
    {
        $personnel = Personnel::find($request->id);
        $date = Carbon::parse($request->birthdate)->format('Y-m-d');
        $personnel->birthdate = $date;
        $personnel->first_name = $request->first_name;
        $personnel->middle_name = $request->middle_name;
        $personnel->last_name = $request->last_name;
        $personnel->name_suffix = $request->name_suffix;
        $personnel->is_private = $request->is_private;
        $personnel->sex = $request->sex;
        $personnel->hospital_id = $request->codeholder;
        $personnel->save();
        return $personnel;
    }

    //Budget
    public function budget()
    {
        return view('roles.admin.budget');
    }

    public function record()
    {
        return view('roles.admin.record');
    }

    public function getBudget()
    {
        $budget = DB::table('budgets')
            ->join('hospitals', 'budgets.hospital_id', '=', 'hospitals.id')
            ->select('budgets.*', 'hospitals.hospital_code')
            ->whereNull('budgets.deleted_at')
            ->orderby('start_date', 'desc')
            ->get();
        return $budget;
    }

    public function addBudget(adminAddBudgetRequest $request)
    {
        $budget = new Budget;
        $startdate = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        $enddate = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        $budget->start_date = $startdate;
        $budget->total = $request->total;
        $budget->end_date = $enddate;
        $budget->hospital_id = $request->codeholder;
        $budget->save();
        return $budget;
    }

    public function editBudget(adminEditBudgetRequest $request)
    {
        $budget = Budget::find($request->id);
        $budget->total = $request->total;
        $budget->start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        $budget->end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        $budget->hospital_id = $request->codeholder;
        $budget->save();
        return $budget;
    }

    //Patient
    public function patient()
    {
        return view('roles.admin.patient');
    }

    public function getPatient()
    {
        $patient = DB::table('patients')
            ->join('hospitals', 'patients.hospital_id', '=', 'hospitals.id')
            ->select('patients.*', 'hospitals.hospital_code')
            ->whereNull('patients.deleted_at')
            ->get();
        return $patient;
    }

    public function addPatient(adminAddPatientRequest $request)
    {
        $patient = new Patient;
        $date = Carbon::parse($request->birthdate)->format('Y-m-d');
        $patient->first_name = $request->first_name;
        $patient->middle_name = $request->middle_name;
        $patient->last_name = $request->last_name;
        $patient->name_suffix = $request->name_suffix;
        $patient->sex = $request->sex;
        $patient->birthdate = $date;
        $patient->marital_status = $request->marital_status;
        $patient->philhealth_number = $request->philhealth_number;
        $patient->hospital_id = $request->codeholder;
        $patient->save();
        return $patient;
    }

    public function editPatient(adminEditPatientRequest $request)
    {
        $patient = Patient::find($request->id);
        $patient->first_name = $request->first_name;
        $patient->middle_name = $request->middle_name;
        $patient->last_name = $request->last_name;
        $patient->name_suffix = $request->name_suffix;
        $patient->sex = $request->sex;
        $patient->birthdate = Carbon::parse($request->birthdate)->format('Y-m-d');
        $patient->marital_status = $request->marital_status;
        $patient->philhealth_number = $request->philhealth_number;
        $patient->hospital_id = $request->codeholder;
        $patient->save();
        return $patient;
    }
    public function getRecord()
    {
        $result = MedicalRecord::join('patients as p', 'medical_records.patient_id', '=', 'p.id')
        ->get();
        return $result;
    }
}
