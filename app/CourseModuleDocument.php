<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseModuleDocument extends Model {
    protected $guarded  = array('id');

    public $timestamps = false;

    public function courseModule() {
        return $this->belongsTo('App\CourseModule');
    }

    public function getFilePathAttribute() {
        $file = $this->attributes['file'];
        if (empty($file)) return null;

        $moduleId = $this->courseModule->id;
        $courseId = $this->courseModule->course->id;

        return "images/courses/$courseId/modules/$moduleId/$file";
    }
}
