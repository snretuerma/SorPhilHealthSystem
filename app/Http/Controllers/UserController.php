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
     * Importing budget through file (deprecated)
     *
     * @var Request $request
     * @return CreditRecord
     */
    public function importExcel(Request $request)
    {
        $doctor_list_complete = $request[0]['doctor_list'];
        $cell_physician = [
            'Attending_Physician',
            'Admitting_Physician',
            'Requesting_Physician',
            'Co_Management',
            'Anesthesiology_Physician',
            'Surgeon_Physician',
            'HealthCare_Physician',
            'ER_Physician'
        ];
        for ($i = 0; $i < count($request[0]['import_batch']); $i++) {
            $batch = $request[0]['import_batch'][$i];
            $acpn = $request[0]['doctor_record'][$i]['content'];
            foreach ($acpn as $each) {
                $doctor_ids = [];
                $doctor_as = [];
                foreach ($cell_physician as $physician) {
                    if ($this->splitTwoComma($each[$physician]) != null) {
                        foreach ($this->splitTwoComma($each[$physician])[0] as $name) {
                            foreach ($doctor_list_complete as $doctor_info) {
                                if (str_replace(' ', '', strtolower(trim($doctor_info['name']))) ==
                                    str_replace(' ', '', strtolower(trim($name)))
                                ) {
                                    array_push($doctor_ids, $doctor_info['id']);
                                    array_push($doctor_as, $physician);
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
                    $doctors = Doctor::where('hospital_id', $record->hospital_id)->whereIn('id', $doctor_ids)->get();
                    foreach ($doctors as $doctor) {
                        $doctor->credit_records()->attach($record->id, [
                            'doctor_role' => explode(
                                '_',
                                strtolower($doctor_as[array_search($doctor->id, $doctor_ids)])
                            )[0],
                            'professional_fee' => $record->total,
                        ]);
                    }
                } else {
                    if (Carbon::parse($each['Admission_Date'])
                    ->setTimeZone('Asia/Manila')
                    ->format('Y-m-d h:i:s') < "2020-03-01") {
                        $record->record_type = 'old';
                        $record->total = $each['Total_PF'];
                        $record->non_medical_fee = $record->total / 2;
                        $record->medical_fee = $record->non_medical_fee;
                        $record->save();
                        $doctors = Doctor::where('hospital_id', $record->hospital_id)
                            ->whereIn('id', $doctor_ids)
                            ->get();
                        foreach ($doctors as $doctor) {
                            $doctor->credit_records()->attach($record->id, [
                                'doctor_role' => explode(
                                    '_',
                                    strtolower($doctor_as[array_search($doctor->id, $doctor_ids)])
                                )[0],
                                'professional_fee' => ($record->non_medical_fee/ $doctor->count())
                            ]);
                        }
                    } else {
                        $record->record_type = 'new';
                        $record->total = $each['Total_PF'];
                        $record->non_medical_fee = $record->total / 2;
                        $record->medical_fee = $record->non_medical_fee;
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
                        $total_pooled_fee = $record->non_medical_fee * 0.3;
                        $initial_individual_fee = ($record->non_medical_fee * 0.3) /
                            (count($full_time_doctors) + (count($part_time_doctors) / 2));

                        $pooled_record->full_time_individual_fee = $initial_individual_fee;
                        $pooled_record->part_time_individual_fee = $initial_individual_fee / 2;
                        $pooled_record->record_id = $record->id;
                        $pooled_record->save();
                        foreach ($doctors as $doctor) {
                            $doctor->credit_records()->attach($record->id, [
                                'doctor_role' => explode(
                                    '_',
                                    strtolower($doctor_as[array_search($doctor->id, $doctor_ids)])
                                )[0],
                                'professional_fee' => ($record->non_medical_fee * 0.7) / $doctor->count()
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
        $summary = Doctor::with('credit_records')
            ->where('hospital_id', Auth::user()->hospital_id)
            ->get();
        return response()->json($summary);
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
     * Page for hospital settings
     *
     * @var void
     * @return View
     */
    public function setting(): View
    {
        return view('roles.user.setting');
    }

    /**
     * Update the hospital settings
     *
     * @var Request $request
     * @return void
     */
    public function updateSetting(Request $request): void
    {
        // TODO: Validation
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
        $record = new CreditRecord;
        $record->hospital()->associate(Hospital::find(1)->id);
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
            $doctors = Doctor::where('hospital_id', $record->hospital_id)
                ->whereIn('id', $request->doctors_id)
                ->get();
            foreach ($doctors as $doctor) {
                foreach ($request->doctortype as $types_of_doctors) {
                    if ($doctor->id == $types_of_doctors['id']) {
                        $doctor->credit_records()->attach($record->id, [
                            'doctor_role' => $types_of_doctors['role'],
                            'professional_fee' =>  $request->pf
                        ]);
                    }
                }
            }
        } else {
            if ($request->admission >= '2020-03-1') {
                $record->record_type = "new";
                $record->total = $request->pf;
                $record->non_medical_fee = $request->pf / 2;
                $record->medical_fee = $request->pf / 2;
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
                $total_pooled_fee = $record->non_medical_fee * 0.3;
                $initial_individual_fee = ($record->non_medical_fee * 0.3) /
                    (count($full_time_doctors) +
                        (count($part_time_doctors) / 2));
                $pooled_record->full_time_individual_fee = $initial_individual_fee;
                $pooled_record->part_time_individual_fee = $initial_individual_fee / 2;
                $pooled_record->record_id = $record->id;
                $pooled_record->save();
                foreach ($doctors as $doctor) {
                    foreach ($request->doctortype as $types_of_doctors) {
                        if ($doctor->id == $types_of_doctors['id']) {
                            $doctor->credit_records()->attach($record->id, [
                                'doctor_role' => $types_of_doctors['role'],
                                'professional_fee' => ($record->non_medical_fee * 0.7) / $doctor->count()
                            ]);
                        }
                    }
                }
            } else {
                $record->record_type = "old";
                $record->total = $request->pf;
                $record->non_medical_fee = $request->pf / 2;
                $record->medical_fee = $record->non_medical_fee;
                $record->save();
            }
        }
    }

    public function getActiveDoctors()
    {
        return Doctor::where('hospital_id', Auth::user()->hospital_id)
            ->where('is_active', true)
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
            $message = "Record batch ".$batch." successfully deleted";
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
        $record = CreditRecord::find($request->id);
        $record->patient_name=$request->name;
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
            $doctors = Doctor::where('hospital_id', $record->hospital_id)
                ->whereIn('id', $request->doctors_id)
                ->get();

            foreach ($doctors as $doctor) {
                foreach ($request->doctortype as $types_of_doctors) {
                    if ($doctor->id == $types_of_doctors['id']) {
                        $doctor->credit_records()->attach($record->id, [
                            'doctor_role' => $types_of_doctors['role'],
                            'professional_fee' =>  $request->pf
                        ]);
                    } else {
                        $doctor->credit_records()->detach([$record->id]);
                    }
                }
            }
        } else {
            if ($request->admission >= '2020-03-1') {
                $record->record_type = "new";
                $record->total = $request->pf;
                $record->non_medical_fee = $request->pf / 2;
                $record->medical_fee = $request->pf / 2;
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
                $total_pooled_fee = $record->non_medical_fee * 0.3;
                $initial_individual_fee = ($record->non_medical_fee * 0.3) /
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
                foreach ($doctors as $doctor) {
                    foreach ($request->doctortype as $types_of_doctors) {
                        if ($doctor->id == $types_of_doctors['id']) {
                            $doctor->credit_records()->attach($record->id, [
                                'doctor_role' => $types_of_doctors['role'],
                                'professional_fee' =>  $request->pf
                            ]);
                        }
                    }
                }
            } else {
                $record->record_type = "old";
                $record->total = $request->pf;
                $record->non_medical_fee = $request->pf / 2;
                $record->medical_fee = $record->non_medical_fee;
                $record->save();
            }
        }
        return $record;
    }
}
