<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'description', 'resolution_date'
    ];

    public function personnels()
    {
        return $this->belongsTo('App\Models\Personnel');
    }

    public function medical_records()
    {
        return $this->belongsTo('App\Models\MedicalRecord');
    }
}
