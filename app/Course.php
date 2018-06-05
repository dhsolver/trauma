<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model implements SluggableInterface {

    use SoftDeletes;
    use SluggableTrait;

    protected $dates = ['deleted_at'];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    protected $guarded  = array('id');

    protected $casts = [
        'instructors' => 'array',
    ];

    /**
     * Returns a formatted post content entry,
     * this ensures that line breaks are returned.
     *
     * @return string
     */
    public function content()
    {
        return nl2br($this->content);
    }

    public function getDateStartAttribute($value)
    {
        if (empty($value)) return null;
        $time = strtotime($value);
        return date("m/d/Y", $time);
    }

    public function setDateStartAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['date_start'] = null;
        } else {
            $this->attributes['date_start'] = date("Y-m-d", strtotime($value) );
        }
    }

    public function getDateEndAttribute($value)
    {
        if (empty($value)) return null;
        $time = strtotime($value);
        return date("m/d/Y", $time);
    }

    public function setDateEndAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['date_end'] = null;
        } else{
            $this->attributes['date_end'] = date("Y-m-d", strtotime($value) );
        }
    }

    public function setOnlineOnlyAttribute($value) {
        $this->attributes['online_only'] = $value;
        if ($this->attributes['online_only'] == true) {
            $this->attributes['date_start'] = null;
            $this->attributes['date_end'] = null;
        }
    }

    public function getPhotoPathAttribute() {
        $id = $this->attributes['id'];
        $photo = $this->attributes['photo'];
        if (empty($id) || empty($photo)) return null;

        return "images/courses/$id/$photo";
    }

    public function modules()
    {
        return $this->hasMany('App\CourseModule');
    }

    public function keys()
    {
        return $this->hasMany('App\CourseKey');
    }

    public function documents()
    {
        return $this->hasMany('App\CourseDocument');
    }

    public function registrations()
    {
        return $this->hasMany('App\UsersCoursesRegistration');
    }

    public function getModuleDocuments() {
        $moduleDocuments = [];
        foreach($this->modules as $module) {
            $moduleDocuments = array_merge($moduleDocuments, $module->documents->toArray());
        }
        return $moduleDocuments;
    }
}
