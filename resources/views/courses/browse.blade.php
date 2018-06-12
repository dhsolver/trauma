@extends('layouts.app')
@section('title'){{ $course->title }}@endsection
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
                <img class="img img-course" alt="course photo" src="{!! getS3Url($course->photo) !!}"/>
            </div>
            <div class="col-sm-6">
                <h1 class="page-title text-primary m-t-10">
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
                <div class="row">
                    <div class="col-xs-3">
                        <small>Certification:</small>
                    </div>
                    <div class="col-xs-9">
                        @if ($registration->completed_at)
                            @if ($registration->certified_at)
                                Certified by faculty
                            @else
                                Not certified by faculty
                            @endif
                        @else
                            Eligible after learning all modules
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="course-info">
            <h3 class="text-primary text-left">Course Modules</h3>
            <div class="row">
                <div class="col-sm-3">
                    @if (count($course->modules))
                    <ul class="nav nav-pills nav-stacked">
                        @foreach ($course->modules as $key=>$module)
                        <li role="presentation" @if ($key===0) class="active" @endif>
                            <a href="#module-{{ $module->id }}" aria-controls="module-{{ $module->id }}" role="tab" data-toggle="tab">
                                {{ $module->title }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @else
                        <p>No course module</p>
                    @endif
                </div>
                <div class="col-sm-9">
                    <div class="tab-content">
                        @foreach ($course->modules as $key=>$module)
                        <div role="tabpanel" class="tab-pane @if ($key===0) active @endif" id="module-{{ $module->id }}">
                            <p class="text-left">{!! nl2br(e($module->description)) !!}</p>
                            <ul class="course-module-documents text-left">
                            @foreach ($module->documents as $document)
                                <li>
                                    @if ($document->type === 'url')
                                    <a
                                        href="{{ $document->full_url }}"
                                        target="_blank"
                                        class="course-document text-break"
                                        data-document-id="{{ $document->id }}"
                                    >
                                    @else
                                    @if ($document->is_image)
                                    <a
                                        href="{{ url($document->full_url) }}"
                                        class="course-document html5lightbox text-break"
                                        title="{{ $document->filename }}"
                                        data-document-id="{{ $document->id }}"
                                    >
                                    @elseif ($document->is_video)
                                    <a
                                        href="{{ url($document->full_url) }}"
                                        class="course-document html5lightbox text-break"
                                        title="{{ $document->filename }}"
                                        data-document-id="{{ $document->id }}"
                                    >
                                    @elseif ($document->is_document)
                                    <a
                                        href="http://view.officeapps.live.com/op/view.aspx?src={{ $document->full_url }}"
                                        class="course-document html5lightbox text-break"
                                        title="{{ $document->filename }}"
                                        data-document-id="{{ $document->id }}"
                                    >
                                    @elseif ($document->is_pdf)
                                    <a
                                        href="{{ $document->full_url }}"
                                        class="course-document text-break"
                                        title="{{ $document->filename }}"
                                        target="_blank"
                                    >
                                    @else
                                    <a
                                        href="{{ $document->full_url }}"
                                        target="_blank"
                                        class="course-document text-break"
                                        data-document-id="{{ $document->id }}"
                                    >
                                    @endif
                                    @endif
                                        <i class="fa fa-check @if (!is_array($registration->progress) || !in_array($document->id, $registration->progress)) invisible @endif"></i>
                                        <i class="fa {{ $document->icon_class }}"></i>
                                        @if ($document->type === 'url')
                                        {{ $document->full_url }}
                                        @else
                                        {{ $document->filename }}
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                            </ul>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="course-complete text-center">
            @if (!empty($registration->completed_at))
            <h3 class="text-success">
                <i class="fa fa-check"></i> Course Completed
            </h3>
            @else
            <div class="notification hidden">
                <p>
                    You have completed all of the tasks within each module of this course. By clicking below, you are validating confirmation of course completion.
                </p>
                <a href="{{ url('course/'.$course->id.'/finish') }}" class="btn btn-primary m-b-5">
                    <i class="fa fa-check"></i> Yes, I've finished this course
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="/js/html5lightbox/html5lightbox.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $(function() {
        var courseUrl = "{{ url('course/'.$course->id) }}";
        $('.course-document').click(function(e) {
            var documentId = $(this).data('document-id');
            var trackUrl = courseUrl + '/module/documents/' + documentId + '/track';
            $(this).find('.fa-check').removeClass('invisible');
            $.get(trackUrl, function (data) {
                if (data.progress == data.total) {
                    $('.course-complete .notification').removeClass('hidden');
                }
            });
        });
    });
</script>
@endsection
