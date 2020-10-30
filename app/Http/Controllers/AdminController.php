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
use Dotenv\Store\File\Paths;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Admin\PatientImport;
use App\Exports\Admin\PatientExport;
use App\Imports\Admin\BudgetImport;
use App\Exports\Admin\BudgetExport;
use App\Imports\Admin\PersonnelImport;
use App\Exports\Admin\PersonnelExport;

use App\Http\Requests\adminAddBudgetRequest;
use App\Http\Requests\adminAddPatientRequest;
use App\Http\Requests\adminAddPersonnelRequest;
use App\Http\Requests\adminEditBudgetRequest;
use App\Http\Requests\adminEditPatientRequest;
use App\Http\Requests\adminEditPersonnelRequest;
use App\Http\Requests\resetPassRequest;
use App\Http\Requests\addHospitalRequest;
use App\Http\Requests\editHospitalRequest;

//use Illuminate\Notifications\Notification;
use App\Notifications\AdminChanges;
//use Illuminate\Routing\Route;
use Notification;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        date_default_timezone_set('Asia/Manila');
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

    public function getPersonnels()
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
        $personnel->designation = $request->designation;
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
        $personnel->designation = $request->designation;
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
    //Record
    public function getRecord()
    {
        $result = MedicalRecord::join('patients as p', 'medical_records.patient_id', '=', 'p.id')
        ->get();
        return $result;
    }
    
    public function importExcel(Request $request)
    {
        $i_action = $request->i_action;
        try {
            switch ($i_action) {
                case "BudgetImport":    
                     $import = new BudgetImport;
                     $postData = request()->file('budgets');
                     Excel::import($import, $postData[0]);    
                     return $import->getRowCount(); break;
                case "PersonnelImport":
                     $import = new PersonnelImport;
                     $postData = request()->file('personnels');
                     Excel::import($import, $postData[0]);   
                     return $import->getRowCount_imported().'/'.$import->getRowCount(); break;
                case "PatientImport":   
                     $import = new PatientImport;
                     $postData = request()->file('patients');
                     Excel::import($import, $postData[0]);    
                     return $import->getRowCount_imported().'/'.$import->getRowCount(); break;
            }
        } catch (\Error $ex) {
            return "Error, something went wrong!";
        }
    }
    
    public function getPersonnel(Request $request){
        $result=MedicalRecord::select('*')->where('id',$request->id)
        ->with('personnels','contributions')->first();
        return response()->json($result);
    }
    
    public function exportExcel(Request $request) 
    {
        $date = Carbon::now()->format('Ymd_His');
        $exceltype = $request->exceltype;
        $e_action = $request->e_action;
        if(isset($exceltype) && $exceltype != ""){
            switch ($exceltype) {
                case "csv":
                    switch ($e_action) {
                        case "BudgetExport":    return Excel::download(new BudgetExport, 'BudgetExportData_'.$date.'.csv');       break;
                        case "PersonnelExport": return Excel::download(new PersonnelExport, 'StaffsExportData_'.$date.'.csv');    break;
                        case "PatientExport":   return Excel::download(new PatientExport, 'PatientExportData_'.$date.'.csv');     break;
                    } break;
                case "xlsx":
                    switch ($e_action) {
                        case "BudgetExport":    return Excel::download(new BudgetExport, 'BudgetExportData_'.$date.'.xlsx');       break;
                        case "PersonnelExport": return Excel::download(new PersonnelExport, 'StaffsExportData_'.$date.'.xlsx');    break;
                        case "PatientExport":   return Excel::download(new PatientExport, 'PatientExportData_'.$date.'.xlsx');     break;
                    } break;
                case "xls":
                    switch ($e_action) {
                        case "BudgetExport":    return Excel::download(new BudgetExport, 'BudgetExportData_'.$date.'.xls');       break;
                        case "PersonnelExport": return Excel::download(new PersonnelExport, 'StaffsExportData_'.$date.'.xls');    break;
                        case "PatientExport":   return Excel::download(new PatientExport, 'PatientExportData_'.$date.'.xls');     break;
                    } break;
            }
        }
    }
    //Users
    public function users()
    {
        return view('roles.admin.users');
    }
    public function getHospitals()
    {
        return response()->json(
            Hospital::select('*')->with('users')->get()
        );  
    }
    public function addUser(Request $request)
    {
        if($request->notify === true){
            $from = [
                'name' => env('APP_NAME'),
                'email' => env('MAIL_USERNAME'),
                'hospital_code' => $request->hospital_code,
                'changes' => $request->changes,
            ];
            Notification::route('mail', $request->hospital_email)->notify(new AdminChanges($from));
        }         
        $user = new User;
        $user->username = trim($request->username);
        $user->password = Hash::make(trim($request->password));
        $user->hospital_id = $request->hospital_id;
        $user->assignRole('user');
        return $user->save();
    }
    public function addHospital(Request $request)
    {
        $hospital = new Hospital;
        $hospital->name = trim($request->name);
        $hospital->address = trim($request->address);
        $hospital->hospital_code = trim($request->hospital_code);
        $hospital->email_address = trim($request->email_address);
        return $hospital->save();
    }
    public function editHospital(Request $request)
    {
        $hospital = Hospital::where('id', $request->id)->first();
        $hospital->hospital_code = $request->hospital_code;
        $hospital->name = $request->name;
        $hospital->address = $request->address;
        $hospital->email_address = $request->email_address;
        return $hospital->save();
    }
    public function editUser(Request $request)
    {
        if($request->notify === true){
            $from = [
                'name' => env('APP_NAME'),
                'email' => env('MAIL_USERNAME'),
                'hospital_code' => $request->hospital_code,
                'changes' => $request->changes,
            ];
            Notification::route('mail', $request->hospital_email)->notify(new AdminChanges($from));
        }
        $user = User::where('id', $request->id)->first();
        $user->username = $request->username;
        return $user->save();
    }
    public function editUserResetPass(Request $request)
    {
        if($request->notify === true){
            $from = [
                'name' => env('APP_NAME'),
                'email' => env('MAIL_USERNAME'),
                'hospital_code' => $request->hospital_code,
                'changes' => $request->changes,
            ];
            Notification::route('mail', $request->hospital_email)->notify(new AdminChanges($from));
        }
        $user = User::where('id', $request->id)->first();
        $user->password = Hash::make('password');
        return $user->save();
    }
}
