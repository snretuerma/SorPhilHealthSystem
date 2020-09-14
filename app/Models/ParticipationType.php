<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipationType extends Model
{
    public $timestamps = false;

    protected $guarded = [
        'name', 'weight'
    ];
}
