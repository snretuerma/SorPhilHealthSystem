<?php

namespace App\Http\Controllers;

use Auth;
use DB;

use App\Models\Patient;
use App\Models\Hospital;
use App\Models\User;
use App\Models\Budget;
use App\Models\Personnel;
use App\Models\MedicalRecord;
use App\Models\Contribution;

use App\Imports\User\PersonnelImport;
use App\Imports\User\PatientImport;
use App\Imports\User\BudgetImport;
use App\Exports\User\BudgetExport;
use App\Exports\User\PatientExport;
use App\Exports\User\PersonnelExport;

use App\Http\Requests\userAddBudgetRequest;
use App\Http\Requests\userAddPatientRequest;
use App\Http\Requests\userAddPersonnelRequest;
use App\Http\Requests\userEditBudgetRequest;
use App\Http\Requests\userEditPatientRequest;
use App\Http\Requests\userEditPersonnelRequest;
use App\Http\Requests\resetPassRequest;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:user');
        date_default_timezone_set('Asia/Manila');
    }

    // User Role Index View
    public function index()
    {
        return view('roles.user.index');
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
                    return $import->getRowCount();
                    break;
                case "PersonnelImport":
                    $import = new PersonnelImport;
                    $postData = request()->file('personnels');
                    Excel::import($import, $postData[0]);
                    return $import->getRowCount_imported() . '/' . $import->getRowCount();
                    break;
                case "PatientImport":
                    $import = new PatientImport;
                    $postData = request()->file('patients');
                    Excel::import($import, $postData[0]);
                    return $import->getRowCount_imported() . '/' . $import->getRowCount();
                    break;
            }
        } catch (\Error $ex) {
            return "Error, something went wrong!";
        }
    }
    public function exportExcel(Request $request)
    {
        $date = Carbon::now()->format('Ymd_His');
        $exceltype = $request->exceltype;
        $e_action = $request->e_action;

        if (isset($exceltype) && $exceltype != "") {
            switch ($exceltype) {
                case "csv":
                    switch ($e_action) {
                        case "BudgetExport":
                            return Excel::download(new BudgetExport, 'BudgetExportData_' . $date . '.csv');
                            break;
                        case "PersonnelExport":
                            return Excel::download(new PersonnelExport, 'StaffsExportData_' . $date . '.csv');
                            break;
                        case "PatientExport":
                            return Excel::download(new PatientExport, 'PatientExportData_' . $date . '.csv');
                            break;
                    }
                    break;
                case "xlsx":
                    switch ($e_action) {
                        case "BudgetExport":
                            return Excel::download(new BudgetExport, 'BudgetExportData_' . $date . '.xlsx');
                            break;
                        case "PersonnelExport":
                            return Excel::download(new PersonnelExport, 'StaffsExportData_' . $date . '.xlsx');
                            break;
                        case "PatientExport":
                            return Excel::download(new PatientExport, 'PatientExportData_' . $date . '.xlsx');
                            break;
                    }
                    break;
                case "xls":
                    switch ($e_action) {
                        case "BudgetExport":
                            return Excel::download(new BudgetExport, 'BudgetExportData_' . $date . '.xls');
                            break;
                        case "PersonnelExport":
                            return Excel::download(new PersonnelExport, 'StaffsExportData_' . $date . '.xls');
                            break;
                        case "PatientExport":
                            return Excel::download(new PatientExport, 'PatientExportData_' . $date . '.xls');
                            break;
                    }
                    break;
            }
        }
    }

    //User Role Reset Password View
    public function resetView()
    {
        return view('roles.user.reset');
    }

    //User Role Reset Password function
    public function resetPass(resetPassRequest $request)
    {
        $new_pass = User::find(Auth::user()->id);
        $new_pass->password = Hash::make($request->password);
        $new_pass->save();
    }

    //User Dashboard
    public function getCommonDisease(Request $request)
    {
        $medical = MedicalRecord::join('patients as p', 'medical_records.patient_id', '=', 'p.id')->with('contributions')
            ->where('p.hospital_id', Auth::user()->hospital_id)
            ->get();
        return $medical;
    }

    public function getRecentMedicalRecord()
    {
        $result = MedicalRecord::join('patients as p', 'medical_records.patient_id', '=', 'p.id')
            ->where('p.hospital_id', Auth::user()->hospital_id)->orderby('admission_date', 'desc')->limit(7)->get();
        return $result;
    }

    public function getRecentContribution()
    {
        $result = MedicalRecord::join('records_personnels as rp', 'medical_records.id', '=', 'rp.medical_record_id')
            ->join('personnels as ps', 'rp.personnel_id', '=', 'ps.id')
            ->join('hospitals as h', 'ps.hospital_id', '=', 'h.id')
            ->join('contributions as c', 'rp.contribution_id', '=', 'c.id')
            ->select('c.created_at', 'ps.last_name', 'ps.first_name', 'ps.middle_name', 'ps.name_suffix', 'medical_records.record_type', 'c.contribution', 'c.credit', 'c.status')
            ->where('h.id', Auth::user()->hospital_id)->orderby('created_at', 'desc')->limit(7)
            ->getQuery()
            ->get();
        return $result;
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

    //Personnel
    public function personnel()
    {
        return view('roles.user.personnel');
    }

    public function getPersonnels()
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
        $personnel->is_parttime = $request->is_parttime;
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
        $personnel->is_parttime = $request->is_parttime;
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

    public function deletePatient(Request $req)
    {
        return Patient::where('id', $req->id)->delete();
    }

    //Records
    public function record()
    {
        return view('roles.user.record');
    }

    public function getRecord()
    {
        $result = MedicalRecord::join('patients', 'medical_records.patient_id', '=', 'patients.id')
            ->with('personnels', 'contributions')
            ->where('patients.hospital_id', Auth::user()->hospital_id)
            ->select(
                'medical_records.*',
                'patients.first_name',
                'patients.last_name',
                'patients.middle_name',
                'patients.name_suffix',
                'patients.philhealth_number'
            )
            ->get();
        return response()->json($result);
    }

    public function addMedicalRecord(Request $request)
    {
        $record = new MedicalRecord;
        $record->patient_id = $request->patient_id;
        $record->admission_date = $request->admission_date;
        $record->discharge_date = $request->discharge_date;
        $record->final_diagnosis = $request->final_diagnosis;
        $record->record_type = $request->record_type;
        $record->total_fee = $request->total_fee;
        $record->non_medical_fee = $request->total_fee / 2;
        $record->pooled_fee = ($request->total_fee / 2) * 0.3;
        $record->total_public_doctors = $request->is_public;
        $record->total_private_doctors = $request->is_private;
        $record->save();
        return $record;
    }

    public function deleteRecord(Request $req)
    {
        return MedicalRecord::where('id', $req->id)->delete();
    }

    public function medicalrecord($id)
    {
        $total = MedicalRecord::find($id)->select('total_fee')->first();
        $data = [
            'id' => $id,
            'total_fee' => $total->total_fee
        ];
        return view('roles.user.medicalrecord')->with($data);
    }

    public function getPersonnellist(Request $request)
    {
        if ($request->query != '') {
            $users = DB::table('personnels')
                ->where('first_name', 'like', $request->get('query') . '%')
                ->get();
            return $users;
        }
    }

    public function getPersonnel(Request $request)
    {

        $result = MedicalRecord::select(
            'ps.last_name as last_name',
            'ps.first_name as first_name',
            'ps.middle_name as middle_name',
            'ps.name_suffix',
            'c.credit  as total_fee',
            'c.id as cid',
            'ps.id as pid',
            'medical_records.id as mid',
            'c.contribution',
            'c.deleted_at'
        )
            ->join('records_personnels as rp', 'medical_records.id', '=', 'rp.medical_record_id')
            ->join('personnels as ps', 'rp.personnel_id', '=', 'ps.id')
            ->join('contributions as c', 'rp.contribution_id', '=', 'c.id')
            ->where('medical_records.id', $request->id)
            ->where('c.deleted_at', null)
            ->getQuery()
            ->get();
        return response()->json($result);
    }

    public function addContributionRecord(Request $request)
    {
        foreach ($request->personnel as $personnel) {
            if ($personnel['state'] == ""  || $personnel['computePF'] == "") {
                throw ValidationException::withMessages([
                    'state' => ['Personnel is required'],
                    'computePF' => ['ComputePF is required']
                ]);
            }
        }
        for ($i = 0; $i < sizeof($request->personnel); $i++) {
            $contribution = new Contribution;
            $contribution->contribution = $request->personnel[$i]['contributionType'];
            $contribution->credit = $request->personnel[$i]['computePF'];
            $contribution->status = "paid";
            $contribution->save();
            $record = MedicalRecord::find($request->medical_record_id);
            $record->personnels()->attach(Personnel::find($request->personnel[$i]['staff']), ['contribution_id' => $contribution->id]);
        }
        return ['message' => 'success', 'status' => '200'];
    }

    public function editContribution(Request $request)
    {
        $i = (sizeof($request->item['contributions']) - 1);
        for ($i; $i >= 0; $i--) {
            if ($request->item['contributions'][$i]['contribution'] == "Attending Physician") {
                $result = Contribution::find($request->item['contributions'][$i]['id']);
                $result->credit = $request->total;
                $result->save();
            }
        }
    }

    public function deleteContribution(Request $req)
    {
        return Contribution::where('id', $req->id)->delete();
    }

    //Restore
    public function restore()
    {
        return view('roles.user.restore');
    }

    public function getRestore()
    {
        $result = MedicalRecord::join('patients as p', 'medical_records.patient_id', '=', 'p.id')
            ->join('hospitals as h', 'p.hospital_id', '=', 'h.id')
            ->join('records_personnels as rp', 'medical_records.id', '=', 'rp.medical_record_id')
            ->join('personnels as ps', 'rp.personnel_id', '=', 'ps.id')
            ->join('contributions as c', 'rp.contribution_id', '=', 'c.id')
            ->select(
                'medical_records.id',
                'p.philhealth_number as philhealth',
                'p.first_name as pfname',
                'p.middle_name as pmname',
                'p.last_name as plname',
                'p.name_suffix as pnamesuffix',
                'ps.first_name as psfname',
                'ps.middle_name as psmname',
                'ps.last_name as pslname',
                'h.hospital_code as hcode',
                'medical_records.admission_date',
                'medical_records.discharge_date'
            )
            ->where('medical_records.deleted_at', '<>', '', 'and')
            ->where('h.id', Auth::user()->hospital_id)
            ->getQuery()
            ->get();
        return $result;
    }

    public function editRestore(Request $request)
    {
        $restore = MedicalRecord::withTrashed()->find($request->id)->restore();
        return $restore;
    }
}
