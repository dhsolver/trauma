@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title'){{ $course->title }} @endsection

{{-- Content --}}
@section('main')
    @if (Session::has('courseMessage'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('courseMessage') }}
        </div>
    @endif

    <h2 class="page-title">
        Edit Course
    </h2>

    {!! Form::model($course, array('url' => url('admin/courses/'.$course->id), 'method' => 'put', 'class' => 'form-course', 'files'=> true)) !!}
    <input type="hidden" name="online_only" value="0">
    <div class="form-group {{ $errors->has('photo') ? 'has-error' : '' }}">
        <div class="row">
            <div class="col-xs-offset-2 col-xs-8 col-sm-offset-3 col-sm-6 text-center">
                @if ($course->photo)
                    <img class="img img-course" alt="{{$course->photo}}" src="{!! url('images/courses/'.$course->id.'/'.$course->photo) !!}"/>
                @else
                    <img class="img img-course" alt="no avatar" src="{!! url('images/no_photo.png') !!}"/>
                @endif
                <div class="m-b-5"><span class='label label-info' id="upload-file-info"></span></div>
                <label class="btn btn-sm btn-primary" for="course-photo">
                    <input id="course-photo" name="photo" type="file" value="Upload" style="display:none"
                    onchange="$('#upload-file-info').html(this.files[0].name)">
                    Choose Photo
                </label>
                <span class="help-block">{{ $errors->first('image', ':message') }}</span>
            </div>
        </div>
    </div>

    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
        {!! Form::label('title', 'Title', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('title', $course->title, array('class' => 'form-control', 'placeholder' => 'Title *')) !!}
            <span class="help-block">{{ $errors->first('title', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
        {!! Form::label('location', 'Location', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('location', $course->location, array('class' => 'form-control', 'placeholder' => 'Location *')) !!}
            <span class="help-block">{{ $errors->first('location', ':message') }}</span>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-3">
                <div class="checkbox inline">
                    <label>
                        {!! Form::checkbox('online_only', true, null, array('class' => 'check-online-only')) !!} Online Only
                    </label>
                </div>
            </div>
            <div class="col-sm-4 {{ $errors->has('date_start') ? 'has-error' : '' }}">
                {!! Form::label('date_start', 'Start Date', array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('date_start', $course->online_only ? null : $course->date_start, array('class' => 'form-control form-input-date', 'placeholder' => 'Start Date *')) !!}
                    <span class="help-block">{{ $errors->first('date_start', ':message') }}</span>
                </div>
            </div>
            <div class="col-sm-4 {{ $errors->has('date_end') ? 'has-error' : '' }}">
                {!! Form::label('date_end', 'End Date', array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('date_end', $course->online_only ? null : $course->date_end, array('class' => 'form-control form-input-date', 'placeholder' => 'End Date *')) !!}
                    <span class="help-block">{{ $errors->first('date_end', ':message') }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group {{ $errors->has('overview') ? 'has-error' : '' }}">
        {!! Form::label('overview', 'Overview', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::textarea('overview', $course->overview, array('class' => 'form-control', 'placeholder' => 'Overview *', 'rows' => '5')) !!}
            <span class="help-block">{{ $errors->first('overview', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('objective') ? 'has-error' : '' }}">
        {!! Form::label('objective', 'Objective', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::textarea('objective', $course->objective, array('class' => 'form-control', 'placeholder' => 'Objective *', 'rows' => '5')) !!}
            <span class="help-block">{{ $errors->first('objective', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('prerequisites') ? 'has-error' : '' }}">
        {!! Form::label('prerequisites', 'Pre-requisites', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::textarea('prerequisites', $course->prerequisites, array('class' => 'form-control', 'placeholder' => 'Pre-requisites', 'rows' => '5')) !!}
            <span class="help-block">{{ $errors->first('prerequisites', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('resources') ? 'has-error' : '' }}">
        {!! Form::label('resources', 'Resources', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::textarea('resources',  $course->resources, array('class' => 'form-control', 'placeholder' => 'Textbook or Additonal Resources', 'rows' => '5')) !!}
            <span class="help-block">{{ $errors->first('resources', ':message') }}</span>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <a class="btn btn-danger" href="{{ url('admin/courses').'/'.$course->id.'/delete' }}" onclick="return confirm('Are you sure?')">
                    Delete Course
                </a>
            </div>
            <div class="col-md-4 text-right">
                <button type="submit" class="btn btn-primary">
                    Update Course
                </button>
            </div>
        </div>
    </div>
@endsection
