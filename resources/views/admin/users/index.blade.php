@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Users @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Users
    </h2>

    @if (Session::has('userMessage'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('userMessage') }}
        </div>
    @endif

    @if (Session::has('message'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    {!! Form::open(array('url' => url('admin/users'), 'method' => 'get', 'id' => 'form-users-search')) !!}
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('id', 'User ID', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('id', Request::get('id'), array('class' => 'form-control', 'placeholder' => 'User ID')) !!}
                        <span class="help-block">{{ $errors->first('id', ':message') }}</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    {!! Form::label('email', 'Email', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('email', Request::get('email'), array('class' => 'form-control', 'placeholder' => 'Email')) !!}
                        <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    {!! Form::label('first_name', 'First Name', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('first_name', Request::get('first_name'), array('class' => 'form-control', 'placeholder' => 'First Name')) !!}
                        <span class="help-block">{{ $errors->first('first_name', ':message') }}</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    {!! Form::label('last_name', 'Last Name', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('last_name', Request::get('last_name'), array('class' => 'form-control', 'placeholder' => 'Last Name')) !!}
                        <span class="help-block">{{ $errors->first('last_name', ':message') }}</span>
                    </div>
                </div>
            </div>
            <div class="row form-horizontal">
                <div class="col-sm-3">
                    {!! Form::label('account', 'Account Status', array('class' => 'control-label shown')) !!}
                </div>
                <div class="col-sm-9">
                    <div class="checkbox inline">
                        <label>
                            {!! Form::checkbox('approval[]', 'approved', is_array(Request::get('approval')) && in_array('approved', Request::get('approval'))) !!} Approved
                        </label>
                    </div>
                    <div class="checkbox inline">
                        <label>
                            {!! Form::checkbox('approval[]', 'denied', is_array(Request::get('approval')) && in_array('denied', Request::get('approval'))) !!} Denied
                        </label>
                    </div>
                    <div class="checkbox inline">
                        <label>
                            {!! Form::checkbox('approval[]', 'pending', is_array(Request::get('approval')) && in_array('pending', Request::get('approval'))) !!} Pending
                        </label>
                    </div>
                </div>
                <div class="col-sm-3">
                    {!! Form::label('account', 'Account Type', array('class' => 'control-label shown')) !!}
                </div>
                <div class="col-sm-9">
                    <div class="checkbox inline">
                        <label>
                            {!! Form::checkbox('role[]', 'student', is_array(Request::get('role')) && in_array('student', Request::get('role'))) !!} Student
                        </label>
                    </div>
                    <div class="checkbox inline">
                        <label>
                            {!! Form::checkbox('role[]', 'faculty', is_array(Request::get('role')) && in_array('faculty', Request::get('role'))) !!} Faculty
                        </label>
                    </div>
                    <div class="checkbox inline">
                        <label>
                            {!! Form::checkbox('role[]', 'admin', is_array(Request::get('role')) && in_array('admin', Request::get('role'))) !!} Administrator
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-8">
                </div>
                <div class="col-md-4 text-right">
                    <button type="submit" class="btn btn-sm btn-primary">
                        Search Users
                    </button>
                </div>
            </div>
        </div>
    {!! Form::close() !!}

    <h3 class="section-title">
        Search Results
        <div class="pull-right">
            <button class="btn btn-sm btn-primary" id="users-search-export">
                <i class="fa fa-download"></i> Export To CSV
            </button>
        </div>
    </h3>
    <div class="table-responsive table-container">
        <table class="table table-hover">
            @foreach ($users as $user)
            <tr>
                <td><a href="{!! url('admin/users/'.$user->id.'/edit') !!}">#{{ $user->id }}</a></td>
                <td><a href="{!! url('admin/users/'.$user->id.'/edit') !!}">{{ $user->first_name }} {{ $user->last_name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->birthday }}</td>
                <td>{{ $user->hospital_name }}</td>
                <td>
                    <label class="label {{ $user->role == 'admin' ? 'label-danger' : ($user->role === 'manager' ? 'label-warning' : ($user->role === 'faculty' ? 'label-info' : 'label-primary')) }}">{{ ucfirst($user->role) }}</label>
                </td>
                <td>
                    @if ($user->role !== 'admin')
                    <label class="label {{ $user->approval === 'denied' ? 'label-danger' : ($user->approval === 'pending' ? 'label-default' : 'label-success') }}">{{ ucfirst($user->approval) }}</label>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
        @if (!count($users))
        <h4>No records found for given search.</h4>
        @endif
    </div>

    <h3 class="section-title">
        Faculties & Administrators
        <div class="pull-right">
            <a href="{!! url('admin/users/create') !!}" class="btn btn-sm btn-primary">Invite a Faculty</a>
        </div>
    </h3>
    <div class="table-responsive table-container">
        <table class="table table-hover">
            @foreach ($managers as $user)
            <tr>
                <td><a href="{!! url('admin/users/'.$user->id.'/edit') !!}">#{{ $user->id }}</a></td>
                <td><a href="{!! url('admin/users/'.$user->id.'/edit') !!}">{{ $user->first_name }} {{ $user->last_name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>
                    <label class="label {{ $user->role == 'admin' ? 'label-danger' : ($user->role === 'faculty' ? 'label-info' : 'label-primary') }}">{{ ucfirst($user->role) }}</label>
                </td>
                <td>
                    @if ($user->role !== 'admin')
                    <label class="label {{ $user->approval === 'denied' ? 'label-danger' : ($user->approval === 'pending' ? 'label-default' : 'label-success') }}">{{ ucfirst($user->approval) }}</label>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        $('#users-search-export').on('click', function(e) {
            var id = window.location.href.indexOf('?')
            if (id >= 0) {
                window.location.href = window.location.href + '&export=csv';
            } else {
                window.location.href = window.location.href + '?export=csv';
            }
            e.preventDefault();
        });
    });
</script>
@endsection
