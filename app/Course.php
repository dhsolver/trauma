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


    public function getDateAttribute($value)
    {
        $time = strtotime($value);
        return date("m/d/Y", $time);
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = date("Y-m-d", strtotime($value) );
    }
}
