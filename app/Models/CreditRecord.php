<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditRecord extends Model
{
    protected $guarded  = [
        'patient_name', 'batch', 'admission_date', 'discharge_date',
        'type', 'total', 'non_medical_fee', 'medical_fee',
    ];

    public function hospital()
    {
        return $this->belongsTo('App\Models\Hospital', 'hospital_id');
    }

    public function doctors()
    {
        return $this->belongsToMany(
            'App\Models\Doctor',
            'record_doctors',
            'record_id',
            'id'
        )->withPivot('doctor_role', 'professional_fee', 'pooled_fee');
    }
}
