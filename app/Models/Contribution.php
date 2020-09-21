<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contribution extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'type', 'contribution', 'credit', 'status'
    ];

    public function records()
    {

    }

    public function personnels()
    {

    }

}
