<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Hospital;
use App\Models\Budget;
use Auth;
use DB;
use Dotenv\Store\File\Paths;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PatientImport;
use App\Exports\PatientExport;
use App\Imports\BudgetImport;
use App\Exports\BudgetExport;
use Carbon\Carbon;

use Illuminate\Http\Request;
//use Illuminate\Http\UploadedFile;



class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:user');
        date_default_timezone_set('Asia/Manila');
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
    
    public function getPatients()
    {
        return Patient::where('hospital_id', Auth::user()->hospital_id)->get();
    }
    public function getBudget()
    {
        return Budget::where('hospital_id', Auth::user()->hospital_id)->orderby('start_date','desc')->get();
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
    }
    public function editPatients(Request $request)
    {
        $patient = Patient::where('id', $request->id)->first();
        $date = Carbon::parse($request->birthdate)->format('Y-m-d');
        $patient->first_name = $request->first_name;
        $patient->middle_name = $request->middle_name;
        $patient->last_name = $request->last_name;
        $patient->name_suffix = $request->name_suffix;
        $patient->sex = $request->sex;
        $patient->birthdate = $date;
        $patient->marital_status = $request->marital_status;
        $patient->philhealth_number = $request->philhealth_number;
        return $patient->save();
    }
    public function editBudget(Request $request)
    {
        $budget = Budget::find($request->id);
        $budget->total=$request->total;
        $budget->start_date=Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        $budget->end_date=Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        $budget->save();
    }

    public function deletePatients(Request $req)
    {
        return Patient::where('id', $req->id)->delete();
    }
    public function deleteBudget(Request $req)
    {
        return Budget::where('id', $req->id)->delete();
    }
    public function importPatients(Request $request)
    {
        try {
            $import = new PatientImport;
            $postData = request()->file('patients');
            Excel::import($import, $postData[0]);
            return $import->getRowCount_imported().'/'.$import->getRowCount();
        } catch (\Error $ex) {
            return "Error, something went wrong!";
        }
    }
    public function exportPatients(Request $request) 
    {
        $date = Carbon::now()->format('Ymd_His');

        if(isset($_GET['exceltype']) && $_GET['exceltype'] != ""){
            if($_GET['exceltype'] == "csv"){
                return Excel::download(new PatientExport, 'PatientExportData_'.$date.'.csv');
            }elseif($_GET['exceltype'] == "xlsx"){
                return Excel::download(new PatientExport, 'PatientExportData_'.$date.'.xlsx');
            }elseif($_GET['exceltype'] == "xls"){
                return Excel::download(new PatientExport, 'PatientExportData_'.$date.'.xls');
            }
        }
    }
    public function importBudget(Request $request)
    {
        try {
            $import = new BudgetImport;
            $postData = request()->file('budgets');
            Excel::import($import, $postData[0]);
            return $import->getRowCount();
        } catch (\Error $ex) {
            return "Error, something went wrong!";
        }
    }
    public function exportBudget(Request $request) 
    {
        $date = Carbon::now()->format('Ymd_His');

        if(isset($_GET['exceltype']) && $_GET['exceltype'] != ""){
            if($_GET['exceltype'] == "csv"){
                return Excel::download(new BudgetExport, 'BudgetExportData_'.$date.'.csv');
            }elseif($_GET['exceltype'] == "xlsx"){
                return Excel::download(new BudgetExport, 'BudgetExportData_'.$date.'.xlsx');
            }elseif($_GET['exceltype'] == "xls"){
                return Excel::download(new BudgetExport, 'BudgetExportData_'.$date.'.xls');
            }
        }
    }
	
}

