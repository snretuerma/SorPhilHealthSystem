<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $guarded  = [
        'username', 'password', 'hospital_id', 'role_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hospital()
    {
        return $this->belongsTo('App\Models\Hospital', 'hospital_id');
    }

    public function role() 
    {
        return $this->belongsTo('App\Models\Role', 'role_id'); 
    }
}
