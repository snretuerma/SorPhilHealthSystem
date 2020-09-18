<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $guarded = [
        '*',
    ];
    
    public function record()
    {
        return $this->belongsTo('App\Models\Record');
    }
    
    public function physicians()
    {
        return $this->belongsToMany('App\Models\Physician');
    }
}
