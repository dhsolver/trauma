<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseModuleDocument extends Model {
    protected $guarded  = array('id');

    public $timestamps = false;
}
