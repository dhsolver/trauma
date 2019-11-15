@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Edit Organization @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Edit Organization
    </h2>

    @if (Session::has('message'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    {!! Form::model($organization, array('url' => url('admin/organizations/'.$organization->id), 'method' => 'put', 'class' => 'form-organization')) !!}
        <h3 class="section-title">Information</h3>
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            {!! Form::label('name', 'Name', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('name', $organization->name, array('class' => 'form-control', 'placeholder' => 'Name *')) !!}
                <span class="help-block">{{ $errors->first('name', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
            {!! Form::label('address', 'Address', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('address', $organization->address, array('class' => 'form-control', 'placeholder' => 'Address')) !!}
                <span class="help-block">{{ $errors->first('address', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('contact_name') ? 'has-error' : '' }}">
            {!! Form::label('contact_name', 'Address', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('contact_name', $organization->contact_name, array('class' => 'form-control', 'placeholder' => 'Contact Name *')) !!}
                <span class="help-block">{{ $errors->first('contact_name', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('contact_email') ? 'has-error' : '' }}">
            {!! Form::label('contact_email', 'Contact Email', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('contact_email', $organization->contact_email, array('class' => 'form-control', 'placeholder' => 'Contact Email *')) !!}
                <span class="help-block">{{ $errors->first('contact_email', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('contact_phone') ? 'has-error' : '' }}">
            {!! Form::label('contact_phone', 'Contact Phone', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('contact_phone', $organization->contact_phone, array('class' => 'form-control', 'placeholder' => 'Contact Phone *')) !!}
                <span class="help-block">{{ $errors->first('contact_phone', ':message') }}</span>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-xxs-12 text-right">
                    <button type="submit" class="btn btn-primary">
                        Update Organization
                    </button>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
    <h3 class="section-title">Assigned Users</h3>
    <div class="table-responsive table-container">
        @if (is_array($organization->assigned_users) && count($organization->assigned_users))
        <table class="table table-hover table-course-keys">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                </tr>
            </thead>
            @foreach ($organization->assigned_users as $user_id)
            <?php $user = $users[$user_id]; ?>
            <tr>
                <td><a href="{!! url('admin/uses/'.$user_id.'/edit') !!}">#{{ $user['id'] }}</a></td>
                <td><a href="{!! url('admin/uses/'.$user_id.'/edit') !!}">{{ $user['first_name'] }} {{ $user['last_name'] }}</a></td>
                <td>{{ $user['email'] }}</td>
                <td>
                    @if ($user['approval'] === 'pending')
                        <label class="label label-warning">Pending</label>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
        @else
        <h4>No users assigned.</h4>
        @endif
    </div>

    @if (Auth::user()->role === 'admin')
    <div class="form-group">
        {!! Form::label('users', 'Available Users(Faculty, Manager)', array('class' => 'control-label shown')) !!}
        <div class="controls users">
            @foreach ($users as $user)
            @if ($user['approval'] === 'approved')
            <div class="user" data-user="{{ $user['id'] }}" data-url={{ url('admin/organizations/'.$organization->id.'/assigned_users') }}>
                <div><a href="{!! url('admin/uses/'.$user['id'].'/edit') !!}">{{ $user['first_name'] }} {{ $user['last_name'] }} ({{ $user['email'] }})</a></div>
                @if (in_array($user['id'], $organization->assigned_users))
                <button class="btn btn-danger btn-xs">Remove</button>
                @else
                <button class="btn btn-primary btn-xs">Add</button>
                @endif
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    <h3 class="section-title">Assigned Courses</h3>
    <div class="table-responsive table-container">
        <table class="table table-hover">
            @foreach ($courses as $course)
            <tr>
                <td><a href="{!! url('admin/courses/'.$course->id.'/edit') !!}">#{{ $course->id }}</a></td>
                <td>
                    <a href="{!! url('admin/courses/'.$course->id.'/edit') !!}">{{ $course->title }}</a><br>
                    {{ $course->online_only ? 'Online' : $course->date_start . '-' . $course->date_end }}
                </td>
                <td>{{ $course->location }}</td>
                <td>
                    @if (is_array($course->instructors) && count($course->instructors) > 0)
                        @for ($i = 0; $i < count($course->instructors); $i++)
                        {{ $users[$course->instructors[$i]]['first_name'] }} {{ $users[$course->instructors[$i]]['last_name'] }}
                            @if ($i < count($course->instructors)-1)<br> @endif
                        @endfor
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
        @if (!count($courses))
        <h4>No courses assigned.</h4>
        @endif
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        $('.users .user button').click(function(event) {
            event.preventDefault();

            var user_id = $(this).parent().data('user');
            var url = $(this).parent().data('url') + '?user_id=' + user_id;

            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    if (data.success) {
                        location.href = data.redirect;
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });
</script>
@endsection
