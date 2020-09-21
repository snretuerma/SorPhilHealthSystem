<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personnel extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'hospital_id', 'first_name', 'middle_name',
        'last_name','name_suffix', 'sex', 'birthdate',
    ];

    public function complaints()
    {
        return $this->hasMany('App\Models\Complaint');
    }

    public function medical_record()
    {
        return $this->belongsToMany('App\Models\MedicalRecord');
    }

    public function contribution()
    {
        return $this->hasMany('App\Models\Contribution');
    }

    public function hospital()
    {
        return $this->belongsTo('App\Models\Hospital');
    }
}
