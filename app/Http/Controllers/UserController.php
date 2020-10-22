<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Hospital;
use App\Models\User;
use App\Models\Budget;
use App\Models\Personnel;
use App\Models\MedicalRecord;
use App\Models\Contribution;
use Auth;
use DB;
use Dotenv\Store\File\Paths;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PatientImport;
use App\Exports\PatientExport;
use App\Imports\BudgetImport;
use App\Exports\BudgetExport;
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
        date_default_timezone_set('Asia/Manila');
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
    public function record()
    {
        return view('roles.user.record');
    }

    public function restore()
    {
        return view('roles.user.restore');
    }

    public function medicalrecord($id)
    {
        $total=MedicalRecord::find($id)->select('total_fee')->first();
        $data=[
            'id'=>$id,
            'total_fee'=>$total->total_fee
        ];
        return view('roles.user.medicalrecord')->with($data);
        // return ['redirect'=>route('/medicalrecord')];
    }

    public function getBudget()
    {
        return Budget::where('hospital_id', Auth::user()->hospital_id)->orderby('start_date','desc')->get();
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
 
    public function getPersonnels()
    {
        return Personnel::where('hospital_id', Auth::user()->hospital_id)->get();
    }
    
    public function getPersonnellist(Request $request)
    {
        if($request->query!='')
        {
        $users = DB::table('personnels')
                ->where('first_name', 'like', $request->get('query').'%')
                ->get();
        return $users;

        }
       
    }
    public function addContributionRecord(Request $request)
    {
        // dd($request);
        for($i=0;$i<sizeof($request->personnel);$i++){
            $contribution=new Contribution;
            $contribution->type=$request->personnel[$i]['stafftype'];
            $contribution->contribution=$request->personnel[$i]['contribution'];
            $contribution->credit=$request->personnel[$i]['computed_pf'];
            $contribution->status="paid";
            $contribution->save();
            $record=MedicalRecord::find($request->medical_record_id);
            $record->personnels()->attach(Personnel::find($request->personnel[$i]['staff']), ['contribution_id' => $contribution->id]);
           
        } return $contribution;
    }

    public function addMedicalRecord(Request $request)
    {
       $record=new MedicalRecord;
       $record->patient_id=$request->patient_id;
       $record->admission_date=$request->admission_date;
       $record->discharge_date=$request->discharge_date;
       $record->final_diagnosis=$request->final_diagnosis;
       $record->record_type=$request->record_type;
       $record->total_fee=$request->total_fee;
       $record->non_medical_fee = $request->total_fee/2;
       $record->pooled_fee = ($request->total_fee/2)*0.3;
       $record->total_public_doctors;
       $record->total_private_doctors;
       $record->save();
       return $record;
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
        $personnel->designation = $request->designation;
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
        $personnel->designation = $request->designation;
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
        $result = MedicalRecord::join('patients', 'medical_records.patient_id', '=', 'patients.id')
        ->where('patients.hospital_id', Auth::user()->hospital_id)
        ->select('medical_records.*','patients.first_name','patients.last_name','patients.middle_name',
        'patients.name_suffix','patients.philhealth_number')
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

    public function editRestore(Request $request)
    {
        $restore=MedicalRecord::withTrashed()->find($request->id)->restore();
        return $restore;
    }
    public function deletePatient(Request $req)
    {
        return Patient::where('id', $req->id)->delete();
    }

    public function deleteRecord(Request $req)
    {
        return MedicalRecord::where('id', $req->id)->delete();
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
