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
//use DB;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

use App\Models\Hospital;
use App\Models\User;
use App\Models\Personnel;
use App\Models\MedicalRecord;
use App\Models\Contribution;
use App\Models\CreditRecord;
use App\Models\Doctor;
use App\Models\PooledRecord;
use App\Jobs\ProcessCreditRecord;
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
     * Importing credit record through file (deprecated)
     *
     * @var Request $request
     * @return CreditRecord
     */
    public function importExcel(Request $request)
    {
        $request->request->add(['hospital_id' => Auth::user()->hospital_id]);

        $request_chunk = array();
        $batch = $request->all()[0]['import_batch'];
        $doctor_list = $request->all()[0]['doctor_list'];

        for ($i=0; $i < count($request->all()[0]['import_batch']); $i++) {
            foreach (array_chunk($request->all()[0]['doctor_record'][$i]['content'], 100) as $doctor_record) {
                array_push(
                    $request_chunk,
                    [
                        [
                            "import_batch" => [$batch[$i]],
                            "doctor_record" => [['content' => []]],
                            "doctor_list" => $doctor_list
                        ],
                        "hospital_id" => Auth::user()->hospital_id
                    ]
                );

                $request_chunk[0][0]['doctor_record'][0]['content'] = $doctor_record;

                try {
                    ProcessCreditRecord::dispatch($request_chunk[0]);
                    $request_chunk = array();
                } catch (\Throwable $th) {
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
            ->orderBy('name', 'ASC')
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
        $doctor->name = strtoupper($request->name);
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
            ->whereNotNull('deleted_at', 'and')
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
        $hospital = Hospital::find(Auth::user()->hospital_id);
        return view('roles.user.setting')->with('setting', $hospital->setting);
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
        if ($batch != "All" || $batch != "all") {
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
                ->where('batch', $batch)
                ->orderBy('patient_name', 'ASC')
                ->get();
            return response()->json($records);
        } else {
            $records = CreditRecord::with('hospital', 'doctors')
                ->orderBy('patient_name', 'ASC')
                ->get();
            return response()->json($records);
        }
    }

    public function addCreditRecord(AddCreditRecordRequest $request)
    {
        $hospital = Hospital::find(Auth::user()->hospital_id);
        $setting = json_decode($hospital->setting);

        $doctorRole = array(
            'requesting' => $setting->physicians[0],
            'surgeon' => $setting->physicians[1],
            'healthcare' => $setting->physicians[2],
            'er' => $setting->physicians[3],
            'anesthesiologist' => $setting->physicians[4],
            'comanagement' => $setting->physicians[5],
            'admitting' => $setting->physicians[6]
        );

        $requesting = 0;
        $surgeon = 0;
        $healthcare = 0;
        $er = 0;
        $anesthesiologist = 0;
        $comanagement = 0;
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
        $seventyPercent = ($total * $setting->nonmedical) * $setting->shared;

        $record = new CreditRecord;
        $record->hospital()->associate(Hospital::find(auth()->user()->hospital_id)->id);
        $record->patient_name = strtoupper($request->name);
        $record->batch = $request->batch[0];
        $record->admission_date = Carbon::parse($request->admission)
            ->setTimezone('Asia/Manila');
        $record->discharge_date = Carbon::parse($request->discharge)
            ->setTimezone('Asia/Manila');

        $doctors = Doctor::where('hospital_id', $record->hospital_id)
            ->whereIn('id', $request->doctors_id)
            ->get();
        foreach ($doctors as $doctor) {
            foreach ($request->doctortype as $types_of_doctors) {
                if ($doctor->id == $types_of_doctors['id']) {
                    if ($types_of_doctors['role'] == 'attending') {
                        $countAttending++;
                    } else {
                        if (array_key_exists($types_of_doctors['role'], $doctorRole)) {
                            $sRole = $types_of_doctors['role'];
                            $dRole = 'count' . ucfirst($types_of_doctors['role']);
                            ${$dRole}++;
                            ${$sRole} = ($seventyPercent * $doctorRole[$sRole]);
                        }
                    }
                }
            }
        }
        if ($request->is_private) {
            $record->record_type = "private";
            $record->total = $total;
            $record->non_medical_fee = 0;
            $record->medical_fee = 0;
            $record->save();
            foreach ($doctors as $doctor) {
                foreach ($request->doctortype as $types_of_doctors) {
                    if ($doctor->id == $types_of_doctors['id']) {
                        if ($types_of_doctors['role'] == 'attending') {
                            $doctor->credit_records()->attach($record->id, [
                                'doctor_role' => $types_of_doctors['role'],
                                'professional_fee' =>  $total
                            ]);
                        }
                    }
                }
            }
        } else {
            if ($request->admission >= '2020-03-1') {
                $record->record_type = "new";
                $record->total = $request->pf;
                $record->non_medical_fee = $request->pf * $setting->nonmedical;
                $record->medical_fee = $request->pf * $setting->medical;
                $record->save();
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

                foreach ($doctors as $doctor) {
                    foreach ($request->doctortype as $types_of_doctors) {
                        if ($doctor->id == $types_of_doctors['id']) {
                            if ($types_of_doctors['role'] == 'attending') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' => ($seventyPercent -
                                        (($requesting * $countRequesting) +
                                            ($surgeon * $countSurgeon) +
                                            ($healthcare * $countHealthcare) +
                                            ($er * $countEr) +
                                            ($anesthesiologist * $countAnesthesiologist) +
                                            ($comanagement * $countComanagement) +
                                            ($admitting * $countAdmitting))) /
                                        $countAttending
                                ]);
                            } else {
                                if (array_key_exists($types_of_doctors['role'], $doctorRole)) {
                                    $sRole = $types_of_doctors['role'];
                                    $doctor->credit_records()->attach($record->id, [
                                        'doctor_role' => $sRole,
                                        'professional_fee' => ${$sRole}
                                    ]);
                                }
                            }
                        }
                    }
                }
            } else {
                $record->record_type = "old";
                $record->total = $request->pf;
                $record->non_medical_fee = $request->pf * $setting->nonmedical;
                $record->medical_fee = $request->pf * $setting->medical;
                $record->save();
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
                foreach ($doctors as $doctor) {
                    foreach ($request->doctortype as $types_of_doctors) {
                        if ($doctor->id == $types_of_doctors['id']) {
                            if ($types_of_doctors['role'] == 'attending') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' => ($seventyPercent -
                                        (($requesting * $countRequesting) +
                                            ($surgeon * $countSurgeon) +
                                            ($healthcare * $countHealthcare) +
                                            ($er * $countEr) +
                                            ($anesthesiologist * $countAnesthesiologist) +
                                            ($comanagement * $countComanagement) +
                                            ($admitting * $countAdmitting))) / $countAttending
                                ]);
                            } else {
                                if (array_key_exists($types_of_doctors['role'], $doctorRole)) {
                                    $sRole = $types_of_doctors['role'];
                                    $doctor->credit_records()->attach($record->id, [
                                        'doctor_role' => $sRole,
                                        'professional_fee' =>  ${$sRole}
                                    ]);
                                }
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
            ->orderBy('name', 'ASC')
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

    public function getRecordPooled(Request $request)
    {
        $pooled = PooledRecord::select('*')
        ->where('record_id', $request->recordid)
        ->first();
        $pooled->full_time_doctors = json_decode($pooled->full_time_doctors);
        $pooled->part_time_doctors = json_decode($pooled->part_time_doctors);
        return $pooled;
    }

    public function editRecord(Request $request): CreditRecord
    {
        //dd($request->id);
        $hospital = Hospital::find(Auth::user()->hospital_id);
        $setting = json_decode($hospital->setting);
        $doctorRole = array(
            'requesting' => $setting->physicians[0],
            'surgeon' => $setting->physicians[1],
            'healthcare' => $setting->physicians[2],
            'er' => $setting->physicians[3],
            'anesthesiologist' => $setting->physicians[4],
            'comanagement' => $setting->physicians[5],
            'admitting' => $setting->physicians[6]
        );

        $requesting = 0;
        $surgeon = 0;
        $healthcare = 0;
        $er = 0;
        $anesthesiologist = 0;
        $comanagement = 0;
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
        $seventyPercent = ($total * $setting->nonmedical) * $setting->shared;

        $record = CreditRecord::find($request->id);
        $record->patient_name = strtoupper($request->name);
        $record->batch = $request->batch[0];
        $record->admission_date = Carbon::parse($request->admission)
            ->setTimezone('Asia/Manila');
        $record->discharge_date = Carbon::parse($request->discharge)
            ->setTimezone('Asia/Manila');
        $doctors = Doctor::where('hospital_id', $record->hospital_id)
            ->whereIn('id', $request->doctors_id)
            ->get();
        $doctorrecord = DB::table('doctor_records')
            ->select('*')
            ->where('record_id', $request->id)
            ->get();
        foreach ($doctors as $doctor) {
            foreach ($request->doctortype as $types_of_doctors) {
                if ($doctor->id == $types_of_doctors['id']) {
                    if ($types_of_doctors['role'] == 'attending') {
                        $countAttending++;
                    } else {
                        if (array_key_exists($types_of_doctors['role'], $doctorRole)) {
                            $sRole = $types_of_doctors['role'];
                            $dRole = 'count' . ucfirst($types_of_doctors['role']);
                            ${$dRole}++;
                            ${$sRole} = ($seventyPercent * $doctorRole[$sRole]);
                        }
                    }
                } else {
                    $doctor->credit_records()->detach([$record->id]);
                }
            }
        }

        if ($request->is_private) {
            $record->record_type = "private";
            $record->total = $request->pf;
            $record->non_medical_fee = 0;
            $record->medical_fee = 0;
            $record->save();
            foreach ($doctorrecord as $dr) {
                DB::table('doctor_records')->where('id', $dr->id)->delete();
            }
            foreach ($doctors as $doctor) {
                foreach ($request->doctortype as $types_of_doctors) {
                    if ($doctor->id == $types_of_doctors['id']) {
                        if ($types_of_doctors['role'] == 'attending') {
                            $doctor->credit_records()->attach($record->id, [
                                'doctor_role' => $types_of_doctors['role'],
                                'professional_fee' =>  $total
                            ]);
                        }
                    }
                }
            }
            DB::table('pooled_records')->where('record_id', $request->id)->delete();
        } else {
            $has_existing_pooled = $request->has_existing_pooled;
            $full_time_id = [];
            $part_time_id = [];
            $fullPooled = 0;
            $partPooled = 0;
            if (count($request->doctorsFullTime) > 0) {
                $fullPooled = 1;
                foreach ($request->doctorsFullTime as $fulltime) {
                    array_push($full_time_id, $fulltime['id']);
                }
            }
            if (count($request->doctorsPartTime) > 0) {
                $partPooled = 1;
                foreach ($request->doctorsPartTime as $parttime) {
                    array_push($part_time_id, $parttime['id']);
                }
            }

            if ($request->admission >= '2020-03-1') {
                $record->record_type = "new";
                $record->total = $request->pf;
                $record->non_medical_fee = $request->pf * $setting->nonmedical;
                $record->medical_fee = $request->pf * $setting->medical;
                $record->save();

                if ($has_existing_pooled) {
                    if ($fullPooled == 1 || $partPooled == 1) {
                        $pooled_record = PooledRecord::where('record_id', $request->id)->first();
                        $pooled_record->full_time_doctors = json_encode($full_time_id);
                        $pooled_record->part_time_doctors = json_encode($part_time_id);
                        $total_pooled_fee = $record->non_medical_fee * $setting->pooled;
                        $initial_individual_fee = ($record->non_medical_fee * $setting->pooled) /
                            (count($full_time_id) +
                                (count($part_time_id) / 2));
                        $pooled_record->full_time_individual_fee = $initial_individual_fee;
                        $pooled_record->part_time_individual_fee = $initial_individual_fee / 2;
                        $pooled_record->save();
                    }
                } else {
                    if ($fullPooled == 1 || $partPooled == 1) {
                        $pooled_record = new PooledRecord;
                        $pooled_record->full_time_doctors = json_encode($full_time_id);
                        $pooled_record->part_time_doctors = json_encode($part_time_id);
                        $total_pooled_fee = $record->non_medical_fee * $setting->pooled;
                        $initial_individual_fee = ($record->non_medical_fee * $setting->pooled) /
                            (count($full_time_id) +
                                (count($part_time_id) / 2));
                        $pooled_record->full_time_individual_fee = $initial_individual_fee;
                        $pooled_record->part_time_individual_fee = $initial_individual_fee / 2;
                        $pooled_record->record_id = $record->id;
                        $pooled_record->save();
                    }
                }

                foreach ($doctorrecord as $dr) {
                    DB::table('doctor_records')->where('id', $dr->id)->delete();
                }
                foreach ($doctors as $doctor) {
                    foreach ($request->doctortype as $types_of_doctors) {
                        if ($doctor->id == $types_of_doctors['id']) {
                            if ($types_of_doctors['role'] == 'attending') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' => ($seventyPercent -
                                        (($requesting * $countRequesting) +
                                            ($surgeon * $countSurgeon) +
                                            ($healthcare * $countHealthcare) +
                                            ($er * $countEr) +
                                            ($anesthesiologist * $countAnesthesiologist) +
                                            ($comanagement * $countComanagement) +
                                            ($admitting * $countAdmitting))) /
                                        $countAttending
                                ]);
                            } else {
                                if (array_key_exists($types_of_doctors['role'], $doctorRole)) {
                                    $sRole = $types_of_doctors['role'];
                                    $doctor->credit_records()->attach($record->id, [
                                        'doctor_role' => $sRole,
                                        'professional_fee' =>  ${$sRole}
                                    ]);
                                }
                            }
                        }
                    }
                }
            } else {
                $record->record_type = "old";
                $record->total = $request->pf;
                $record->non_medical_fee = $request->pf * $setting->nonmedical;
                $record->medical_fee = $request->pf * $setting->medical;
                $record->save();
                if ($has_existing_pooled) {
                    if ($fullPooled == 1 || $partPooled == 1) {
                        $pooled_record = PooledRecord::where('record_id', $request->id)->first();
                        $pooled_record->full_time_doctors = json_encode($full_time_id);
                        $pooled_record->part_time_doctors = json_encode($part_time_id);
                        $total_pooled_fee = $record->non_medical_fee * $setting->pooled;
                        $initial_individual_fee = ($record->non_medical_fee * $setting->pooled) /
                            (count($full_time_id) +
                                (count($part_time_id) / 2));
                        $pooled_record->full_time_individual_fee = $initial_individual_fee;
                        $pooled_record->part_time_individual_fee = $initial_individual_fee / 2;
                        $pooled_record->save();
                    }
                } else {
                    if ($fullPooled == 1 || $partPooled == 1) {
                        $pooled_record = new PooledRecord;
                        $pooled_record->full_time_doctors = json_encode($full_time_id);
                        $pooled_record->part_time_doctors = json_encode($part_time_id);
                        $total_pooled_fee = $record->non_medical_fee * $setting->pooled;
                        $initial_individual_fee = ($record->non_medical_fee * $setting->pooled) /
                            (count($full_time_id) +
                                (count($part_time_id) / 2));
                        $pooled_record->full_time_individual_fee = $initial_individual_fee;
                        $pooled_record->part_time_individual_fee = $initial_individual_fee / 2;
                        $pooled_record->record_id = $record->id;
                        $pooled_record->save();
                    }
                }
                foreach ($doctorrecord as $dr) {
                    DB::table('doctor_records')->where('id', $dr->id)->delete();
                }
                foreach ($doctors as $doctor) {
                    foreach ($request->doctortype as $types_of_doctors) {
                        if ($doctor->id == $types_of_doctors['id']) {
                            if ($types_of_doctors['role'] == 'attending') {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => $types_of_doctors['role'],
                                    'professional_fee' => ($seventyPercent -
                                        (($requesting * $countRequesting) +
                                            ($surgeon * $countSurgeon) +
                                            ($healthcare * $countHealthcare) +
                                            ($er * $countEr) +
                                            ($anesthesiologist * $countAnesthesiologist) +
                                            ($comanagement * $countComanagement) +
                                            ($admitting * $countAdmitting))) /
                                        $countAttending
                                ]);
                            } else {
                                if (array_key_exists($types_of_doctors['role'], $doctorRole)) {
                                    $sRole = $types_of_doctors['role'];
                                    $doctor->credit_records()->attach($record->id, [
                                        'doctor_role' => $sRole,
                                        'professional_fee' =>  ${$sRole}
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $record;
    }
}
