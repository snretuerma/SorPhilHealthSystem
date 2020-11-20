<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalRecord extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'patient_id', 'admission_date', 'discharge_date',
        'final_diagnosis', 'record_type', 'total_fee'
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
        return $this->belongsToMany('App\Models\Personnel', 'records_personnels')->withPivot('personnel_id');
    }

    public function contributions()
    {
        return $this->belongsToMany('App\Models\Contribution', 'records_personnels')->withPivot('contribution_id');
    }
}
