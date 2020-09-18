<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use SoftDeletes;
    
    protected $guarded = [
        'hospital_id', 'total', 'start_date', 'end_date'
    ];

    public function hospital()
    {
        return $this->belongsTo('App\Models\Hospital');
    }

    public function records()
    {
        return $this->belongsToMany('App\Models\Record');
    }
}
