@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') New Course @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Create New Course
    </h2>

    {!! Form::open(array('url' => url('admin/courses'), 'method' => 'post', 'class' => 'form-course', 'files' => true)) !!}
    <div class="form-group {{ $errors->has('photo') ? 'has-error' : '' }}">
        <div class="row">
            <div class="col-xs-offset-3 col-xs-6 col-sm-offset-4 col-sm-4 text-center">
                <img class="img img-photo" alt="no photo" src="{!! url('images/no_photo.png') !!}"/>
                <span class='label label-info' id="upload-file-info"></span>
                <label class="btn btn-primary" for="course-photo">
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
            {!! Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'Title *')) !!}
            <span class="help-block">{{ $errors->first('title', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
        {!! Form::label('location', 'Location', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('location', null, array('class' => 'form-control', 'placeholder' => 'Location *')) !!}
            <span class="help-block">{{ $errors->first('location', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
        {!! Form::label('date', 'Date', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('date', null, array('class' => 'form-control form-input-date', 'placeholder' => 'Date *')) !!}
            <span class="help-block">{{ $errors->first('date', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('overview') ? 'has-error' : '' }}">
        {!! Form::label('overview', 'Overview', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::textarea('overview', null, array('class' => 'form-control', 'placeholder' => 'Overview *', 'rows' => '5')) !!}
            <span class="help-block">{{ $errors->first('overview', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('objective') ? 'has-error' : '' }}">
        {!! Form::label('objective', 'Objective', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::textarea('objective', null, array('class' => 'form-control', 'placeholder' => 'Objective *', 'rows' => '5')) !!}
            <span class="help-block">{{ $errors->first('objective', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('prerequisites') ? 'has-error' : '' }}">
        {!! Form::label('prerequisites', 'Pre-requisites', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::textarea('prerequisites', null, array('class' => 'form-control', 'placeholder' => 'Pre-requisites', 'rows' => '5')) !!}
            <span class="help-block">{{ $errors->first('prerequisites', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('resources') ? 'has-error' : '' }}">
        {!! Form::label('resources', 'Resources', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::textarea('resources', null, array('class' => 'form-control', 'placeholder' => 'Textbook or Additonal Resources', 'rows' => '5')) !!}
            <span class="help-block">{{ $errors->first('resources', ':message') }}</span>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <button type="submit" class="btn btn-primary">
                    Create Course
                </button>
            </div>
        </div>
    </div>
@endsection
