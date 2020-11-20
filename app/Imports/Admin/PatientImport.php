<?php

namespace App\Imports\Admin;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Str;

class PatientImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $rows = 0;
    private $rows_imported = 0;

    public function model(array $row)
    {
        ++$this->rows;
        date_default_timezone_set('Asia/Manila');
        
        $hospital_id = intval(trim($row['hospital_id']));
        $first_name = Str::of($row['first_name'])->ltrim()->rtrim();
        $middle_name = Str::of($row['middle_name'])->ltrim()->rtrim();
        $last_name = Str::of($row['last_name'])->ltrim()->rtrim();
        $name_suffix = Str::of($row['name_suffix'])->ltrim()->rtrim();
        $sex = Str::of($row['sex'])->ltrim()->rtrim();
        $birthdate = Str::of($row['birthdate'])->ltrim()->rtrim();
        $marital_status = Str::of($row['marital_status'])->ltrim()->rtrim();
        $philhealth_number = Str::of($row['philhealth_number'])->ltrim()->rtrim();
        $sex_index = "";
        $marital_status_index = "";

        $sex_array = array(0 => 'Male', 1 => 'Female');
        $marital_status_array = array(0 => 'Single', 1 => 'Married', 2 => 'Divorced', 3 => 'Widowed', 4 => 'Others/Prepare Not to Say');

        $exists = Patient::select('id','hospital_id','first_name','middle_name','last_name','name_suffix','sex','birthdate','marital_status','philhealth_number','deleted_at')
        ->where('hospital_id', $hospital_id)
        ->where('first_name', $first_name)
        ->where('middle_name', $middle_name)
        ->where('last_name', $last_name)
        ->where('sex', array_search($sex, $sex_array))
        ->where('philhealth_number', $philhealth_number)
        ->getQuery()
        ->get();

        if(!isset($exists[0]->hospital_id) &&
           !isset($exists[0]->first_name) &&
           !isset($exists[0]->middle_name) &&
           !isset($exists[0]->last_name) &&
           !isset($exists[0]->sex) &&
           !isset($exists[0]->philhealth_number) && 
           !isset($exists[0]->deleted_at)
        ){
            ++$this->rows_imported;
            $patient = new Patient();
            $patient->hospital_id = $hospital_id;
            $patient->first_name = $first_name; 
            $patient->middle_name = $middle_name; 
            $patient->last_name = $last_name;
            $patient->name_suffix = $name_suffix; 
            $patient->sex = array_search($sex, $sex_array);
            $patient->birthdate = Carbon::parse($birthdate)->format('Y-m-d');
            $patient->marital_status = array_search($marital_status, $marital_status_array); 
            $patient->philhealth_number = $philhealth_number;
            $patient->save();
        }elseif($exists[0]->hospital_id == $hospital_id &&
                strtolower(str_replace(" ","",$exists[0]->first_name)) == strtolower(str_replace(" ","",$first_name)) &&
                strtolower(str_replace(" ","",$exists[0]->middle_name)) == strtolower(str_replace(" ","",$middle_name)) &&
                strtolower(str_replace(" ","",$exists[0]->last_name)) == strtolower(str_replace(" ","",$last_name)) &&
                str_replace(" ","",$exists[0]->philhealth_number) == str_replace(" ","",$philhealth_number) &&
                $exists[0]->deleted_at != null
        ){
            ++$this->rows_imported;
            $restore = Patient::withTrashed()->find($exists[0]->id)->restore();
        }
    }
    public function getRowCount(): int
    {
        return $this->rows;
    }
    public function getRowCount_imported(): int
    {
        return $this->rows_imported;
    }
}
