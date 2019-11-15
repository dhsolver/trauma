@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title'){{ $course->title }} @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Edit Course
    </h2>

    @if (Session::has('courseMessage'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('courseMessage') }}
        </div>
    @endif
    @if (Session::has('message'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    @if (Session::has('errors'))
        <div class="alert alert-danger" role="alert">
            Some fields are incorrect, please check below.
        </div>
    @endif

    {!! Form::open(array('id' => 'course-photo-form', 'url' => url('admin/courses/'.$course->id.'/photo'), 'method' => 'post')) !!}
        <!-- <input type="hidden" name="fileKeys[]" value="" /> -->
    {!! Form::close() !!}

    <div class="form-group {{ $errors->has('photo') ? 'has-error' : '' }}">
        <div class="row">
            <div class="col-xs-offset-2 col-xs-8 col-sm-offset-3 col-sm-6 text-center">
                <img class="img img-course" alt="course photo" src="{!! getS3Url($course->photo) !!}"/>
                <div class="m-b-5"><span class='label label-info' id="upload-file-info"></span></div>
                <label class="btn btn-sm btn-primary" for="course-photo">
                    <input id="course-photo" name="photo" type="file" value="Upload" style="display:none"
                    onchange="$('#upload-file-info').html(this.files[0].name); $('#upload-photo-submit').show();">
                    Choose Photo
                </label>
                <span class="help-block">{{ $errors->first('fileKeys', ':message') }}</span>
                <button
                    id="upload-photo-submit"
                    type="submit"
                    class="btn btn-sm btn-primary"
                    style="display:none"
                    data-upload="s3"
                    data-upload-file="#course-photo"
                    data-upload-dir="courses/{{ $course->id }}"
                    data-upload-form="#course-photo-form"
                >
                    Upload Photo
                </button>
            </div>
        </div>
    </div>

    {!! Form::model($course, array('url' => url('admin/courses/'.$course->id), 'method' => 'put', 'class' => 'form-course', 'id' => 'course_form')) !!}
    <input type="hidden" name="online_only" value="0">
    <input type="hidden" name="published" value="0">
    <input type="hidden" name="purchase_enabled" value="0">
    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
        {!! Form::label('title', 'Course Id: #'.$course->id, array('class' => 'control-label shown')) !!}
        <div class="controls">
            {!! Form::text('title', $course->title, array('class' => 'form-control', 'placeholder' => 'Title *')) !!}
            <span class="help-block">{{ $errors->first('title', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
        {!! Form::label('location', 'Location', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('location', $course->location, array('class' => 'form-control', 'placeholder' => 'Location *')) !!}
            <span class="help-block">{{ $errors->first('location', ':message') }}</span>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-3">
                <div class="checkbox inline">
                    <label>
                        {!! Form::checkbox('online_only', true, $course->online_only, array('class' => 'check-online-only')) !!} Online Only
                    </label>
                </div>
            </div>
            <div class="col-sm-4 {{ $errors->has('date_start') ? 'has-error' : '' }}">
                {!! Form::label('date_start', 'Start Date', array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('date_start', $course->online_only ? null : $course->date_start, array('class' => 'form-control form-input-date', 'placeholder' => 'Start Date *')) !!}
                    <span class="help-block">{{ $errors->first('date_start', ':message') }}</span>
                </div>
            </div>
            <div class="col-sm-4 {{ $errors->has('date_end') ? 'has-error' : '' }}">
                {!! Form::label('date_end', 'End Date', array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('date_end', $course->online_only ? null : $course->date_end, array('class' => 'form-control form-input-date', 'placeholder' => 'End Date *')) !!}
                    <span class="help-block">{{ $errors->first('date_end', ':message') }}</span>
                </div>
            </div>
        </div>
    </div>

    <h3 class="section-title">
        Instructors
        @if (Auth::user()->role === 'admin')
        <div class="pull-right">
            <a href="{!! url('admin/users/create') !!}" class="btn btn-sm btn-primary">Invite a Faculty</a>
        </div>
        @endif
    </h3>

    <div class="table-responsive table-container">
        @if (is_array($course->instructors) && count($course->instructors))
        <table class="table table-hover table-course-keys">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                </tr>
            </thead>
            @foreach ($course->instructors as $faculty_id)
            <?php $faculty = $faculties[$faculty_id]; ?>
            <tr>
                <td>{{ $faculty['id'] }}</td>
                <td>{{ $faculty['first_name'] }} {{ $faculty['last_name'] }}</td>
                <td>{{ $faculty['email'] }}</td>
                <td>
                    @if ($faculty['approval'] === 'pending')
                        <label class="label label-warning">Pending</label>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
        @else
        <h4>No instructors assigned.</h4>
        @endif
    </div>

    @if (Auth::user()->role === 'admin')
    <div class="form-group">
        {!! Form::label('instructors', 'Available Instructors', array('class' => 'control-label shown')) !!}
        <div class="controls instructors">
            @foreach ($faculties as $faculty)
            @if ($faculty['approval'] === 'approved')
            <div class="instructor" data-instructor="{{ $faculty['id'] }}" data-url={{ url('admin/courses/'.$course->id.'/instructors') }}>
                <div>{{ $faculty['first_name'] }} {{ $faculty['last_name'] }} ({{ $faculty['email'] }})</div>
                @if (in_array($faculty['id'], $course->instructors))
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

    <h3 class="section-title">
        Organization
    </h3>
    <div class="table-responsive table-container">
        @if (!empty($course->organization_id))
        <table class="table table-hover table-course-keys">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Contact Name</th>
                    <th>Contact Email</th>
                </tr>
            </thead>        
            <?php $organization = $organizations[$course->organization_id]; ?>
            <tr>
                <td>{{ $organization['id'] }}</td>
                <td>{{ $organization['name'] }}</td>
                <td>{{ $organization['contact_name'] }}</td>
                <td>{{ $organization['contact_email'] }}</td>
            </tr>
        </table>
        @else
        <h4>No organization specified.</h4>
        @endif
    </div>

    @if (Auth::user()->role === 'admin')
    <div class="form-group">
        {!! Form::label('organizations', 'Organizations', array('class' => 'control-label shown')) !!}
        <div class="controls organizations">
            @foreach ($organizations as $organization)
            <div class="organization" data-organization="{{ $organization['id'] }}" data-url={{ url('admin/courses/'.$course->id.'/organization') }}>
                <div>{{ $organization['name'] }} ({{ $organization['contact_email'] }})</div>
                @if ($organization['id'] == $course->organization_id)
                <button class="btn btn-danger btn-xs">Remove</button>
                @else
                <button class="btn btn-primary btn-xs">Add</button>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="form-group {{ $errors->has('overview') ? 'has-error' : '' }}">
        {!! Form::label('overview', 'Overview', array('class' => 'control-label shown')) !!}
        <div class="controls">
            {!! Form::textarea('overview', $course->overview, array('class' => 'form-control', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('overview', ':message') }}</span>
        </div>
    </div>

    @if (is_array($course->instructors) && in_array(Auth::user()->id, $course->instructors))
    <div class="form-group {{ $errors->has('instructor_note') ? 'has-error' : '' }}">
        {!! Form::label('instructor_note', 'Instructor Note', array('class' => 'control-label shown')) !!}
        <div class="controls">
            {!! Form::textarea('instructor_note', $course->instructor_note, array('class' => 'form-control', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('instructor_note', ':message') }}</span>
        </div>
    </div>
    @endif

    <div class="form-group {{ $errors->has('objective') ? 'has-error' : '' }}">
        {!! Form::label('objective', 'Objective', array('class' => 'control-label shown')) !!}
        <div class="controls">
            {!! Form::textarea('objective', $course->objective, array('class' => 'form-control', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('objective', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('prerequisites') ? 'has-error' : '' }}">
        {!! Form::label('prerequisites', 'Pre-requisites', array('class' => 'control-label shown')) !!}
        <div class="controls">
            {!! Form::textarea('prerequisites', $course->prerequisites, array('class' => 'form-control', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('prerequisites', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('resources') ? 'has-error' : '' }}">
        {!! Form::label('resources', 'Resources', array('class' => 'control-label shown')) !!}
        <div class="controls">
            {!! Form::textarea('resources',  $course->resources, array('class' => 'form-control', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('resources', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('continuing_education') ? 'has-error' : '' }}">
        {!! Form::label('continuing_education', 'Continuing Education (CE)', array('class' => 'control-label shown')) !!}
        <div class="controls">
            {!! Form::textarea('continuing_education',  $course->continuing_education, array('class' => 'form-control', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('continuing_education', ':message') }}</span>
        </div>
    </div>

    <h3 class="section-title">
        Course Administrative Documents
        <div class="pull-right">
            <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#documentModal">
                <i class="fa fa-plus"></i> Add
            </a>
        </div>
    </h3>
    <div>
        @if (count($course->documents))
            @foreach ($course->documents as $document)
            <div class="row">
                <div class="col-xs-10">
                    <a href="{{ getS3Url($document->file) }}" target="_blank" class="text-break">
                        <i class="fa fa-file-o"></i> {{ $document->filename }}
                    </a>
                </div>
                <div class="col-xs-2 text-right">
                    <a class="btn btn-xs btn-circle  btn-danger" href="{{ url('admin/courses/'.$course->id.'/documents/'.$document->id.'/delete') }}" onclick="return confirm('Are you sure?')">
                        <i class="fa fa-close"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </table>
        @else
            <h4>No administrative documents added.</h4>
        @endif
    </div>

    <hr>

    <h3 class="section-title">
        Course Modules
        <div class="pull-right">
            <a href="{{ url('/admin/courses/'.$course->id.'/modules/create')}}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> Add
            </a>
        </div>
    </h3>
    <div>
        @if (count($course->modules))
        @foreach ($course->modules as $module)
            <hr>
            <div class="row m-t-10">
                <div class="col-xxs-6">
                    <strong>{{ $module->title }}</strong>
                </div>
                <div class="col-xxs-6 text-right">
                    @if ($module->is_visible)
                    <a href="{!! url('admin/courses/'.$course->id.'/modules/'.$module->id.'/hide') !!}" class="btn btn-xs btn-warning">Hide</a>
                    @else
                    <a href="{!! url('admin/courses/'.$course->id.'/modules/'.$module->id.'/show') !!}" class="btn btn-xs btn-info">Show</a>
                    @endif
                    <a href="{!! url('admin/courses/'.$course->id.'/modules/'.$module->id.'/edit') !!}" class="btn btn-xs btn-primary">Edit</a>
                    <a href="{!! url('admin/courses/'.$course->id.'/modules/'.$module->id.'/delete') !!}" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            </div>
            <div class="">
                {!! nl2br(e($module->description)) !!}
            </div>
            @foreach ($module->documents as $document)
            <div class="row">
                <div class="col-sm-11 col-sm-offset-1">
                    @if ($document->type === 'url')
                        @if (!empty($document->display_name))
                            <a href="{{ $document->url }}" target="_blank" class="text-break"><i class="fa {{ $document->icon_class }}"></i> {{ $document->display_name }}</a>
                        @else
                            <a href="{{ $document->url }}" target="_blank" class="text-break"><i class="fa {{ $document->icon_class }}"></i> {{ $document->url }}</a>
                        @endif
                    @else
                        @if (!empty($document->display_name))
                            <a href="{{ getS3Url($document->file) }}" target="_blank" class="text-break"><i class="fa {{ $document->icon_class }}"></i> {{ $document->display_name }}</a>
                        @else
                            <a href="{{ getS3Url($document->file) }}" target="_blank" class="text-break"><i class="fa {{ $document->icon_class }}"></i> {{ $document->filename }}</a>
                        @endif
                    @endif
                </div>
            </div>
            @endforeach
        @endforeach
        @else
            <h4>No course modules added.</h4>
        @endif
    </div>

    <hr>

    <h3 class="section-title">
        Course Price ($)
    </h3>
    <div class="checkbox inline">
        <label>
            {!! Form::checkbox('purchase_enabled', true, $course->purchase_enabled) !!} Enable Purchase
        </label>
    </div>
    <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
        {!! Form::label('price', 'Price', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('price', $course->price, array('class' => 'form-control', 'placeholder' => 'Price')) !!}
            <span class="help-block">{{ $errors->first('price', ':message') }}</span>
        </div>
    </div>

    <hr>

    <h3 class="section-title">
        Course Keys
        <div class="pull-right text-right">
            <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#generateKeyModal">
                Generate Keys
            </a>
            <a href="{{ url('admin/courses/'.$course->id.'/keys/export') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-download"></i> Export to CSV
            </a>
        </div>
    </h3>

    <div class="table-responsive table-container">
        @if (count($course->keys))
        <table class="table table-hover table-course-keys">
            <thead>
                <tr>
                    <th>Key</th>
                    <th>Created At</th>
                    <th>Redeemed</th>
                    <th>Tag</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            @foreach ($course->keys as $key)
            <tr>
                <td>{{ $key->key }}</td>
                <td>{{ $key->created_at }}</td>
                <td>
                    @if ($key->redeemed)
                        <label class="label label-success">
                            {{ $key->redeemedUser->first_name }} {{ $key->redeemedUser->last_name }}
                            on {{ $key->redeemed_at }}
                        </label>
                    @else
                        <label class="label label-default">Not Redeemed</label>
                    @endif
                </td>
                <td><label class="label label-info">{{ $key->tag }}</label></td>
                <td class="text-center">
                    @if (!$key->redeemed)
                        @if ($key->enabled)
                        <a href="{{ url('admin/courses/'.$course->id.'/keys/'.$key->id.'/disable') }}"
                            class="btn btn-xs btn-danger">
                            Disable
                        </a>
                        @else
                        <a href="{{ url('admin/courses/'.$course->id.'/keys/'.$key->id.'/enable') }}"
                            class="btn btn-xs btn-success">
                            Enable
                        </a>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
        @else
        <h4>No keys for this course.</h4>
        @endif
    </div>

    <hr>
    <h3 class="section-title">
        Registered Students
        <div class="pull-right text-right">
            @if (count($course->registrations))
                <?php $mailToAddresses = ''; ?>
                @foreach ($course->registrations as $registration)
                    <?php $mailToAddresses .= $registration->user->email . ';'; ?>
                @endforeach
                <?php $mailToAddresses = substr($mailToAddresses, 0, -1).'?subject='.$course->title ; ?>
                <a href="mailto:{{ $mailToAddresses }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-envelope"></i> Send Email
                </a>    
            @endif
            <a href="{{ url('admin/courses/'.$course->id.'/students/export') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-download"></i> Export to CSV
            </a>
        </div>
    </h3>

    <div class="table-responsive table-container">
        @if (count($course->registrations))
        <table class="table table-hover table-course-keys">
            <thead>
                <tr>
                    <th>Student Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Channel</th>
                    <th>Registered</th>
                    <th>Completed</th>
                    <th>Certified</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            @foreach ($course->registrations as $registration)
            <tr>
                <td>#{{ $registration->user->id }}</td>
                <td>
                    @if (Auth::user()->role === 'admin')
                    <a href="{!! route('user.edit', $registration->user->id) !!}">
                        {{ $registration->user->first_name }} {{ $registration->user->last_name }}
                    </a>
                    @else
                        {{ $registration->user->first_name }} {{ $registration->user->last_name }}
                    @endif
                </td>
                <td>{{ $registration->user->email }}</td>
                <td>
                    @if ($registration->method === 'key')
                        <i class="fa fa-key"></i> Course Key
                    @elseif ($registration->method === 'paypal')
                        <i class="fa fa-paypal"></i> Paypal
                        @if ($registration->payment_status !== 'Completed')
                            <label class="label label-warning">{{ $registration->payment_status }}</label>
                        @endif
                    @endif
                </td>
                <td>{{ $registration->registered_at }}</td>
                <td>{{ $registration->completed_at }}</td>
                <td>{{ $registration->certified_at }}</td>
                <td class="text-center">
                    @if ($registration->payment_status === 'Completed')
                    @if ($registration->certified_at)
                        <a href="{{ url('admin/courses/'.$course->id.'/registrations/'.$registration->id.'/uncertify') }}"
                            class="btn btn-xs btn-80 m-2 btn-danger">
                            Uncertify
                        </a>
                    @else
                        <a href="{{ url('admin/courses/'.$course->id.'/registrations/'.$registration->id.'/certify') }}"
                            class="btn btn-xs btn-80 m-2 btn-success">
                            Certify
                        </a>
                    @endif
                    @if (Auth::user()->role === 'admin')
                    <a
                        href="{{ route('course.unregister', [$course->id, $registration->id]) }}"
                        class="btn btn-xs btn-80 m-2 btn-danger"
                        onclick="return confirm('Are you sure?')"
                    >
                        Remove
                    </a>
                    @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
        @else
        <h4>No registered students for this course.</h4>
        @endif
    </div>

    <hr>

    <div class="form-group">
        <div class="checkbox inline">
            <label>
                {!! Form::checkbox('published', true, $course->published) !!} Published
            </label>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xxs-6">
                <!-- <a class="btn btn-danger" href="{{ url('admin/courses').'/'.$course->id.'/delete' }}" onclick="return confirm('Are you sure?')">
                    Delete Course
                </a> -->
                @if (Auth::user()->role === 'admin')
                @if (!$course->enabled)
                <a
                    class="btn btn-primary"
                    href="{{ url('admin/courses').'/'.$course->id.'/enable' }}"
                    onclick="return confirm('Are you sure?')"
                >
                    Enable
                </a>
                @else
                <a
                    class="btn btn-danger"
                    href="{{ url('admin/courses').'/'.$course->id.'/disable' }}"
                    onclick="return confirm('Are you sure?')"
                >
                    Disable
                </a>
                @endif
                @endif
            </div>
            <div class="col-xxs-6 text-right">
                <a
                    class="btn btn-primary"
                    href="{{ url('course').'/'.$course->slug.'/preview' }}"
                >
                    Preview
                </a>
                <button type="submit" class="btn btn-primary">
                    Update Course
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <div class="modal fade" tabindex="-1" role="dialog" id="generateKeyModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::open(array('url' => url('admin/courses/'.$course->id.'/keys'), 'method' => 'post', 'class' => 'form-course-keys')) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Generate Keys</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group count">
                        {!! Form::label('url', 'Number of keys', array('class' => 'control-label shown')) !!}
                        <div class="controls">
                            {!! Form::select('count', [
                                '1' => '1',
                                '5' => '5',
                                '10' => '10',
                                '20' => '20',
                                '50' => '50'
                                ], '1', array('class' => 'form-control')
                            ) !!}
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group prefix">
                        {!! Form::label('url', 'Prefix', array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::text('prefix', null, array('class' => 'form-control', 'placeholder' => 'DEMO-, Free10-, etc')) !!}
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group tag">
                        {!! Form::label('url', 'Tag', array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::text('tag', null, array('class' => 'form-control', 'placeholder' => 'Optional tag')) !!}
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Generate</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="documentModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::open(array('id' => 'course-document-file-form', 'url' => url('admin/courses/'.$course->id.'/documents'), 'method' => 'post', 'class' => 'form-course-doc')) !!}
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Document Info</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group document">
                        <div><span class='label label-info' id="document-file-info"></span></div>
                        <label class="btn btn-sm btn-primary" for="course-document">
                            <input id="course-document" name="documents[]" type="file" multiple="multiple" value="Upload" style="display:none"
                            onchange="$('#document-file-info').html(getFileNames(this.files))">
                            Choose Files
                        </label>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                    <button
                        type="submit"
                        class="btn btn-sm btn-primary"
                        data-upload="s3"
                        data-upload-file="#course-document"
                        data-upload-dir="courses/{{ $course->id }}/documents"
                        data-upload-form="#course-document-file-form"
                    >
                        Save
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        $('.form-course input[type=checkbox][name=online_only]').change(function(e) {
            var courseForm = $(this).closest('.form-course');
            if (this.checked) {
                courseForm.find('#date_start').prop('disabled', true);
                courseForm.find('#date_end').prop('disabled', true);
            } else {
                courseForm.find('#date_start').prop('disabled', false);
                courseForm.find('#date_end').prop('disabled', false);
            }
        });
        $('.form-course input[type=checkbox][name=online_only]').trigger('change');

        $('form.form-course-keys').submit(function(event) {
            event.preventDefault();

            var $form = $(this);
            $form.find('.form-group').removeClass('has-error');
            $form.find('.form-group .help-block').text('');

            var formData = new FormData(this);
            var url = $form.attr('action');

             $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function (data) {
                    if (data.success) {
                        location.href = data.redirect;
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            }).fail(function(xhr) {
                var errors = xhr.responseJSON;
                $.each(Object.keys(errors), function(index, key) {
                    $form.find('.form-group.' + key).addClass('has-error');
                    $form.find('.form-group.' + key + ' .help-block').text(errors[key]);
                });
            });
        });

        $('form.form-course-doc').submit(function(event) {
            event.preventDefault();

            var $form = $(this);
            $form.find('.form-group').removeClass('has-error');
            $form.find('.form-group .help-block').text('');

            var formData = new FormData(this);
            var url = $form.attr('action');
            
             $.ajax({
                url: url,
                type: 'GET',
                data: formData,
                success: function (data) {
                    if (data.success) {
                        location.href = data.redirect;
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            }).fail(function(xhr) {
                var errors = xhr.responseJSON;
                $.each(Object.keys(errors), function(index, key) {
                    $form.find('.form-group.' + key).addClass('has-error');
                    $form.find('.form-group.' + key + ' .help-block').text(errors[key]);
                });
            });
        });

        $('form.form-course .instructors .instructor button').click(function(event) {
            event.preventDefault();

            var instructor_id = $(this).parent().data('instructor');
            var url = $(this).parent().data('url') + '?instructor_id=' + instructor_id;

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

        $('form.form-course .organizations .organization button').click(function(event) {
            event.preventDefault();

            var organization_id = $(this).parent().data('organization');
            var url = $(this).parent().data('url') + '?organization_id=' + organization_id;

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
