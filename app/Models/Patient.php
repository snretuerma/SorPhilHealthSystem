<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'hospital_id', 'first_name', 'middle_name', 'last_name',
        'name_suffix', 'sex', 'birthdate', 'address',
        'marital_status', 'philhealth_number'
    ];

    public function hospital()
    {
        return $this->belongsTo('App\Models\Hospital');
    }

    public function medical_records()
    {
        return $this->hasMany('App\Models\MedicalRecord');
    }
}
