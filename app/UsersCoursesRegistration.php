<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersCoursesRegistration extends Model {
    protected $guarded  = array('id');
    public $timestamps = false;

    protected $casts = [
        'progress' => 'array',
    ];

    public function getRegisteredAtAttribute($value)
    {
        if (empty($value)) return null;
        $time = strtotime($value);
        return date("m/d/Y", $time);
    }

    public function getCompletedAtAttribute($value)
    {
        if (empty($value)) return null;
        $time = strtotime($value);
        return date("m/d/Y", $time);
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function course() {
        return $this->belongsTo('App\Course');
    }
}
