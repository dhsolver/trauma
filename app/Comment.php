<?php

namespace App;

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
        $time = strtotime($value);
        return date("m/d/Y h:i A", $time);
    }

    public function getUpdatedAtAttribute($value)
    {
        if (empty($value)) return null;
        $time = strtotime($value);
        return date("m/d/Y h:i A", $time);
    }
}
