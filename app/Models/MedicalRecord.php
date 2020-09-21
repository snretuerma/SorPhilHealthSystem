<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalRecord extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'patient_id', 'total_pf', 'admission_date',
        'discharge_date', 'final_diagnosis', 'record_type',
    ];

    public function complaints()
    {
        return $this->hasMany('App\Models\Complaint');
    }

    public function patient()
    {
        return $this->belongsTo('App\Models\Patient');
    }

    public function personnels()
    {
        return $this->belongsToMany('App\Models\Personnel');
    }

    public function contributions()
    {
        return $this->hasMany('App\Models\Contribution');
    }
}
