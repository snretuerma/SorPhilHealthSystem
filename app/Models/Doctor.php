<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $guarded  = [
        'name', 'is_active', 'is_parttime'
    ];

    public function hospital()
    {
        return $this->belongsTo('App\Models\Hospital', 'hospital_id');
    }

    public function credit_records()
    {
        return $this->belongsToMany('App\Models\CreditRecord');
    }
}
