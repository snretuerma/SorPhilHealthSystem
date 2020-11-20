<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use SoftDeletes;
    use Notifiable;

    protected $guarded = [
        'name', 'address', 'hospital_code', 'email_address', 'setting'
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function doctors()
    {
        return $this->hasMany('App\Models\Doctor');
    }

    public function credit_records()
    {
        return $this->hasMany('App\Models\CreditRecord');
    }
}
