@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')New Course @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Create New Course
    </h2>

    @if (Session::has('message'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    {!! Form::open(array('url' => url('admin/courses'), 'method' => 'post', 'class' => 'form-course')) !!}
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
                    {!! Form::text('date_start', null, array('class' => 'form-control form-input-date', 'placeholder' => 'Start Date *')) !!}
                    <span class="help-block">{{ $errors->first('date_start', ':message') }}</span>
                </div>
            </div>
            <div class="col-sm-4 {{ $errors->has('date_end') ? 'has-error' : '' }}">
                {!! Form::label('date_end', 'End Date', array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('date_end', null, array('class' => 'form-control form-input-date', 'placeholder' => 'End Date *')) !!}
                    <span class="help-block">{{ $errors->first('date_end', ':message') }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group {{ $errors->has('overview') ? 'has-error' : '' }}">
        {!! Form::label('overview', 'Overview', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::textarea('overview', null, array('class' => 'form-control', 'placeholder' => 'Overview *', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('overview', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('objective') ? 'has-error' : '' }}">
        {!! Form::label('objective', 'Objective', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::textarea('objective', null, array('class' => 'form-control', 'placeholder' => 'Objective *', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('objective', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('prerequisites') ? 'has-error' : '' }}">
        {!! Form::label('prerequisites', 'Pre-requisites', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::textarea('prerequisites', null, array('class' => 'form-control', 'placeholder' => 'Pre-requisites', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('prerequisites', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('resources') ? 'has-error' : '' }}">
        {!! Form::label('resources', 'Resources', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::textarea('resources', null, array('class' => 'form-control', 'placeholder' => 'Textbook or Additonal Resources', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('resources', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('continuing_education') ? 'has-error' : '' }}">
        {!! Form::label('continuing_education', 'Continuing Education (CE)', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::textarea('continuing_education', null, array('class' => 'form-control', 'placeholder' => 'Continuing Education (CE)', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('continuing_education', ':message') }}</span>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-sm-12 text-right">
                <button type="submit" class="btn btn-primary">
                    Create Course
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        $('.form-course input[type=checkbox][name=online_only]').change(function(e) {
            var courseForm = $(this).closest('.form-course');
            if (this.checked) {
                courseForm.find('#date_start').prop('disabled', true);
                courseForm.find('#date_end').prop('disabled', true);
            } else {
                courseForm.find('#date_start').prop('disabled', false);
                courseForm.find('#date_end').prop('disabled', false);
            }
        });
        $('.form-course input[type=checkbox][name=online_only]').trigger('change');
    });
</script>
@endsection
