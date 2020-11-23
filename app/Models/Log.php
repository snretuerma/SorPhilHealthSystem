<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $guarded  = [
        'action', 'table_name', 'item_id', 'original_values', 'new_values',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
