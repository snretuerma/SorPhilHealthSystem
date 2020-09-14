<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use SoftDeletes;
    
    protected $guarded = [
        'hospital_id', 'total', 'budget_start_date'
    ];

    public function hospital()
    {
        return $this->belongsTo('App\Models\Hospital');
    }

    public function physicians()
    {
        return $this->belongsToMany('App\Models\Physician');
    }
}
