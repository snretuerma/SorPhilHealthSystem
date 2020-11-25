<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

use App\Models\Patient;
use App\Models\Hospital;
use App\Models\User;
use App\Models\Personnel;
use App\Models\MedicalRecord;
use App\Models\Contribution;
use App\Models\CreditRecord;

use App\Imports\User\PersonnelImport;
use App\Imports\User\PatientImport;
use App\Exports\User\PatientExport;
use App\Exports\User\PersonnelExport;

use App\Http\Requests\userAddPatientRequest;
use App\Http\Requests\userAddPersonnelRequest;
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

    //Doctors
    public function doctors()
    {
        return view('roles.user.doctors');
    }

    //Budget

    //Personnel

    //Patients

    //Records
    public function records()
    {
        return view('roles.user.records');
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
                'medical_records.discharge_date',
                'medical_records.final_diagnosis',
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

    public function setting()
    {
        return view('roles.user.setting');
    }

    public function updateSetting(Request $request)
    {
        $medical = intval($request->medical) / 100;
        $nonmedical = intval($request->nonmedical) / 100;
        $pooled = intval($request->pooled) / 100;
        $shared = intval($request->shared) / 100;
        $requesting = intval($request->requesting) / 100;
        $surgeon = intval($request->surgeon) / 100;
        $healthcare = intval($request->healthcare) / 100;
        $er = intval($request->er) / 100;
        $anesthesiologist = intval($request->anesthesiologist) / 100;
        $comanagement = intval($request->comanagement) / 100;
        $admitting = intval($request->admitting) / 100;
        $data = '{
            "medical":' . $medical . ',
            "nonmedical":' . $nonmedical . ',
            "pooled":' . $pooled . ',
            "shared":' . $shared . ',
            "physicians":[
                            ' . $requesting . ',
                            ' . $surgeon . ',
                            ' . $healthcare . ',
                            ' . $er . ',
                            ' . $anesthesiologist . ',
                            ' . $comanagement . ',
                            ' . $admitting . '
                        }]
        }';
        $hospital = Hospital::where('id', Auth::user()->hospital_id)->first();
        $hospital->setting = $data;
        return $hospital->save();
    }
    public function getRecords()
    {
        $records=CreditRecord::with('hospital','doctors')
        ->get();
        return response()->json($records);
    }
}
