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
use App\Models\Hospital;
use App\Models\User;
use App\Models\Doctor;

//doctor imp
use App\Imports\User\DoctorImport;
use App\Imports\User\CreditRecordImport;
use App\Models\CreditRecord;
//end

use App\Imports\User\PersonnelImport;
use App\Imports\User\PatientImport;
use App\Exports\User\PatientExport;
use App\Exports\User\PersonnelExport;
use App\Http\Requests\ResetPassRequest;
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
        /*if(str_replace(' ','',strtoupper(trim($data))) == "NULL" || $data == ""){
            return 'null';
        }else{
            $data_split_arr = [];
            $content = explode(',', $data);
            for ($i=0; $i < count($content); $i+=2) {
                array_push($data_split_arr, trim($content[$i].','.$content[$i + 1]));
            }
            return $data_split_arr;
        }*/
       // dd($data);
        if(str_replace(' ','',strtoupper(trim($data))) == "NULL" || $data == "" || $data == null){
            return null;
        }else{
            $all_match = [];
            preg_match_all('/[^,]+,[^,]+/', $data, $all_match);
            if(count($all_match[0]) > 0){
                return $all_match;
            }else{
                return null;
            }
        }
    }

    /**
     * Importing budget through file (deprecated)
     *
     * @var Request $request
     * @return BudgetImport
     */
    public function importExcel(Request $request)
    {
        //dd(Auth::user()->hospital_id);
        //$postData = request()->file('budgets');
        //$postData = request()->file('doctorRecord');
        //dd($postData_sample);

        //for doctor import
        /*$import = new DoctorImport;
        $postData = request()->file('doctorRecord');
        Excel::import($import, $postData[0]);
        return $import->getRowCount();*/


        //for credit record working
        /*$import = new CreditRecordImport();
        $postData = request()->file('doctorRecord');
        Excel::import($import, $postData[0]);*/

        /*$j = $request[0]['doctor_record'][0]['content'];
        $k = 0;
        foreach($j as $h){

        $k++;}
        dd($k);*/

        $doctor_list_complete = $request[0]['doctor_list'];
        $cell_physician = [
            'Attending_Physician',	
            'Admitting_Physician',
            'Requesting_Physician',	
            'Reffered_Physician',	
            'Co_Management',	
            'Anesthesiology_Physician',	
            'Surgeon_Physician',	
            'HealthCare_Physician',	
            'ER_Physician'	
        ];

        //dd($request[0]['doctor_list']);
        for ($i=0; $i < count($request[0]['import_batch']); $i++) {
            //dd($request[0]['import_batch'][$i]);
            //dd(Carbon::parse($each['Admission_Date'])->setTimeZone('Asia/Manila')->format('Y-m-d h:i:s') ,$type);
            $batch = $request[0]['import_batch'][$i];
            $acpn = $request[0]['doctor_record'][$i]['content'];
            foreach($acpn as $each){

               

               // $doctor_list = [];
                $doctor_ids = [];
                $doctor_as = [];
                
                foreach($cell_physician as $physician){
                    if($this->splitTwoComma($each[$physician]) != null){
                        foreach($this->splitTwoComma($each[$physician])[0] as $name){
                            //array_push($doctor_list, trim($name));
                            foreach($doctor_list_complete as $doctor_info){
                                if(str_replace(' ','',strtolower(trim($doctor_info['name']))) == str_replace(' ','',strtolower(trim($name)))){
                                    array_push($doctor_ids, $doctor_info['id']);
                                    array_push($doctor_as, $physician);
                                }
                            }
                        }
                    }
                }
                //dd($doctor_ids, $doctor_as);


                /*$doctors = Doctor::where('hospital_id', 1)->whereIn('id', $doctor_ids)->get();
                   dd($doctors);
                dd($cell_physician);*/
                

                $record = new CreditRecord;
                $record->hospital()->associate(Auth::user()->hospital_id);
                $record->patient_name = $each['Patient_Name'];
                $record->batch = $batch;
                $record->admission_date = Carbon::parse($each['Admission_Date'])->setTimeZone('Asia/Manila')->format('Y-m-d h:i:s');
                $record->discharge_date = Carbon::parse($each['Discharge_Date'])->setTimeZone('Asia/Manila')->format('Y-m-d h:i:s');

                if($each['Is_Private'] == "1"){
                        $record->record_type = 'private';
                        $record->total = $each['Total_PF'];
                        $record->non_medical_fee = 0;
                        $record->medical_fee = 0;
                        $record->save();
                        $doctors = Doctor::where('hospital_id', $record->hospital_id)->whereIn('id', $doctor_ids)->get();
                        foreach($doctors as $doctor) {
                            $doctor->credit_records()->attach($record->id, [
                                'doctor_role' => str_replace('_',' ',strtolower($doctor_as[array_search($doctor->id, $doctor_ids)])),
                                'professional_fee' => $record->total,
                            ]);
                        }
                }else{
                    if((Carbon::parse($each['Admission_Date'])->setTimeZone('Asia/Manila')->format('Ymd')) < "20200301"){
                            $record->record_type = 'old';
                            $record->total = $each['Total_PF'];
                            $record->non_medical_fee = $record->total/2;
                            $record->medical_fee = $record->non_medical_fee;
                            $record->save();
                    }else{
                            $record->record_type = 'new';
                            $record->total = $each['Total_PF'];
                            $record->non_medical_fee = $record->total/2;
                            $record->medical_fee = $record->non_medical_fee;
                            $record->save();
                            $doctors = Doctor::where('hospital_id', $record->hospital_id)->whereIn('id', $doctor_ids)->get();
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
                            $total_pooled_fee = $record->non_medical_fee*0.3;
                            $initial_individual_fee = ($record->non_medical_fee*0.3)/(count($full_time_doctors)+(count($part_time_doctors)/2));

                            $pooled_record->full_time_individual_fee = $initial_individual_fee;
                            $pooled_record->part_time_individual_fee = $initial_individual_fee/2;
                            $pooled_record->record_id = $record->id;
                            $pooled_record->save();
                            foreach($doctors as $doctor) {
                                $doctor->credit_records()->attach($record->id, [
                                    'doctor_role' => str_replace('_',' ',strtolower($doctor_as[array_search($doctor->id, $doctor_ids)])),
                                    'professional_fee' => ($record->non_medical_fee*0.7)/$doctor->count()
                                ]);
                            }
                    }
                }
            }
        }
        //dd(count($request[0]['import_batch']));

        $excel_data = $request->doctorRecord;
        $batch_list = $request->import_batch;
        $doctor_list = $request->doctor_list;
        dd($excel_data, $batch_list, $doctor_list);

        //dd(count(explode(",", $batch_list[0])));
        $excel_data_get = explode(",", $excel_data[0]);
        $batch = explode(",", $batch_list[0]);
        /*for ($i=0; $i < count($batch); $i++) {
            dd(serialize($excel_data_get[$i]));
            $j = json_decode($excel_data_get[$i], true);
            dd($batch[$i], $j->title);
            //dd($batch[$i]);
        }*/


        /*for ($i=0; $i < 5; $i++) {
            dd($datas[0][0]->title);
        }*/

        //dd($datas[0]);
        /*foreach($datas as $data){
            dd($data->content);
        }*/


        //Excel::import($import, $postData[0]->getClientOriginalName());
        //dd($postData[0]->getClientOriginalName());

        //$data = Excel::load('file.csv', false, 'ISO-8859-1');

        /*$i_action = $request->i_action;
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
        }*/
    }

    /**
     * Exporting budget data as CSV
     *
     * @var Request $request
     * @return Excel
     */
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

    /**
     * Gets the list of doctors for the current user's hospital
     *
     * @var void
     * @return Collection
     */
    public function getDoctors(): Collection
    {
        return Doctor::where('hospital_id', Auth::user()->hospital_id)->get();
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
            $message = "Doctor ".$doctor->name." successfully deleted";
            $doctor->delete();
            return response()->json(
                [
                    'title' => 'Deleting Doctor Success',
                    'message' => $message,
                    'status' => 'success',
                ]
            );
        }
        $message = "Failed to delete doctor ".
            $doctor->name.
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
            "medical":'.$medical.',
            "nonmedical":'.$nonmedical.',
            "pooled":'.$pooled.',
            "shared":'.$shared.',
            "physicians":[
                '.$requesting.',
                '.$surgeon.',
                '.$healthcare.',
                '.$er.',
                '.$anesthesiologist.',
                '.$comanagement.',
                '.$admitting.'
            ]
        }';
        $hospital = Hospital::where('id', Auth::user()->hospital_id)->first();
        $hospital->setting = $data;
        return;
    }
}
