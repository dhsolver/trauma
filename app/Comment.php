<?php

namespace App;

use Config;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
    protected $guarded  = array('id');

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function course() {
        return $this->belongsTo('App\Course');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'parent_id');
    }

    public function getCreatedAtAttribute($value)
    {
        if (empty($value)) return null;
        $dt = new DateTime($value, new DateTimeZone('UTC'));
        $dt->setTimezone(new DateTimeZone('America/Chicago'));
        return $dt->format("m/d/Y h:i A");
    }

    public function getUpdatedAtAttribute($value)
    {
        if (empty($value)) return null;
        $dt = new DateTime($value, new DateTimeZone('UTC'));
        $dt->setTimezone(new DateTimeZone('America/Chicago'));
        return $dt->format("m/d/Y h:i A");
    }
}
