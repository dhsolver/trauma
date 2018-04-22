@extends('layouts.app')

@section('title') Register @endsection

{{-- Content --}}
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="content-box">
            {!! Form::open(array('url' => url('auth/register'), 'method' => 'post', 'class'=> 'form-register')) !!}
                <div class="form-group  {{ $errors->has('name') ? 'has-error' : '' }}">
                    {!! Form::label('name', trans('site/user.name'), array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Name *')) !!}
                        <span class="help-block">{{ $errors->first('name', ':message') }}</span>
                    </div>
                </div>
                <div class="form-group  {{ $errors->has('username') ? 'has-error' : '' }}">
                    {!! Form::label('username', 'Username', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Username *')) !!}
                        <span class="help-block">{{ $errors->first('username', ':message') }}</span>
                    </div>
                </div>
                <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                    {!! Form::label('email', trans('site/user.e_mail'), array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'Email *')) !!}
                        <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                    </div>
                </div>
                <div class="form-group  {{ $errors->has('password') ? 'has-error' : '' }}">
                    {!! Form::label('password', "Password", array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password *')) !!}
                        <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                    </div>
                </div>
                <div class="form-group  {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                    {!! Form::label('password_confirmation', "Confirm Password", array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::password('password_confirmation', array('class' => 'form-control', 'placeholder' => 'Verify Password *')) !!}
                        <span class="help-block">{{ $errors->first('password_confirmation', ':message') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-8">
                            <small>
                                By registering, you state that you agree to our <a href="/terms" class="btn-link">Terms & Policies</a>.
                            </small>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type="submit" class="btn btn-primary pull-right">
                                Register
                            </button>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
