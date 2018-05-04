@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')New Course Module @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        New Course Module <small>/ <a href="{{ url('admin/courses/'.$course->id.'/edit') }}">{{ $course->title }}</a></small>
    </h2>

    @if (Session::has('message'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    {!! Form::open(array('url' => url('admin/courses/'.$course->id.'/modules'), 'method' => 'post', 'class' => 'form-course-module')) !!}
    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
        {!! Form::label('title', 'Title', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'Title *')) !!}
            <span class="help-block">{{ $errors->first('title', ':message') }}</span>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 text-right">
                <button type="submit" class="btn btn-primary">
                    Save
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
