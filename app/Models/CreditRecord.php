<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditRecord extends Model
{
    use SoftDeletes;
    protected $guarded  = [
        'patient_name', 'batch', 'admission_date', 'discharge_date',
        'record_type', 'total', 'non_medical_fee', 'medical_fee',
    ];

    public function hospital()
    {
        return $this->belongsTo('App\Models\Hospital', 'hospital_id');
    }

    public function doctors()
    {
        return $this->belongsToMany(
            'App\Models\Doctor',
            'doctor_records',
            'record_id',
            'doctor_id'
        )->withPivot('doctor_role', 'professional_fee');
    }

    public function pooled_record()
    {
        return $this->hasOne('App\Models\PooledRecord', 'record_id');
    }
}
