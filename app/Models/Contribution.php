<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contribution extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'type', 'contribution', 'credit', 'status',
    ];

    public function records()
    {
        return $this->belongsToMany('App\Models\MedicalRecord', 'records_personnels')->withPivot('medical_record_id');
    }

    public function personnels()
    {
        return $this->belongsTo('App\Models\Personnel');
    }

}
