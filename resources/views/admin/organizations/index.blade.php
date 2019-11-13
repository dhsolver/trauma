@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Organizations @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Organizations
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

    {{-- {!! Form::open(array('url' => url('admin/users'), 'method' => 'get', 'id' => 'form-users-search')) !!}
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
    {!! Form::close() !!} --}}

    <h3 class="section-title">
        Search Results
        <div class="pull-right">
            <a href="{!! url('admin/organizations/create') !!}" class="btn btn-sm btn-primary" id="create-organization">
                Create New Organization
            </a>
        </div>
    </h3>
    <div class="table-responsive table-container">
        <table class="table table-hover">
            @foreach ($organizations as $organization)
            <tr>
                <td><a href="{!! url('admin/organizations/'.$organization->id.'/edit') !!}">#{{ $organization->id }}</a></td>
                <td><a href="{!! url('admin/organizations/'.$organization->id.'/edit') !!}">{{ $organization->name }}</a></td>
                <td>{{ $organization->contact_name }}</td>
                <td>{{ $organization->contact_email }}</td>
            </tr>
            @endforeach
        </table>
        @if (!count($organizations))
        <h4>No records found for given search.</h4>
        @endif
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
