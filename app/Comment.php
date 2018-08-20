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
}
