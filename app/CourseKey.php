<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseKey extends Model {
    protected $guarded  = array('id');

    public function getCreatedAtAttribute($value)
    {
        if (empty($value)) return null;
        $time = strtotime($value);
        return date("m/d/Y", $time);
    }

    public function getRedeemedAtAttribute($value)
    {
        if (empty($value)) return null;
        $time = strtotime($value);
        return date("m/d/Y", $time);
    }

    public function redeemedUser() {
        return $this->belongsTo('App\User');
    }
}
