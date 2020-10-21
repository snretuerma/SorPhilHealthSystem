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
        'name', 'address', 'hospital_code', 'email_address'
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function budgets()
    {
        return $this->hasMany('App\Models\Budget');
    }

    public function personnels()
    {
        return $this->hasMany('App\Models\Personnel');
    }

    public function patients()
    {
        return $this->hasMany('App\Models\Patient');
    }

    public function medical_records()
    {
        return $this->hasMany('App\Models\MedicalRecord');
    }
}
