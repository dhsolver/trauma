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

        @if (Session::has('courseError'))
            <div class="alert alert-danger" role="alert">
                {{ Session::get('courseError') }}
            </div>
        @endif

        <div class="row">
            <div class="col-sm-6 text-center">
                <img class="img img-course" alt="course photo" src="{!! getS3Url($course->photo) !!}"/>
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
                @if ($course->purchase_enabled && $course->price > 0)
                <div class="row">
                    <div class="col-xs-3">
                        <small>Price:</small>
                    </div>
                    <div class="col-xs-9 text-primary">
                        <strong>${{ $course->price }}</strong>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <hr>
        <div class="course-info">
            <label>Overview</label>
            <p>{!! nl2br($course->overview) !!}</p>
        </div>

        <hr>
        <div class="course-info">
            <label>Instructor Note</label>
            <p>{!! nl2br($course->instructor_note) !!}</p>
        </div>

        <hr>
        <div class="course-info">
            <label>Learning Objective</label>
            <p>{!! nl2br($course->objective) !!}</p>
        </div>

        <hr>
        <div class="course-info">
            <label>Pre-Requisites</label>
            <p>{!! nl2br($course->prerequisites) !!}</p>
        </div>

        <hr>
        <div class="course-info">
            <label>Textbook or Additional Resources</label>
            <p>{!! nl2br($course->resources) !!}</p>
        </div>

        <hr>
        <div class="course-info">
            <label>Continuing Education</label>
            <p>{!! nl2br($course->continuing_education) !!}</p>
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
        <div class="course-info course-register text-center">
            @if (!empty($registration))
                @if ($registration->payment_status === 'Completed')
                    <p class="text-info">
                        <i class="fa fa-check"></i> You have registered for this course.
                    </p>
                    @if ($registration->completed_at)
                    <p class="text-info">
                        <i class="fa fa-check"></i> You have finished the course.
                    </p>
                    @endif
                    <a href="{{ url('course/'.$course->slug.'/browse') }}" class="btn btn-primary m-b-5">
                        <i class="fa fa-search"></i> Browse Course Modules
                    </a>
                @else
                    <p class="text-warning">
                        <i class="fa fa-check-circle-o"></i> Your purchase is under verification.
                    </p>
                @endif
            @else
                @if (Auth::guest())
                    <p class="text-info">You need to login to register for this course</p>
                    <a href="{{ url('auth/login') }}" class="btn btn-primary">
                        Login
                    </a>
                @else
                    <a href="#" class="btn btn-primary m-b-5" data-toggle="modal" data-target="#registerWithKeyModal">
                        <i class="fa fa-key"></i> Register with key
                    </a>
                    @if ($course->purchase_enabled > 0 && $course->price > 0)
                    <a href="#" class="btn btn-primary m-b-5" data-toggle="modal" data-target="#buyWithPaypalModal">
                        <i class="fa fa-paypal"></i> Buy with Paypal
                    </a>
                    @endif
                @endif
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


<div class="modal fade" tabindex="-1" role="dialog" id="buyWithPaypalModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ env('PAYPAL_CHECKOUT_URL', 'https://www.paypal.com/cgi-bin/webscr') }}" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Buy with Paypal</h4>
                </div>

                <div class="modal-body">
                    Price: ${{ $course->price }}
                </div>

                <input type="hidden" name="business" value="{{ env('PAYPAL_MERCHANT_EMAIL') }}">

                <!-- Specify a Buy Now button. -->
                <input type="hidden" name="cmd" value="_xclick">

                <!-- Specify details about the item that buyers will purchase. -->
                <input type="hidden" name="item_name" value="{{ $course->title }}">
                <input type="hidden" name="amount" value="{{ $course->price }}">
                <input type="hidden" name="currency_code" value="USD">

                <input type="hidden" name="no_shipping" value="1">
                <input type="hidden" name="return" value="{{ url('cb/paypal/'.$course->slug) }}">
                <input type="hidden" name="cancel_return" value="{{ url('course/'.$course->slug) }}">
                <input type="hidden" name="rm" value="2">
                <input type="hidden" name="custom" value="{{ $user->id }}-{{ $course->id }}">

                <input type="hidden" name="notify_url" value="{{ env('PAYPAL_IPN_URL', url('ipn/paypal')) }}">

                <!-- <input type="image" name="submit" border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" alt="Buy Now" /> -->
                <!-- <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" /> -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Buy Now</button>
                </div>
            </form>
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
