@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Edit User @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Edit User Profile
    </h2>

    @if (Session::has('userMessage'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('userMessage') }}
        </div>
    @endif

    {!! Form::model($user, array('url' => url('admin/users/'.$user->id), 'method' => 'put', 'class' => 'form-user')) !!}
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4 text-center">
                    @if ($user->avatar)
                        <img class="img img-avatar img-circle" alt="{{$user->avatar}}" src="{!! url('images/users/'.$user->id.'/'.$user->avatar) !!}"/>
                    @else
                        <img class="img img-avatar img-circle" alt="no avatar" src="{!! url('images/no_photo.png') !!}"/>
                    @endif
                </div>
                <div class="col-sm-offset-1 col-sm-7 hidden-xs">
                    <h3>Full Name: {{ $user->first_name }} {{ $user->last_name }}</h3>
                    <div class="user-info">ID: #{{ $user->id }}</div>
                    @if (!$user->admin)
                    <div class="user-info">Account Type: {{ ucfirst($user->role) }}</div>
                    @endif

                    @if (!$user->admin && $user->role === 'student')
                    <div class="user-info">
                        Approval Status:
                        @if ($user->approval === 'approved')
                        <label class="label label-success">Approved</label>
                        @elseif ($user->approval === 'denied')
                        <label class="label label-danger">Denied</label>
                        @elseif ($user->approval === 'pending')
                        <label class="label label-default">Pending</label>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <h3 class="section-title">Personal Information</h3>
        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
            {!! Form::label('first_name', 'First Name', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('first_name', $user->first_name, array('class' => 'form-control', 'placeholder' => 'First Name *')) !!}
                <span class="help-block">{{ $errors->first('first_name', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
            {!! Form::label('last_name', 'Last Name', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('last_name', $user->last_name, array('class' => 'form-control', 'placeholder' => 'Last Name *')) !!}
                <span class="help-block">{{ $errors->first('last_name', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            {!! Form::label('email', trans('site/user.e_mail'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('email', $user->email, array('class' => 'form-control', 'placeholder' => 'Email *')) !!}
                <span class="help-block">{{ $errors->first('email', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            {!! Form::label('password', "Password", array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password *')) !!}
                <span class="help-block">{{ $errors->first('password', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
            {!! Form::label('password_confirmation', "Verify Password", array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::password('password_confirmation', array('class' => 'form-control', 'placeholder' => 'Verify Password *')) !!}
                <span class="help-block">{{ $errors->first('password_confirmation', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('birthday') ? 'has-error' : '' }}">
            {!! Form::label('birthday', 'Date of Birth', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('birthday', $user->birthday, array('class' => 'form-control form-input-date', 'placeholder' => 'Date of Birth *', 'data-date-end-date' => '0d')) !!}
                <span class="help-block">{{ $errors->first('birthday', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
            {!! Form::label('phone', 'Telephone', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('phone', $user->phone, array('class' => 'form-control', 'placeholder' => 'Telephone *')) !!}
                <span class="help-block">{{ $errors->first('phone', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
            {!! Form::label('address', 'Address', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('address', $user->address, array('class' => 'form-control', 'placeholder' => 'Address')) !!}
                <span class="help-block">{{ $errors->first('address', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('unit') ? 'has-error' : '' }}">
            {!! Form::label('unit', 'Apt/Unit', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('unit', $user->unit, array('class' => 'form-control', 'placeholder' => 'Apt/Unit')) !!}
                <span class="help-block">{{ $errors->first('unit', ':message') }}</span>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('city', 'City', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('city', $user->city, array('class' => 'form-control', 'placeholder' => 'City')) !!}
                        <span class="help-block">{{ $errors->first('city', ':message') }}</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    {!! Form::label('state', 'State', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('state', $user->state, array('class' => 'form-control', 'placeholder' => 'State')) !!}
                        <span class="help-block">{{ $errors->first('state', ':message') }}</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    {!! Form::label('zipcode', 'Zip Code', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('zipcode', $user->zipcode, array('class' => 'form-control', 'placeholder' => 'Zip Code')) !!}
                        <span class="help-block">{{ $errors->first('zipcode', ':message') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="section-title">Hospital/Trauma Center Information</h3>
        <div class="form-group {{ $errors->has('hospital_name') ? 'has-error' : '' }}">
            {!! Form::label('hospital_name', 'Hospital/Trauma Center Name *', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('hospital_name', $user->hospital_name, array('class' => 'form-control', 'placeholder' => 'Hospital/Trauma Center Name *')) !!}
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
                    ], $user->hospital_level, array('class' => 'form-control')
                ) !!}
                <span class="help-block">{{ $errors->first('hospital_level', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('hospital_ntdb') ? 'has-error' : '' }}">
            {!! Form::label('hospital_ntdb', 'NTDB/NTDS #', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('hospital_ntdb', $user->hospital_ntdb, array('class' => 'form-control', 'placeholder' => 'NTDB/NTDS #')) !!}
                <span class="help-block">{{ $errors->first('hospital_ntdb', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('hospital_tqip') ? 'has-error' : '' }}">
            {!! Form::label('hospital_tqip', 'TQIP #', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('hospital_tqip', $user->hospital_tqip, array('class' => 'form-control', 'placeholder' => 'TQIP #')) !!}
                <span class="help-block">{{ $errors->first('hospital_tqip', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('hospital_address1') ? 'has-error' : '' }}">
            {!! Form::label('hospital_address1', 'Address 1 *', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('hospital_address1', $user->hospital_address1, array('class' => 'form-control', 'placeholder' => 'Address 1 *')) !!}
                <span class="help-block">{{ $errors->first('hospital_address1', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('hospital_address2') ? 'has-error' : '' }}">
            {!! Form::label('hospital_address2', 'Address 2', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('hospital_address2', $user->hospital_address2, array('class' => 'form-control', 'placeholder' => 'Address 2')) !!}
                <span class="help-block">{{ $errors->first('hospital_address2', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('hospital_address3') ? 'has-error' : '' }}">
            {!! Form::label('hospital_address3', 'Address 3', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('hospital_address3', $user->hospital_address3, array('class' => 'form-control', 'placeholder' => 'Address 3')) !!}
                <span class="help-block">{{ $errors->first('hospital_address3', ':message') }}</span>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6 {{ $errors->has('hospital_city') ? 'has-error' : '' }}">
                    {!! Form::label('hospital_city', 'City *', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('hospital_city', $user->hospital_city, array('class' => 'form-control', 'placeholder' => 'City *')) !!}
                        <span class="help-block">{{ $errors->first('hospital_city', ':message') }}</span>
                    </div>
                </div>
                <div class="col-sm-3 {{ $errors->has('hospital_state') ? 'has-error' : '' }}">
                    {!! Form::label('hospital_state', 'State *', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('hospital_state', $user->hospital_state, array('class' => 'form-control', 'placeholder' => 'State *')) !!}
                        <span class="help-block">{{ $errors->first('hospital_state', ':message') }}</span>
                    </div>
                </div>
                <div class="col-sm-3 {{ $errors->has('hospital_zipcode') ? 'has-error' : '' }}">
                    {!! Form::label('hospital_zipcode', 'Zip Code *', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('hospital_zipcode', $user->hospital_zipcode, array('class' => 'form-control', 'placeholder' => 'Zip Code *')) !!}
                        <span class="help-block">{{ $errors->first('hospital_zipcode', ':message') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="section-title">Required for Continuing Education (CE)</h3>
        <div class="form-group {{ $errors->has('ssn') ? 'has-error' : '' }}">
            {!! Form::label('ssn', 'Last 4 of SSN', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('ssn', $user->ssn, array('class' => 'form-control', 'placeholder' => 'Last 4 of SSN')) !!}
                <span class="help-block">{{ $errors->first('ssn', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('credentials') ? 'has-error' : '' }}">
            {!! Form::label('credentials', 'Credentials (RN, MD, EMT, CMISS, etc)', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('credentials', $user->credentials, array('class' => 'form-control', 'placeholder' => 'Credentials (RN, MD, EMT, CMISS, etc)')) !!}
                <span class="help-block">{{ $errors->first('credentials', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('state_license') ? 'has-error' : '' }}">
            {!! Form::label('state_license', 'State License # (or N/A)', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('state_license', $user->state_license, array('class' => 'form-control', 'placeholder' => 'State License # (or N/A)')) !!}
                <span class="help-block">{{ $errors->first('state_license', ':message') }}</span>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-8">

                    @if ($user->role === 'student')
                    @if ($user->approval != 'approved')
                    <a href="{{ url('admin/users/'.$user->id.'/approve') }}" class="btn btn-success" onclick="return confirm('Are you sure?')">
                        Approve
                    </a>
                    @endif
                    @if ($user->approval != 'denied')
                    <a href="{{ url('admin/users/'.$user->id.'/deny') }}" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                        Deny
                    </a>
                    @endif
                    @endif
                </div>
                <div class="col-md-4 text-right">
                    <button type="submit" class="btn btn-primary">
                        Update Profile
                    </button>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@endsection
