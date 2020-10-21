<?php

namespace App\Imports\User;

use App\Models\Personnel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Str;

class PersonnelImport implements ToModel,WithHeadingRow
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

        $first_name = Str::of($row['first_name'])->ltrim()->rtrim();
        $middle_name = Str::of($row['middle_name'])->ltrim()->rtrim();
        $last_name = Str::of($row['last_name'])->ltrim()->rtrim();
        $name_suffix = Str::of($row['name_suffix'])->ltrim()->rtrim();
        $sex = Str::of(ucfirst($row['sex']))->ltrim()->rtrim();
        $birthdate = Str::of($row['birthdate'])->ltrim()->rtrim();
        $is_private = Str::of(ucfirst($row['is_private']))->ltrim()->rtrim();

        $sex_array = array(0 => 'Male', 1 => 'Female');
        $is_private_array = array(0 => 'Private', 1 => 'Non-private');

        $exists = Personnel::select('id','hospital_id','first_name','middle_name','last_name','sex','birthdate','is_private','deleted_at')
        ->where('hospital_id', Auth::user()->hospital_id)
        ->where('first_name', $first_name)
        ->where('middle_name', $middle_name)
        ->where('last_name', $last_name)
        ->where('sex', array_search($sex, $sex_array))
        ->getQuery()
        ->get();

        if(!isset($exists[0]->first_name) &&
           !isset($exists[0]->middle_name) &&
           !isset($exists[0]->last_name) &&
           !isset($exists[0]->sex) &&
           !isset($exists[0]->is_private) && 
           !isset($exists[0]->deleted_at)
        ){
            ++$this->rows_imported;
            $personnel = new Personnel();
            $personnel->hospital_id = Auth::user()->hospital_id;
            $personnel->first_name = $first_name; 
            $personnel->middle_name = $middle_name; 
            $personnel->last_name = $last_name;
            $personnel->name_suffix = $name_suffix; 
            $personnel->sex = array_search($sex, $sex_array);
            $personnel->birthdate = Carbon::parse($birthdate)->format('Y-m-d');
            $personnel->is_private = array_search($is_private, $is_private_array); 
            $personnel->save();
        }elseif(strtolower(str_replace(" ","",$exists[0]->first_name)) == strtolower(str_replace(" ","",$first_name)) &&
                strtolower(str_replace(" ","",$exists[0]->middle_name)) == strtolower(str_replace(" ","",$middle_name)) &&
                strtolower(str_replace(" ","",$exists[0]->last_name)) == strtolower(str_replace(" ","",$last_name)) &&
                $exists[0]->sex == array_search($sex, $sex_array) &&
                $exists[0]->birthdate == Carbon::parse($birthdate)->format('Y-m-d') &&
                $exists[0]->is_private == array_search($is_private, $is_private_array) && 
                $exists[0]->deleted_at != null
        ){
            ++$this->rows_imported;
            $restore = Personnel::withTrashed()->find($exists[0]->id)->restore();
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
