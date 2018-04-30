@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Edit User @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Create User Profile
    </h2>

    @if (Session::has('message'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    {!! Form::open(array('url' => url('admin/users'), 'method' => 'post', 'class' => 'form-user')) !!}
        <div class="form-group">
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
        </div>

        <h3 class="section-title">Personal Information</h3>
        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
            {!! Form::label('first_name', 'First Name', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('first_name', null, array('class' => 'form-control', 'placeholder' => 'First Name *')) !!}
                <span class="help-block">{{ $errors->first('first_name', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
            {!! Form::label('last_name', 'Last Name', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('last_name', null, array('class' => 'form-control', 'placeholder' => 'Last Name *')) !!}
                <span class="help-block">{{ $errors->first('last_name', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            {!! Form::label('email', trans('site/user.e_mail'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'Email *')) !!}
                <span class="help-block">{{ $errors->first('email', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('birthday') ? 'has-error' : '' }}">
            {!! Form::label('birthday', 'Date of Birth', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('birthday', null, array('class' => 'form-control form-input-date', 'placeholder' => 'Date of Birth *', 'data-date-end-date' => '0d')) !!}
                <span class="help-block">{{ $errors->first('birthday', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
            {!! Form::label('phone', 'Telephone', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('phone', null, array('class' => 'form-control', 'placeholder' => 'Telephone *')) !!}
                <span class="help-block">{{ $errors->first('phone', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
            {!! Form::label('address', 'Address', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('address', null, array('class' => 'form-control', 'placeholder' => 'Address')) !!}
                <span class="help-block">{{ $errors->first('address', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('unit') ? 'has-error' : '' }}">
            {!! Form::label('unit', 'Apt/Unit', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('unit', null, array('class' => 'form-control', 'placeholder' => 'Apt/Unit')) !!}
                <span class="help-block">{{ $errors->first('unit', ':message') }}</span>
            </div>
        </div>
        <div class="form-group">
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
        </div>

        <h3 class="section-title">Hospital/Trauma Center Information</h3>
        <div class="form-group {{ $errors->has('hospital_name') ? 'has-error' : '' }}">
            {!! Form::label('hospital_name', 'Hospital/Trauma Center Name *', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('hospital_name', null, array('class' => 'form-control', 'placeholder' => 'Hospital/Trauma Center Name *')) !!}
                <span class="help-block">{{ $errors->first('hospital_name', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('hospital_level') ? 'has-error' : '' }}">
            {!! Form::label('hospital_level', 'Trauma Center Level (Level 1-5, In Pursuit, or N/A)', array('class' => 'control-label shown')) !!}
            <div class="controls">
                {!! Form::select('hospital_level', [
                    'n/a' => 'N/A',
                    '0' => 'In Pursuit',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5'
                    ], null, array('class' => 'form-control')
                ) !!}
                <span class="help-block">{{ $errors->first('hospital_level', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('hospital_ntdb') ? 'has-error' : '' }}">
            {!! Form::label('hospital_ntdb', 'NTDB/NTDS #', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('hospital_ntdb', null, array('class' => 'form-control', 'placeholder' => 'NTDB/NTDS #')) !!}
                <span class="help-block">{{ $errors->first('hospital_ntdb', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('hospital_tqip') ? 'has-error' : '' }}">
            {!! Form::label('hospital_tqip', 'TQIP #', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('hospital_tqip', null, array('class' => 'form-control', 'placeholder' => 'TQIP #')) !!}
                <span class="help-block">{{ $errors->first('hospital_tqip', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('hospital_address1') ? 'has-error' : '' }}">
            {!! Form::label('hospital_address1', 'Address 1 *', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('hospital_address1', null, array('class' => 'form-control', 'placeholder' => 'Address 1 *')) !!}
                <span class="help-block">{{ $errors->first('hospital_address1', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('hospital_address2') ? 'has-error' : '' }}">
            {!! Form::label('hospital_address2', 'Address 2', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('hospital_address2', null, array('class' => 'form-control', 'placeholder' => 'Address 2')) !!}
                <span class="help-block">{{ $errors->first('hospital_address2', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('hospital_address3') ? 'has-error' : '' }}">
            {!! Form::label('hospital_address3', 'Address 3', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('hospital_address3', null, array('class' => 'form-control', 'placeholder' => 'Address 3')) !!}
                <span class="help-block">{{ $errors->first('hospital_address3', ':message') }}</span>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6 {{ $errors->has('hospital_city') ? 'has-error' : '' }}">
                    {!! Form::label('hospital_city', 'City *', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('hospital_city', null, array('class' => 'form-control', 'placeholder' => 'City *')) !!}
                        <span class="help-block">{{ $errors->first('hospital_city', ':message') }}</span>
                    </div>
                </div>
                <div class="col-sm-3 {{ $errors->has('hospital_state') ? 'has-error' : '' }}">
                    {!! Form::label('hospital_state', 'State *', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('hospital_state', null, array('class' => 'form-control', 'placeholder' => 'State *')) !!}
                        <span class="help-block">{{ $errors->first('hospital_state', ':message') }}</span>
                    </div>
                </div>
                <div class="col-sm-3 {{ $errors->has('hospital_zipcode') ? 'has-error' : '' }}">
                    {!! Form::label('hospital_zipcode', 'Zip Code *', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('hospital_zipcode', null, array('class' => 'form-control', 'placeholder' => 'Zip Code *')) !!}
                        <span class="help-block">{{ $errors->first('hospital_zipcode', ':message') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="section-title">Required for Continuing Education (CE)</h3>
        <div class="form-group {{ $errors->has('ssn') ? 'has-error' : '' }}">
            {!! Form::label('ssn', 'Last 4 of SSN', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('ssn', null, array('class' => 'form-control', 'placeholder' => 'Last 4 of SSN')) !!}
                <span class="help-block">{{ $errors->first('ssn', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('credentials') ? 'has-error' : '' }}">
            {!! Form::label('credentials', 'Credentials (RN, MD, EMT, CMISS, etc)', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('credentials',null, array('class' => 'form-control', 'placeholder' => 'Credentials (RN, MD, EMT, CMISS, etc)')) !!}
                <span class="help-block">{{ $errors->first('credentials', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('state_license') ? 'has-error' : '' }}">
            {!! Form::label('state_license', 'State License # (or N/A)', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('state_license', null, array('class' => 'form-control', 'placeholder' => 'State License # (or N/A)')) !!}
                <span class="help-block">{{ $errors->first('state_license', ':message') }}</span>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-8">
                </div>
                <div class="col-md-4 text-right">
                    <button type="submit" class="btn btn-primary">
                        Create a Profile
                    </button>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@endsection
