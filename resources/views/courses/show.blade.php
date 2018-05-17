@extends('layouts.app')
@section('title'){{ $course->title }} @endsection
@section('content')
<div class="page-course">
    <div class="content-box">
        @if (Session::has('courseMessage'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('courseMessage') }}
            </div>
        @endif
        <div class="row">
            <div class="col-sm-6 text-center">
                @if ($course->photo)
                    <img class="img img-course" alt="{{$course->photo}}" src="{!! url('images/courses/'.$course->id.'/'.$course->photo) !!}"/>
                @else
                    <img class="img img-course" alt="no avatar" src="{!! url('images/no_photo.png') !!}"/>
                @endif
            </div>
            <div class="col-sm-6">
                <h1 class="page-title text-primary">
                    <a href="{{ url('course/'.$course->slug) }}">{{ $course->title }}</a>
                </h1>
                <div class="row">
                    <div class="col-xs-3">
                        <small>Course Id:</small>
                    </div>
                    <div class="col-xs-9">
                        #{{ $course->id }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                        <small>Instructors:</small>
                    </div>
                    <div class="col-xs-9">
                        @if (is_array($course->instructors) && count($course->instructors))
                            @for ($i=0; $i<count($course->instructors); $i++)
                                <?php $faculty = $faculties[$course->instructors[$i]]; ?>
                                <span>{{ $faculty['first_name'] }} {{ $faculty['last_name'] }}</span>@if ($i<count($course->instructors)-1), @endif
                            @endfor
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-3">
                        <small>Date:</small>
                    </div>
                    <div class="col-xs-9">
                        {{ $course->online_only ? 'Online' : $course->date_start . ' - ' . $course->date_end }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                        <small>Location:</small>
                    </div>
                    <div class="col-xs-9">
                        {{ $course->location }}
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="course-info">
            <label>Overview</label>
            <p>{!! $course->overview !!}</p>
        </div>

        <hr>
        <div class="course-info">
            <label>Learning Objective</label>
            <p>{!! $course->objective !!}</p>
        </div>

        <hr>
        <div class="course-info">
            <label>Pre-Requisites</label>
            <p>{!! $course->prerequisites !!}</p>
        </div>

        <hr>
        <div class="course-info">
            <label>Textbook or Additional Resources</label>
            <p>{!! $course->resources !!}</p>
        </div>

        <hr>
        <div class="course-info">
            <label>Continuing Education</label>
            <p>{!! $course->continuing_education !!}</p>
        </div>

        <hr>
        <div class="course-info">
            <label>Course Modules</label>
            @if (count($course->modules))
                <div>
                <ul class="course-modules">
                @foreach ($course->modules as $module)
                    <li>{{ $module->title }}</li>
                @endforeach
                </ul>
                </div>
            @else
                <p>No course module</p>
            @endif
        </div>

        <hr>
        <div class="course-info course-register">
            <!-- <label>Register</label> -->
            @if (empty($registration))
                @if (Auth::guest())
                    <p class="text-info">You need to login to register for this course</p>
                    <a href="{{ url('auth/login') }}" class="btn btn-primary">
                        Login
                    </a>
                @else
                    <a href="#" class="btn btn-primary m-b-5" data-toggle="modal" data-target="#registerWithKeyModal">
                        <i class="fa fa-key"></i> Register with key
                    </a>
                    <a href="#" class="btn btn-primary m-b-5">
                        <i class="fa fa-paypal"></i> Buy with Paypal
                    </a>
                @endif
            @else
                <p class="text-info">You have already registered for this course.</p>
                <a href="{{ url('course/'.$course->slug.'/browse') }}" class="btn btn-primary m-b-5">
                    <i class="fa fa-search"></i> Browse Course Modules
                </a>
            @endif
        </div>

    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="registerWithKeyModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! Form::open(array('url' => url('course/'.$course->id.'/register'), 'method' => 'post', 'class' => 'form-register-key')) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Enter your key</h4>
            </div>
            <div class="modal-body">
                <div class="form-group key">
                    {!! Form::label('url', 'Key', array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('key', null, array('class' => 'form-control')) !!}
                        <span class="help-block"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        $('.form-register-key').submit(function(event) {
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
    });
</script>
@endsection
