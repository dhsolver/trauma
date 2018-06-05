@extends('layouts.app')
@section('title')Change Password @endsection
{{-- Content --}}
@section('content')
<div class="page-header">
    <h1>Change Password</h1>
</div>
{!! Form::open(array('url' => url('auth/changepassword'), 'method' => 'post', 'files'=> true)) !!}
    <fieldset>
        <div class="form-group  {{ $errors->has('password') ? 'has-error' : '' }}">
            {!! Form::label('password', 'password', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::password('password', array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('password', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
            {!! Form::label('password_confirmation', 'password_confirmation', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('password_confirmation', ':message') }}</span>
            </div>
        </div>
        <div class="form-actions form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </fieldset>
{!! Form::close() !!}
@endsection
