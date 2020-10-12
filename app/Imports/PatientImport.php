<?php

namespace App\Imports;

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

        $exists = Patient::select('hospital_id','philhealth_number')
        ->where('hospital_id', Auth::user()->hospital_id)
        ->where('philhealth_number', $philhealth_number)
        ->getQuery()
        ->get();

        $sex_array = array(0 => 'Male', 1 => 'Female');
        $marital_status_array = array(0 => 'Single', 1 => 'Married', 2 => 'Divorced', 3 => 'Widowed', 4 => 'Others/Prepare Not to Say');

        if(!isset($exists[0]->philhealth_number)){
            ++$this->rows_imported;
            date_default_timezone_set('Asia/Manila');
            $patient = new Patient();
            $patient->hospital_id = Auth::user()->hospital_id;
            $patient->first_name = $first_name; 
            $patient->middle_name = $middle_name; 
            $patient->last_name = $last_name;
            $patient->name_suffix = $name_suffix; 
            $patient->sex = array_search($sex, $sex_array);
            $patient->birthdate = Carbon::parse($birthdate)->format('Y-m-d');
            $patient->marital_status = array_search($marital_status, $marital_status_array); 
            $patient->philhealth_number = $philhealth_number;
            $patient->save();
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
