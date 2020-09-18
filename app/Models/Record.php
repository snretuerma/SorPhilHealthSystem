<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'patient_first_name', 'patient_middle_name', 'patient_last_name',
        'is_credited', 'patient_in', 'patient_out', 'total_pf',
    ];

    protected $guarded = [
        'hospital_id',
    ];

    public function hospital()
    {
        return $this->belongsTo('App\Models\Hospital');
    }

    public function physicians()
    {
        return $this->belongsToMany('App\Models\Physician');
    }

    public function complaints()
    {
        return $this->hasMany('App\Models\Complaint');
    }

    public function budgets()
    {
        return $this->belongsToMany('App\Models\Budget', 'budgets_records');
    }
}
