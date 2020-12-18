<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PooledRecord extends Model
{
    public $timestamps = false;
    protected $guarded  = [
        'full_time_doctors', 'part_time_doctors', 'full_time_individual_fee', 'part_time_individual_fee'
    ];

    public function credit_record()
    {
        return $this->belongsTo('App\Models\CreditRecord', 'record_id');
    }
}
