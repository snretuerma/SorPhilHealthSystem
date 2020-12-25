<?php

/**
 * @file UserController
 * Controller for all function used by a User model in the application
 * php version 7.2.5
 *
 * @category App\Http\Controllers
 * @package
 * @author Shannon Francis Retuerma <snretuerma@up.edu.ph>
 * @author Mark Dy <markdy61@gmail.com>
 * @author Paul Bryan Dy <dy.paulbryan@gmail.com>
 * @author John Kevin Noguera <johnkevin0829@gmail.com>
 * @copyright  2020-present Provincial Government of Sorsogon ICTD
 * @license N/A
 * @version Development v.2
 */

namespace App\Http\Controllers;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

use App\Models\Hospital;
use App\Models\User;
use App\Models\Personnel;
use App\Models\MedicalRecord;
use App\Models\Contribution;
use App\Models\CreditRecord;
use App\Models\Doctor;
use App\Models\PooledRecord;
use App\Http\Requests\ResetPassRequest;
use App\Http\Requests\AddCreditRecordRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Define UserController file.
     *
     * @param null
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:user');
    }

    /**
     * Landing page for the User logged in as user role
     *
     * @var null
     * @return View
     */
    public function index(): View
    {
        return view('roles.user.index');
    }

    /**
     * Split data with two comma
     *
     * @var String $data
     * @return Array
     */
    public function splitTwoComma(String $data)
    {
        if (str_replace(' ', '', strtoupper(trim($data))) == "NULL" || $data == "" || $data == null) {
            return null;
        } else {
            $all_match = [];
            preg_match_all('/[^,]+,[^,]+/', $data, $all_match);
            if (count($all_match[0]) > 0) {
                return $all_match;
            } else {
                return null;
            }
        }
    }

    /**
     * Importing credit record through file (deprecated)
     *
     * @var Request $request
     * @return CreditRecord
     */
    public function importExcel(Request $request)
    {
        $hospital=Hospital::find(Auth::user()->hospital_id);
        $setting = $hospital->setting;
        $doctor_list_complete = $request[0]['doctor_list'];
        $cell_physician = [
            'Attending_Physician',
            'Admitting_Physician',
            'Requesting_Physician',
            'Co_Management',
            'Anesthesiologist_Physician',
            'Surgeon_Physician',
            'HealthCare_Physician',
            'ER_Physician'
        ];
        $physician_percentage = array(
            'Admitting_Physician' => json_decode($setting)->physicians[6],
            'Requesting_Physician' => json_decode($setting)->physicians[0],
            'Co_Management' => json_decode($setting)->physicians[5],
            'Anesthesiologist_Physician' => json_decode($setting)->physicians[4],
            'Surgeon_Physician' => json_decode($setting)->physicians[1],
            'HealthCare_Physician' => json_decode($setting)->physicians[2],
            'ER_Physician' => json_decode($setting)->physicians[3]
        );
        for ($i = 0; $i < count($request[0]['import_batch']); $i++) {
            $batch = $request[0]['import_batch'][$i];
            $acpn = $request[0]['doctor_record'][$i]['content'];
            foreach ($acpn as $each) {
                $doctor_ids = [];
                $doctor_as = [];
                $pf = [];
                $receive_by_non_attending = 0;
                $seventy_percent = ($each['Total_PF'] * json_decode($setting)->nonmedical) * 
                    json_decode($setting)->shared;
                foreach ($cell_physician as $physician) {
                    if ($this->splitTwoComma($each[$physician]) != null) {
                        foreach ($this->splitTwoComma($each[$physician])[0] as $name) {
                            foreach ($doctor_list_complete as $doctor_info) {
                                if (str_replace(' ', '', strtolower(trim($doctor_info['name']))) ==
                                    str_replace(' ', '', strtolower(trim($name)))) {
                                    array_push($doctor_ids, $doctor_info['id']);
                                    array_push($doctor_as, $physician);
                                    if($physician == "Attending_Physician"){
                                        array_push($pf, $seventy_percent);
                                    }else{
                                        array_push(
                                            $pf, 
                                            ($seventy_percent * $physician_percentage[$physician])
                                        );
                                        $receive_by_non_attending += 
                                            ($seventy_percent * $physician_percentage[$physician]);
                                    }
                                }
                            }
                        }
                    }
                }
                $record = new CreditRecord;
                $record->hospital()->associate(Auth::user()->hospital_id);
                $record->patient_name = $each['Patient_Name'];
                $record->batch = $batch;
                $record->admission_date = Carbon::parse($each['Admission_Date'])
                    ->setTimeZone('Asia/Manila')
                    ->format('Y-m-d h:i:s');
                $record->discharge_date = Carbon::parse($each['Discharge_Date'])
                    ->setTimeZone('Asia/Manila')
                    ->format('Y-m-d h:i:s');
                if ($each['Is_Private'] == "1") {
                    $record->record_type = 'private';
                    $record->total = $each['Total_PF'];
                    $record->non_medical_fee = 0;
                    $record->medical_fee = 0;
                    $record->save();
                    $doctors = Doctor::where('hospital_id', $record->hospital_id)
                        ->whereIn('id', $doctor_ids)
                        ->get();
                    foreach ($doctors as $doctor) {
                        $doctor->credit_records()->attach($record->id, [
                            'doctor_role' => (
                                $doctor_as[array_search($doctor->id, $doctor_ids)] ==
                                'Co_Management'
                            ) ? 'comanagement' :
                                explode(
                                    '_',
                                    strtolower($doctor_as[array_search($doctor->id, $doctor_ids)])
                            )[0],
                            'professional_fee' => $record->total,
                        ]);
                    }
                } else {
                    if (Carbon::parse($each['Admission_Date'])
                        ->setTimeZone('Asia/Manila')
                        ->format('Y-m-d h:i:s') < "2020-03-01"
                    ) {
                        $record->record_type = 'old';
                        $record->total = $each['Total_PF'];
                        $record->non_medical_fee = $record->total * json_decode($setting)->nonmedical;
                        $record->medical_fee = $record->non_medical_fee;
                        $record->save();
                        $doctors = Doctor::where('hospital_id', $record->hospital_id)
                            ->whereIn('id', $doctor_ids)
                            ->get();
                        foreach ($doctors as $doctor) {
                            $doctor->credit_records()->attach($record->id, [
                                'doctor_role' => (
                                    $doctor_as[array_search($doctor->id, $doctor_ids)] ==
                                    'Co_Management'
                                ) ? 'comanagement' :
                                    explode(
                                        '_',
                                        strtolower($doctor_as[array_search($doctor->id, $doctor_ids)])
                                )[0],
                                'professional_fee' => ($record->non_medical_fee / $doctor->count())
                            ]);
                        }
                    } else {
                        $record->record_type = 'new';
                        $record->total = $each['Total_PF'];
                        $record->non_medical_fee = $record->total * json_decode($setting)->nonmedical;
                        $record->medical_fee = $record->total * json_decode($setting)->medical;
                        $record->save();
                        $doctors = Doctor::where('hospital_id', $record->hospital_id)
                            ->whereIn('id', $doctor_ids)
                            ->get();
                        $full_time_doctors = Doctor::select('id')
                            ->where('is_active', true)
                            ->where('is_parttime', false)
                            ->pluck('id')
                            ->toArray();
                        $part_time_doctors = Doctor::select('id')
                            ->where('is_active', true)
                            ->where('is_parttime', true)
                            ->pluck('id')
                            ->toArray();
                        $pooled_record = new PooledRecord;
                        $pooled_record->full_time_doctors = json_encode($full_time_doctors);
                        $pooled_record->part_time_doctors = json_encode($part_time_doctors);
                        $total_pooled_fee = $record->non_medical_fee * json_decode($setting)->pooled;
                        $initial_individual_fee = ($record->non_medical_fee * json_decode($setting)->pooled) /
                            (count($full_time_doctors) + (count($part_time_doctors) / 2));
                        $pooled_record->full_time_individual_fee = $initial_individual_fee;
                        $pooled_record->part_time_individual_fee = $initial_individual_fee / 2;
                        $pooled_record->record_id = $record->id;
                        $pooled_record->save();
                        foreach ($doctors as $doctor) {
                            $computed_pf = 0;
                            if ($doctor_as[array_search($doctor->id, $doctor_ids)] == 'Attending_Physician'){
                                $computed_pf = (
                                    $pf[array_search($doctor->id, $doctor_ids)] - $receive_by_non_attending) /
                                    array_count_values($doctor_as)[$doctor_as[array_search($doctor->id, $doctor_ids)]]
                                ;
                            } else {
                                $computed_pf = $pf[array_search($doctor->id, $doctor_ids)];
                            }
                            $doctor->credit_records()->attach($record->id, [
                                'doctor_role' => (
                                    $doctor_as[array_search($doctor->id, $doctor_ids)] ==
                                    'Co_Management'
                                ) ? 'comanagement' :
                                    explode(
                                        '_',
                                        strtolower($doctor_as[array_search($doctor->id, $doctor_ids)])
                                    )[0],
                                'professional_fee' => $computed_pf
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Importing doctor list through file (deprecated)
     *
     * @var Request $request
     * @return Doctor
     */
    public function importExcelDoctorList(Request $request)
    {
        $physicians = $request[0];
        foreach ($physicians as $physician) {
            $doctor = new Doctor;
            $doctor->hospital_id = Auth::user()->hospital_id;
            $doctor->name = $physician['Physician_Name'];
            $doctor->is_active = (($physician['Is_active'] == 'Yes') ? 1 : 0);
            $doctor->is_parttime = (($physician['Is_parttime'] == 'Yes') ? 1 : 0);
            $doctor->save();
        }
    }

    /**
     * Exporting budget data as CSV
     *
     * @var Request $request
     * @return Excel
     */
    public function exportExcel(Request $request)
    {
    }

    /**
     * Displays the page for the reset password
     *
     * @var void
     * @return View
     */
    public function resetView(): View
    {
        return view('roles.user.reset');
    }

    /**
     * Reset the password from user input
     *
     * @var App\Http\Requests\ResetPassRequest
     * @return void
     */
    public function resetPass(ResetPassRequest $request): void
    {
        // TODO: Add validation and confirmation
        $new_pass = User::find(Auth::user()->id);
        $new_pass->password = Hash::make($request->password);
        $new_pass->save();
    }

    /**
     * Displays the page for the doctors page
     *
     * @var void
     * @return View
     */
    public function doctors(): View
    {
        return view('roles.user.doctors');
    }
    //Summary
    public function summary()
    {
        return view('roles.user.summary');
    }


    //Budget
    /**
     * Gets the list of doctors for the current user's hospital
     *
     * @var void
     * @return Collection
     */
    public function getDoctors()
    {
        $summary = Doctor::with(['credit_records' => function ($query) {
            $query->with('pooled_record');
        }])
            ->where('hospital_id', Auth::user()->hospital_id)
            ->get();
            
        return response()->json($summary);
    }

    //Co-Physician
    /**
     * Gets the list of co-doctors for the current user's hospital
     *
     * @var void
     * @return Collection
     */
    public function getDoctorsWithCoPhysician(Request $request)
    {
        $doctor_records = DB::table('doctor_records')
            ->join('doctors', 'doctor_records.doctor_id', '=', 'doctors.id')
            ->join('credit_records', 'doctor_records.record_id', '=', 'credit_records.id')
            ->select('doctor_records.*', 'doctors.name', 'credit_records.batch')
            ->where('credit_records.batch', $request->batch)
            ->get();

        return $doctor_records;
    }

    /**
     * Add a doctor record to the database
     *
     * @var Request $request
     * @return Doctor
     */

    public function addDoctor(Request $request): Doctor
    {
        $request->validate(
            [
                'name' => 'required|max:255',
                'is_active' => 'required',
                'is_parttime' => 'required'
            ]
        );

        $doctor = new Doctor;
        $doctor->name = $request->name;
        $doctor->is_active = $request->is_active;
        $doctor->is_parttime = $request->is_parttime;
        $doctor->hospital()->associate(Hospital::find(auth()->user()->hospital_id)->id);
        $doctor->save();

        return $doctor;
    }

    /**
     * Edit a doctor record
     *
     * @var Request $request
     * @return Doctor
     */
    public function editDoctor(Request $request): Doctor
    {
        $request->validate(
            [
                'name' => 'required|max:255',
                'is_active' => 'required',
                'is_parttime' => 'required'
            ]
        );

        $doctor = Doctor::find($request->id);
        $doctor->name = $request->name;
        $doctor->is_active = $request->is_active;
        $doctor->is_parttime = $request->is_parttime;
        $doctor->hospital()->associate(Hospital::find(auth()->user()->hospital_id)->id);
        $doctor->save();

        return $doctor;
    }

    /**
     * Delete a doctor's record if no CreditRecord is attached to it, else do not delete
     *
     * @var String
     * @return JsonResponse
     */
    public function deleteDoctor(String $id): JsonResponse
    {
        $doctor = Doctor::find($id);
        if ($doctor->credit_records->count() == 0) {
            $message = "Doctor " . $doctor->name . " successfully deleted";
            $doctor->delete();
            return response()->json(
                [
                    'title' => 'Deleting Doctor Success',
                    'message' => $message,
                    'status' => 'success',
                ]
            );
        }
        $message = "Failed to delete doctor " .
            $doctor->name .
            " because doctor has records in the database.
            Please contact the Administrator if you want to really delete this doctor data";
        return response()->json(
            [
                'title' => 'Deleting Doctor Failed',
                'message' => $message,
                'status' => 'error',
            ]
        );
    }

    /**
     * Page view for credit records
     *
     * @var void
     * @return View
     */
    public function records(): View
    {
        return view('roles.user.records');
    }

    /**
     * Page view restore page
     *
     * @var void
     * @return View
     */
    public function restore(): View
    {
        return view('roles.user.restore');
    }

    /**
     * Get deleted credit records
     *
     * @var void
     * @return Creditrecord
     */
    public function getRestore()
    {
        $deleted = CreditRecord::withTrashed()
            ->where('deleted_at', '<>', '', 'and')
            ->where('hospital_id', Auth::user()->hospital_id)
            ->getQuery()
            ->get();

        return $deleted;
    }

    /**
     * Restore deleted credit records
     *
     * @var void
     * @return Creditrecord
     */
    public function editRestore(Request $request)
    {
        $restore = CreditRecord::withTrashed()->find($request->id)->restore();
        return $restore;
    }

    /**
     * Page for hospital settings
     *
     * @var void
     * @return View
     */
    public function setting(): View
    {
        $hospital=Hospital::find(Auth::user()->hospital_id);
        return view('roles.user.setting')->with('setting',$hospital->setting);
    }

    /**
     * Update the hospital settings
     *
     * @var Request $request
     * @return void
     */
    public function updateSetting(Request $request): void
    {
        $request->validate(
            [
                'medical' => 'required|integer|between:0,100',
                'nonmedical' => 'required|integer|between:0,100',
                'pooled' => 'required|integer|between:0,100',
                'shared' => 'required|integer|between:0,100',
                'requesting' => 'required|integer|between:0,100',
                'surgeon' => 'required|integer|between:0,100',
                'healthcare' => 'required|integer|between:0,100',
                'er' => 'required|integer|between:0,100',
                'anesthesiologist' => 'required|integer|between:0,100',
                'comanagement' => 'required|integer|between:0,100',
                'admitting' => 'required|integer|between:0,100'
            ]
        );

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
            ]
        }';
        $hospital = Hospital::where('id', Auth::user()->hospital_id)->first();
        $hospital->setting = $data;
        $hospital->save();

        return;
    }
    public function getSummary($batch)
    {
        if ($batch != "All") {
            $summary = Doctor::with(['credit_records' => function ($query) use ($batch) {
                $query
                    ->where('batch', $batch)
                    ->with('pooled_record');
            }])
                ->where('hospital_id', Auth::user()->hospital_id)
                ->get();
            return response()->json($summary);
        } else {
            $summary = Doctor::with(['credit_records' => function ($query) {
                $query->with('pooled_record');
            }])
                ->where('hospital_id', Auth::user()->hospital_id)
                ->get();
            return response()->json($summary);
        }
    }
    public function getRecords($batch)
    {
        if ($batch != "All") {
            $records = CreditRecord::with('hospital', 'doctors')
                ->where('batch', $batch)->get();
            return response()->json($records);
        } else {
            $records = CreditRecord::with('hospital', 'doctors')
                ->get();
            return response()->json($records);
        }
    }

    public function addCreditRecord(AddCreditRecordRequest $request)
    {
        $hospital = Hospital::find(Auth::user()->hospital_id);
        $setting = json_decode($hospital->setting);
        $record = new CreditRecord;
        $record->hospital()->associate(Hospital::find(auth()->user()->hospital_id)->id);
        $record->patient_name = $request->name;
        $record->batch = $request->batch[0];
        $record->admission_date = Carbon::parse($request->admission)
            ->setTimezone('Asia/Manila');
        $record->discharge_date = Carbon::parse($request->discharge)
            ->setTimezone('Asia/Manila');
        if ($request->is_private) {
            $record->record_type = "private";
            $record->total = $request->pf;
            $record->non_medical_fee = 0;
            $record->medical_fee = 0;
            $record->save();
            $countAttending = 0;
            $total = $request->pf;
            $doctors = Doctor::where('hospital_id', $record->hospital_id)
                ->whereIn('id', $request->doctors_id)
                ->get();
            foreach ($doctors as $doctor) {
                foreach ($request->doctortype as $types_of_doctors) {
                    if ($doctor->id == $types_of_doctors['id']) {
                        if ($types_of_doctors['role'] == 'attending') {
                            $countAttending++;
                        }
                    }
                }
            }
            foreach ($doctors as $doctor) {
                foreach ($request->doctortype as $types_of_doctors) {
                    if ($doctor->id == $types_of_doctors['id']) {
                        if ($types_of_doctors['role'] == 'attending') {
                            $doctor->credit_records()->attach($record->id, [
                                'doctor_role' => $types_of_doctors['role'],
                                'professional_fee' =>  $total / $countAttending
                            ]);
                        }
                    }
                }
            }
            DB::table('pooled_records')->where('record_id', $request->id)->delete();
        } else {
            if ($request->admission >= '2020-03-1') {
                $record->record_type = "new";
                $record->total = $request->pf;
                $record->non_medical_fee = $request->pf * $setting->nonmedical;
                $record->medical_fee = $request->pf * $setting->medical;
                $record->save();
                $doctors = Doctor::where('hospital_id', $record->hospital_id)
                    ->whereIn('id', $request->doctors_id)
                    ->get();
                $full_time_doctors = Doctor::select('id')
                    ->where('is_active', true)
                    ->where('is_parttime', false)
                    ->pluck('id')
                    ->toArray();
                $part_time_doctors = Doctor::select('id')->where('is_active', true)
                    ->where('is_parttime', true)->pluck('id')->toArray();
                $pooled_record = new PooledRecord;
                $pooled_record->full_time_doctors = json_encode($full_time_doctors);
                $pooled_record->part_time_doctors = json_encode($part_time_doctors);
                $total_pooled_fee = $record->non_medical_fee * $setting->pooled;
                $initial_individual_fee = ($record->non_medical_fee * $setting->pooled) /
                    (count($full_time_doctors) +
                        (count($part_time_doctors) / 2));
                $pooled_record->full_time_individual_fee = $initial_individual_fee;
                $pooled_record->part_time_individual_fee = $initial_individual_fee / 2;
                $pooled_record->record_id = $record->id;
                $pooled_record->save();
                $requesting = 0;
                $surgeon = 0;
                $healthcare = 0;
                $er = 0;
                $anesthesiologist = 0;
                $comanage = 0;
                $admitting = 0;
                $countAttending = 0;
                $countRequesting = 0;
                $countSurgeon = 0;
                $countHealthcare = 0;
                $countEr = 0;
                $countAnesthesiologist = 0;
                $countComanagement = 0;
                $countAdmitting = 0;
                $total = $request->pf;
                foreach ($doctors as $doctor) {
                    foreach ($request->doctortype as $types_of_doctors) {
                        if ($doctor->id == $types_of_doctors['id']) {
                            if ($types_of_doctors['role'] == 'attending') {
                                $countAttending++;
                            } elseif ($types_of_doctors['role'] == 'requesting') {
                                $countRequesting++;
                                $requesting = ($total * $setting->physicians[0]);
                            } elseif ($types_of_doctors['role'] == 'surgeon') {
                                $countSurgeon++;
                                $surgeon = ($total * $setting->physicians[1]);
                            } elseif ($types_of_doctors['role'] == 'healthcare') {
                                $countHealthcare++;
                                $healthcare = ($total * $setting->physicians[2]);
                            } elseif ($types_of_doctors['role'] == 'er') {
                                $countEr++;
                                $er = ($total * $setting->physicians[3]);
                            } elseif ($types_of_doctors['role'] == 'anesthesiologist') {
                                $countAnesthesiologist++;
                                $anesthesiologist = ($total * $setting->physicians[4]);
                            } elseif ($types_of_doctors['role'] == 'comanagement') {
                                $countComanagement++;
                                $comanage = ($total * $setting->physicians[5]);
                            } elseif ($types_of_doctors['role'] == 'admitting') {
                                $countAdmitting++;
                                $admitting = ($total * $setting->physicians[6]);
                            }
                        }
                    }
                }
                foreach ($doctors as $doctor) {
                    foreach ($request->doctortype as $types_of_doctors) {
                        if ($doctor->id == $types_of_doctors['id']) {
                            if ($types_of_doctors['role'] == 'attending') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' => ($total -
                                        (($requesting * $countRequesting) +
                                            ($surgeon * $countSurgeon) +
                                            ($healthcare * $countHealthcare) +
                                            ($er * $countEr) +
                                            ($anesthesiologist * $countAnesthesiologist) +
                                            ($comanage * $countComanagement) +
                                            ($admitting * $countAdmitting))) /
                                        $countAttending
                                ]);
                            } elseif ($types_of_doctors['role'] == 'requesting') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[0]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'surgeon') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[1]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'healthcare') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[2]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'er') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[3]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'anesthesiologist') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[4]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'comanagement') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[5]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'admitting') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[6]
                                ]);
                            }
                        }
                    }
                }
            } else {
                $requesting = 0;
                $surgeon = 0;
                $healthcare = 0;
                $er = 0;
                $anesthesiologist = 0;
                $comanage = 0;
                $admitting = 0;
                $countAttending = 0;
                $countRequesting = 0;
                $countSurgeon = 0;
                $countHealthcare = 0;
                $countEr = 0;
                $countAnesthesiologist = 0;
                $countComanagement = 0;
                $countAdmitting = 0;
                $total = $request->pf;
                $record->record_type = "old";
                $record->total = $request->pf;
                $record->non_medical_fee = $request->pf * $setting->nonmedical;
                $record->medical_fee = $request->pf * $setting->medical;
                $record->save();
                $doctorrecord = DB::table('doctor_records')->select('*')
                    ->where('record_id', $request->id)->get();
                $full_time_doctors = Doctor::select('id')
                    ->where('is_active', true)
                    ->where('is_parttime', false)
                    ->pluck('id')
                    ->toArray();
                $part_time_doctors = Doctor::select('id')->where('is_active', true)
                    ->where('is_parttime', true)->pluck('id')->toArray();
                $deletePooled = DB::table('pooled_records')->where('record_id', $request->id)->delete();
                $pooled_record = new PooledRecord;
                $pooled_record->full_time_doctors = json_encode($full_time_doctors);
                $pooled_record->part_time_doctors = json_encode($part_time_doctors);
                $total_pooled_fee = $record->non_medical_fee * $setting->pooled;
                $initial_individual_fee = ($record->non_medical_fee * $setting->pooled) /
                    (count($full_time_doctors) +
                        (count($part_time_doctors) / 2));
                $pooled_record->full_time_individual_fee = $initial_individual_fee;
                $pooled_record->part_time_individual_fee = $initial_individual_fee / 2;
                $pooled_record->record_id = $record->id;
                $pooled_record->save();
                foreach ($doctorrecord as $dr) {
                    DB::table('doctor_records')->where('id', $dr->id)->delete();
                }
                $doctors = Doctor::where('hospital_id', $record->hospital_id)
                    ->whereIn('id', $request->doctors_id)->get();
                $countAttending = 0;
                $total = $request->pf;
                foreach ($doctors as $doctor) {
                    foreach ($request->doctortype as $types_of_doctors) {
                        if ($doctor->id == $types_of_doctors['id']) {
                            if ($types_of_doctors['role'] == 'attending') {
                                $countAttending++;
                            } elseif ($types_of_doctors['role'] == 'requesting') {
                                $countRequesting++;
                                $requesting = ($total * $setting->physicians[0]);
                            } elseif ($types_of_doctors['role'] == 'surgeon') {
                                $countSurgeon++;
                                $surgeon = ($total * $setting->physicians[1]);
                            } elseif ($types_of_doctors['role'] == 'healthcare') {
                                $countHealthcare++;
                                $healthcare = ($total * $setting->physicians[2]);
                            } elseif ($types_of_doctors['role'] == 'er') {
                                $countEr++;
                                $er = ($total * $setting->physicians[3]);
                            } elseif ($types_of_doctors['role'] == 'anesthesiologist') {
                                $countAnesthesiologist++;
                                $anesthesiologist = ($total * $setting->physicians[4]);
                            } elseif ($types_of_doctors['role'] == 'comanagement') {
                                $countComanagement++;
                                $comanage = ($total * $setting->physicians[5]);
                            } elseif ($types_of_doctors['role'] == 'admitting') {
                                $countAdmitting++;
                                $admitting = ($total * $setting->physicians[6]);
                            }
                        } else {
                            $doctor->credit_records()->detach([$record->id]);
                        }
                    }
                }
                foreach ($doctors as $doctor) {
                    foreach ($request->doctortype as $types_of_doctors) {
                        if ($doctor->id == $types_of_doctors['id']) {
                            if ($types_of_doctors['role'] == 'attending') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' => ($total -
                                        (($requesting * $countRequesting) +
                                            ($surgeon * $countSurgeon) +
                                            ($healthcare * $countHealthcare) +
                                            ($er * $countEr) +
                                            ($anesthesiologist * $countAnesthesiologist) +
                                            ($comanage * $countComanagement) +
                                            ($admitting * $countAdmitting))) / $countAttending
                                ]);
                            } elseif ($types_of_doctors['role'] == 'requesting') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[0]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'surgeon') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[1]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'healthcare') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[2]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'er') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[3]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'anesthesiologist') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[4]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'comanagement') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[5]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'admitting') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[6]
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    public function getAllDoctors()
    {
        return Doctor::where('hospital_id', Auth::user()->hospital_id)
            ->get();
    }

    public function getBatch()
    {
        return CreditRecord::distinct()
            ->where('hospital_id', Auth::user()->hospital_id)
            ->orderBy('batch', 'DESC')
            ->get(['batch']);
    }

    public function deleteRecord(String $id): JsonResponse
    {
        $record = CreditRecord::find($id);
        if ($record) {
            $message = "Doctor " . $record->name . " successfully deleted";
            $record->delete();
            return response()->json(
                [
                    'title' => 'Deleting Record Success',
                    'message' => $message,
                    'status' => 'success',
                ]
            );
        }
        $message = "Failed to delete record " .
            $record->name .
            "Please contact the Administrator if you want to really delete this record data";
        return response()->json(
            [
                'title' => 'Deleting Record Failed',
                'message' => $message,
                'status' => 'error',
            ]
        );
    }

    public function deleteByBatch(String $batch): JsonResponse
    {
        $record = CreditRecord::where('batch', $batch);
        if ($record) {
            $message = "Record batch " . $batch . " successfully deleted";
            $record->delete();
            return response()->json(
                [
                    'title' => 'Deleting Record Success',
                    'message' => $message,
                    'status' => 'success',
                ]
            );
        }
        $message = "Failed to delete record " .
            $record->name .
            "Please contact the Administrator if you want to really delete this record data";
        return response()->json(
            [
                'title' => 'Deleting Record Failed',
                'message' => $message,
                'status' => 'error',
            ]
        );
    }

    public function editRecord(Request $request): CreditRecord
    {
        $hospital = Hospital::find(Auth::user()->hospital_id);
        $setting = json_decode($hospital->setting);
        $record = CreditRecord::find($request->id);
        $record->patient_name = $request->name;
        $record->batch = $request->batch[0];
        $record->admission_date = Carbon::parse($request->admission)
            ->setTimezone('Asia/Manila');
        $record->discharge_date = Carbon::parse($request->discharge)
            ->setTimezone('Asia/Manila');
        if ($request->is_private) {
            $record->record_type = "private";
            $record->total = $request->pf;
            $record->non_medical_fee = 0;
            $record->medical_fee = 0;
            $record->save();
            $doctorrecord = DB::table('doctor_records')->select('*')
                ->where('record_id', $request->id)->get();
            foreach ($doctorrecord as $dr) {
                DB::table('doctor_records')->where('id', $dr->id)->delete();
            }
            $doctors = Doctor::where('hospital_id', $record->hospital_id)
                ->whereIn('id', $request->doctors_id)
                ->get();
            $countAttending = 0;
            $countRequesting = 0;
            $countSurgeon = 0;
            $countHealthcare = 0;
            $countEr = 0;
            $countAnesthesiologist = 0;
            $countComanagement = 0;
            $countAdmitting = 0;
            $total = $request->pf;
            foreach ($doctors as $doctor) {
                foreach ($request->doctortype as $types_of_doctors) {
                    if ($doctor->id == $types_of_doctors['id']) {
                        if ($types_of_doctors['role'] == 'attending') {
                            $countAttending++;
                        }
                    } else {
                        $doctor->credit_records()->detach([$record->id]);
                    }
                }
            }
            foreach ($doctors as $doctor) {
                foreach ($request->doctortype as $types_of_doctors) {
                    if ($doctor->id == $types_of_doctors['id']) {
                        if ($types_of_doctors['role'] == 'attending') {
                            $doctor->credit_records()->attach($record->id, [
                                'doctor_role' => $types_of_doctors['role'],
                                'professional_fee' =>  $total / $countAttending
                            ]);
                        }
                    }
                }
            }
            DB::table('pooled_records')->where('record_id', $request->id)->delete();
        } else {
            if ($request->admission >= '2020-03-1') {
                $record->record_type = "new";
                $record->total = $request->pf;
                $record->non_medical_fee = $request->pf * $setting->nonmedical;
                $record->medical_fee = $request->pf * $setting->medical;
                $record->save();
                $doctors = Doctor::where('hospital_id', $record->hospital_id)
                    ->whereIn('id', $request->doctors_id)
                    ->get();
                $full_time_doctors = Doctor::select('id')
                    ->where('is_active', true)
                    ->where('is_parttime', false)
                    ->pluck('id')
                    ->toArray();
                $part_time_doctors = Doctor::select('id')->where('is_active', true)
                    ->where('is_parttime', true)->pluck('id')->toArray();
                $deletePooled = DB::table('pooled_records')->where('record_id', $request->id)->delete();
                $pooled_record = new PooledRecord;
                $pooled_record->full_time_doctors = json_encode($full_time_doctors);
                $pooled_record->part_time_doctors = json_encode($part_time_doctors);
                $total_pooled_fee = $record->non_medical_fee * $setting->pooled;
                $initial_individual_fee = ($record->non_medical_fee * $setting->pooled) /
                    (count($full_time_doctors) +
                        (count($part_time_doctors) / 2));
                $pooled_record->full_time_individual_fee = $initial_individual_fee;
                $pooled_record->part_time_individual_fee = $initial_individual_fee / 2;
                $pooled_record->record_id = $record->id;
                $pooled_record->save();
                $doctorrecord = DB::table('doctor_records')->select('*')
                    ->where('record_id', $request->id)->get();
                foreach ($doctorrecord as $dr) {
                    DB::table('doctor_records')->where('id', $dr->id)->delete();
                }
                $requesting = 0;
                $surgeon = 0;
                $healthcare = 0;
                $er = 0;
                $anesthesiologist = 0;
                $comanage = 0;
                $admitting = 0;
                $countAttending = 0;
                $countRequesting = 0;
                $countSurgeon = 0;
                $countHealthcare = 0;
                $countEr = 0;
                $countAnesthesiologist = 0;
                $countComanagement = 0;
                $countAdmitting = 0;
                $total = $request->pf;
                foreach ($doctors as $doctor) {
                    foreach ($request->doctortype as $types_of_doctors) {
                        if ($doctor->id == $types_of_doctors['id']) {
                            if ($types_of_doctors['role'] == 'attending') {
                                $countAttending++;
                            } elseif ($types_of_doctors['role'] == 'requesting') {
                                $countRequesting++;
                                $requesting = ($total * $setting->physicians[0]);
                            } elseif ($types_of_doctors['role'] == 'surgeon') {
                                $countSurgeon++;
                                $surgeon = ($total * $setting->physicians[1]);
                            } elseif ($types_of_doctors['role'] == 'healthcare') {
                                $countHealthcare++;
                                $healthcare = ($total * $setting->physicians[2]);
                            } elseif ($types_of_doctors['role'] == 'er') {
                                $countEr++;
                                $er = ($total * $setting->physicians[3]);
                            } elseif ($types_of_doctors['role'] == 'anesthesiologist') {
                                $countAnesthesiologist++;
                                $anesthesiologist = ($total * $setting->physicians[4]);
                            } elseif ($types_of_doctors['role'] == 'comanagement') {
                                $countComanagement++;
                                $comanage = ($total * $setting->physicians[5]);
                            } elseif ($types_of_doctors['role'] == 'admitting') {
                                $countAdmitting++;
                                $admitting = ($total * $setting->physicians[6]);
                            }
                        } else {
                            $doctor->credit_records()->detach([$record->id]);
                        }
                    }
                }
                foreach ($doctors as $doctor) {
                    foreach ($request->doctortype as $types_of_doctors) {
                        if ($doctor->id == $types_of_doctors['id']) {
                            if ($types_of_doctors['role'] == 'attending') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' => ($total -
                                        (($requesting * $countRequesting) +
                                            ($surgeon * $countSurgeon) +
                                            ($healthcare * $countHealthcare) +
                                            ($er * $countEr) +
                                            ($anesthesiologist * $countAnesthesiologist) +
                                            ($comanage * $countComanagement) +
                                            ($admitting * $countAdmitting))) /
                                        $countAttending
                                ]);
                            } elseif ($types_of_doctors['role'] == 'requesting') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[0]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'surgeon') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[1]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'healthcare') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[2]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'er') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[3]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'anesthesiologist') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[4]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'comanagement') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[5]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'admitting') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[6]
                                ]);
                            }
                        }
                    }
                }
            } else {
                $requesting = 0;
                $surgeon = 0;
                $healthcare = 0;
                $er = 0;
                $anesthesiologist = 0;
                $comanage = 0;
                $admitting = 0;
                $countAttending = 0;
                $countRequesting = 0;
                $countSurgeon = 0;
                $countHealthcare = 0;
                $countEr = 0;
                $countAnesthesiologist = 0;
                $countComanagement = 0;
                $countAdmitting = 0;
                $total = $request->pf;
                $record->record_type = "old";
                $record->total = $request->pf;
                $record->non_medical_fee = $request->pf * $setting->nonmedical;
                $record->medical_fee = $request->pf * $setting->medical;
                $record->save();
                $doctorrecord = DB::table('doctor_records')->select('*')
                    ->where('record_id', $request->id)->get();
                $full_time_doctors = Doctor::select('id')
                    ->where('is_active', true)
                    ->where('is_parttime', false)
                    ->pluck('id')
                    ->toArray();
                $part_time_doctors = Doctor::select('id')->where('is_active', true)
                    ->where('is_parttime', true)->pluck('id')->toArray();
                $deletePooled = DB::table('pooled_records')->where('record_id', $request->id)->delete();
                $pooled_record = new PooledRecord;
                $pooled_record->full_time_doctors = json_encode($full_time_doctors);
                $pooled_record->part_time_doctors = json_encode($part_time_doctors);
                $total_pooled_fee = $record->non_medical_fee * $setting->pooled;
                $initial_individual_fee = ($record->non_medical_fee * $setting->pooled) /
                    (count($full_time_doctors) +
                        (count($part_time_doctors) / 2));
                $pooled_record->full_time_individual_fee = $initial_individual_fee;
                $pooled_record->part_time_individual_fee = $initial_individual_fee / 2;
                $pooled_record->record_id = $record->id;
                $pooled_record->save();
                foreach ($doctorrecord as $dr) {
                    DB::table('doctor_records')->where('id', $dr->id)->delete();
                }
                $doctors = Doctor::where('hospital_id', $record->hospital_id)
                    ->whereIn('id', $request->doctors_id)
                    ->get();
                $countAttending = 0;
                $total = $request->pf;
                foreach ($doctors as $doctor) {
                    foreach ($request->doctortype as $types_of_doctors) {
                        if ($doctor->id == $types_of_doctors['id']) {
                            if ($types_of_doctors['role'] == 'attending') {
                                $countAttending++;
                            } elseif ($types_of_doctors['role'] == 'requesting') {
                                $countRequesting++;
                                $requesting = ($total * $setting->physicians[0]);
                            } elseif ($types_of_doctors['role'] == 'surgeon') {
                                $countSurgeon++;
                                $surgeon = ($total * $setting->physicians[1]);
                            } elseif ($types_of_doctors['role'] == 'healthcare') {
                                $countHealthcare++;
                                $healthcare = ($total * $setting->physicians[2]);
                            } elseif ($types_of_doctors['role'] == 'er') {
                                $countEr++;
                                $er = ($total * $setting->physicians[3]);
                            } elseif ($types_of_doctors['role'] == 'anesthesiologist') {
                                $countAnesthesiologist++;
                                $anesthesiologist = ($total * $setting->physicians[4]);
                            } elseif ($types_of_doctors['role'] == 'comanagement') {
                                $countComanagement++;
                                $comanage = ($total * $setting->physicians[5]);
                            } elseif ($types_of_doctors['role'] == 'admitting') {
                                $countAdmitting++;
                                $admitting = ($total * $setting->physicians[6]);
                            }
                        } else {
                            $doctor->credit_records()->detach([$record->id]);
                        }
                    }
                }
                foreach ($doctors as $doctor) {
                    foreach ($request->doctortype as $types_of_doctors) {
                        if ($doctor->id == $types_of_doctors['id']) {
                            if ($types_of_doctors['role'] == 'attending') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' => ($total -
                                        (($requesting * $countRequesting) +
                                            ($surgeon * $countSurgeon) +
                                            ($healthcare * $countHealthcare) +
                                            ($er * $countEr) +
                                            ($anesthesiologist * $countAnesthesiologist) +
                                            ($comanage * $countComanagement) +
                                            ($admitting * $countAdmitting))) /
                                        $countAttending
                                ]);
                            } elseif ($types_of_doctors['role'] == 'requesting') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[0]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'surgeon') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[1]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'healthcare') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[2]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'er') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[3]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'anesthesiologist') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[4]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'comanagement') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[5]
                                ]);
                            } elseif ($types_of_doctors['role'] == 'admitting') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' =>  $request->pf * $setting->physicians[6]
                                ]);
                            }
                        }
                    }
                }
            }
        }
        return $record;
    }
}
