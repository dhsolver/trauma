<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $guarded  = array('id');

    protected $casts = [
        'assigned_users' => 'array',
    ];
}
