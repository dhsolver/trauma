@extends('layouts.app')

@section('title') Login @endsection

{{-- Content --}}
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="content-box">
            {!! Form::open(array('url' => url('auth/login'), 'method' => 'post', 'class'=> 'form-login')) !!}
                <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                    {!! Form::label('email', "E-Mail Address", array('class' => 'control-label')) !!}
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
                <div class="form-group">
                    <button type="submit" class="btn btn-primary pull-right">
                        Sign In
                    </button>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    <a href="{{ url('/password/email') }}" class="pull-left">Forgot Your Password?</a>
                    <a href="{{ url('/auth/register') }}"  class="pull-right">Register</a>
                </div>
            {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
