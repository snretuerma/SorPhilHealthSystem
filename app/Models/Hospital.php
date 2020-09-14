<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use SoftDeletes;
    
    protected $guarded = [
        '*',
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function physicians()
    {
        return $this->hasMany('App\Models\Physician');
    }

    public function budgets()
    {
        return $this->hasMany('App\Models\Budget');
    }

    public function records()
    {
        return $this->hasMany('App\Models\Record');
    }
}
