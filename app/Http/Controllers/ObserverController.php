<?php

namespace App\Http\Controllers;

use DB;

use App\Models\MedicalRecord;

use Illuminate\Http\Request;

class ObserverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:observer');
        date_default_timezone_set('Asia/Manila');
    }

    public function index()
    {
        return view('roles.observer.index');
    }

    //Budget
    public function budgetView()
    {
        return view('roles.observer.budget');
    }

    public function budgetList()
    {
        $budget = DB::table('budgets')
            ->join('hospitals', 'budgets.hospital_id', '=', 'hospitals.id')
            ->select('budgets.*', 'hospitals.hospital_code')
            ->whereNull('budgets.deleted_at')
            ->orderby('start_date', 'desc')
            ->get();
        return $budget;
    }

    //Personnels
    public function personnelsView()
    {
        return view('roles.observer.personnels');
    }

    public function personnelsList()
    {
        $personnels = DB::table('personnels')
            ->join('hospitals', 'personnels.hospital_id', '=', 'hospitals.id')
            ->select('personnels.*', 'hospitals.hospital_code')
            ->whereNull('personnels.deleted_at')
            ->get();
        return $personnels;
    }

    //Patients
    public function patientsView()
    {
        return view('roles.observer.patients');
    }

    public function patientsList()
    {
        $patients = DB::table('patients')
            ->join('hospitals', 'patients.hospital_id', '=', 'hospitals.id')
            ->select('patients.*', 'hospitals.hospital_code')
            ->whereNull('patients.deleted_at')
            ->get();
        return $patients;
    }

    //Records
    public function recordsView()
    {
        return view('roles.observer.records');
    }

    public function recordsList()
    {
    //    $records = DB::table('medical_records')
    //         ->join('hospitals', 'medical_records.hospital_id', '=', 'hospitals.id')
    //         ->select('medical_records.*', 'hospitals.hospital_code')
    //         ->whereNull('medical_records.deleted_at')
    //         ->get();
    //     return $records;
        $result = MedicalRecord::join('patients as p', 'medical_records.patient_id', '=', 'p.id')
                ->get();
        return $result;
    }

    public function personnelsListOnRecords(Request $request)
    {
        $result = MedicalRecord::select('*')->where('id', $request->id)
            ->with('personnels', 'contributions')->first();
        return response()->json($result);
    }

    //Users
    public function usersView()
    {
        return view('roles.observer.users');
    }

    //Reset Password
    public function resetPasswordView()
    {
        return view('roles.observer.resetpassword');
    }


}
