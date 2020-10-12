<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Hospital;
use App\Models\User;
use App\Models\Budget;
use App\Models\MedicalRecord;
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
    public function budget()
    {
        return view('roles.user.budget');
    }
    public function record()
    {
        return view('roles.user.record');
    }
    public function restore()
    {
        return view('roles.user.restore');
    }
    public function getPatients()
    {
        return Patient::where('hospital_id', Auth::user()->hospital_id)->get();
    }
    public function getBudget()
    {
        return Budget::where('hospital_id', Auth::user()->hospital_id)->orderby('start_date','desc')->get();
    }
    public function getRecord()
    {
        // $result = MedicalRecord::join('patients as p', 'medical_records.patient_id', '=', 'p.id')
        // ->join('hospitals as h','p.hospital_id','=','h.id')
        // ->join('records_personnels as rp','medical_records.id','=','rp.medical_record_id')
        // ->join('personnels as ps','rp.personnel_id','=','ps.id')
        // ->join('contributions as c','rp.contribution_id','=','c.id')
        // ->select('medical_records.id','p.philhealth_number as philhealth','p.first_name as pfname',
        // 'p.middle_name as pmname','p.last_name as plname','p.name_suffix as pnamesuffix',
        // 'ps.first_name as psfname','ps.middle_name as psmname','ps.last_name as pslname',
        // 'h.hospital_code as hcode','medical_records.admission_date','medical_records.discharge_date')
        // ->whereNull('medical_records.deleted_at')
       
        // ->getQuery() // Optional: downgrade to non-eloquent builder so we don't build invalid User objects.
        // ->get();
        $result = MedicalRecord::join('patients as p', 'medical_records.patient_id', '=', 'p.id')
        ->where('p.hospital_id', Auth::user()->hospital_id)
        ->get();
        return $result;
    }
    public function getPersonnel(Request $request){
        $result=MedicalRecord::select('*')->where('id',$request->id)
        ->with('personnels','contributions')->first();
        return response()->json($result);
    }
    public function getRestore()
    {
        $result = MedicalRecord::join('patients as p', 'medical_records.patient_id', '=', 'p.id')
        ->join('hospitals as h','p.hospital_id','=','h.id')
        ->join('records_personnels as rp','medical_records.id','=','rp.medical_record_id')
        ->join('personnels as ps','rp.personnel_id','=','ps.id')
        ->join('contributions as c','rp.contribution_id','=','c.id')
        ->select('medical_records.id','p.philhealth_number as philhealth','p.first_name as pfname',
        'p.middle_name as pmname','p.last_name as plname','p.name_suffix as pnamesuffix',
        'ps.first_name as psfname','ps.middle_name as psmname','ps.last_name as pslname',
        'h.hospital_code as hcode','medical_records.admission_date','medical_records.discharge_date')
        ->where('medical_records.deleted_at','<>','','and')
        ->where('h.id', Auth::user()->hospital_id)
        ->getQuery() // Optional: downgrade to non-eloquent builder so we don't build invalid User objects.
        ->get();
        return $result;
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
        return $budget;
    }
    public function editPatient(Request $request)
    {
        $patient = Patient::where('id', $request->id)->first();
    }
    public function editBudget(Request $request)
    {
        $budget = Budget::find($request->id);
        $budget->total=$request->total;
        $budget->start_date=Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        $budget->end_date=Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        $budget->save();
    }
    public function editRestore(Request $request)
    {
        $restore=MedicalRecord::withTrashed()->find($request->id)->restore();
        return $restore;
    }
    public function deletePatients(Request $req)
    {
        return Patient::where('id', $req->id)->delete();
    }
    public function deleteBudget(Request $req)
    {
        return Budget::where('id', $req->id)->delete();
    }
    public function deleteRecord(Request $req)
    {
        return MedicalRecord::where('id', $req->id)->delete();
    }
}

