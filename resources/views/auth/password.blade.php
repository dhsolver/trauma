@extends('layouts.app')

@section('title')Forgot Password @endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <!-- <div class="panel-heading">Reset Password</div> -->
                    <div class="panel-body">
                        @if (Session::has('status'))
                            <div class="alert alert-success" role="alert">
                                {{ Session::get('status') }}
                            </div>
                        @else
                            {!! Form::open(array('url' => url('password/email'), 'method' => 'post')) !!}
                            <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                                {!! Form::label('email', "E-Mail Address", array('class' => 'control-label')) !!}
                                <div class="controls">
                                    {!! Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'Your email address')) !!}
                                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" class="btn btn-primary">
                                        Send Password Reset Link
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
