@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Edit User @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Create Organization
    </h2>

    @if (Session::has('message'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    {!! Form::open(array('url' => url('admin/organizations'), 'method' => 'post', 'class' => 'form-user')) !!}
        {{-- <div class="form-group">
            <div class="row form-horizontal">
                <div class="col-sm-3">
                    {!! Form::label('account', 'Account Type', array('class' => 'control-label shown')) !!}
                </div>
                <div class="col-sm-9">
                    <div class="checkbox inline">
                        <label>
                            {!! Form::radio('role', 'faculty', true) !!} Faculty
                        </label>
                    </div>
                    <div class="checkbox inline">
                        <label>
                            {!! Form::radio('role', 'admin') !!} Admin
                        </label>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <h3 class="section-title">Personal Information</h3> --}}
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            {!! Form::label('name', 'Name', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Name *')) !!}
                <span class="help-block">{{ $errors->first('name', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
            {!! Form::label('address', 'Address', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('address', null, array('class' => 'form-control', 'placeholder' => 'Address')) !!}
                <span class="help-block">{{ $errors->first('address', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('contact_name') ? 'has-error' : '' }}">
            {!! Form::label('contact_name', 'Contact Name', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('contact_name', null, array('class' => 'form-control', 'placeholder' => 'Contact Name *')) !!}
                <span class="help-block">{{ $errors->first('contact_name', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('contact_email') ? 'has-error' : '' }}">
            {!! Form::label('contact_email', 'Contact Email', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('contact_email', null, array('class' => 'form-control', 'placeholder' => 'Contact Email *')) !!}
                <span class="help-block">{{ $errors->first('contact_email', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('contact_phone') ? 'has-error' : '' }}">
            {!! Form::label('contact_phone', 'Contact Phone', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('contact_phone', null, array('class' => 'form-control', 'placeholder' => 'Contact Phone *')) !!}
                <span class="help-block">{{ $errors->first('contact_phone', ':message') }}</span>
            </div>
        </div>
        {{-- <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('city', 'City', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('city', null, array('class' => 'form-control', 'placeholder' => 'City')) !!}
                        <span class="help-block">{{ $errors->first('city', ':message') }}</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    {!! Form::label('state', 'State', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('state', null, array('class' => 'form-control', 'placeholder' => 'State')) !!}
                        <span class="help-block">{{ $errors->first('state', ':message') }}</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    {!! Form::label('zipcode', 'Zip Code', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('zipcode', null, array('class' => 'form-control', 'placeholder' => 'Zip Code')) !!}
                        <span class="help-block">{{ $errors->first('zipcode', ':message') }}</span>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="form-group">
            <div class="row">
                <div class="col-xs-12 text-right">
                    <button type="submit" class="btn btn-primary">
                        Create an Organization
                    </button>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@endsection
