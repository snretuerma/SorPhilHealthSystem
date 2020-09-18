<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Physician extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 
        'accreditation_number', 'is_public'
    ];

    protected $guarded = [
        'hospital_id'
    ];
    
    public function hospital()
    {
        return $this->belongsTo('App\Models\Hospital');
    }

    public function records()
    {
        return $this->belongsToMany('App\Models\Record');
    }

    public function complaints()
    {
        return $this->hasMany('App\Models\Complaint');
    }
}
