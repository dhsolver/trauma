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
            <h3 class="text-primary">Course Modules</h3>
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
                            <ul class="course-module-documents text-left">
                            @foreach ($module->documents as $document)
                                <li>
                                @if ($document->type === 'url')
                                    <a
                                        href="{{ $document->full_url }}"
                                        target="_blank"
                                        class="text-break"
                                    >
                                        <i class="fa {{ $document->icon_class }}"></i> {{ $document->url }}
                                    </a>
                                @else
                                    @if ($document->is_image)
                                    <a
                                        href="{{ url($document->full_url) }}"
                                        class="html5lightbox"
                                        title="{{ $document->filename }}"
                                    >
                                        <i class="fa {{ $document->icon_class }}"></i> {{ $document->filename }}
                                    </a>
                                    @elseif ($document->is_video)
                                    <a
                                        href="{{ url($document->full_url) }}"
                                        class="html5lightbox"
                                        title="{{ $document->filename }}"
                                    >
                                        <i class="fa {{ $document->icon_class }}"></i> {{ $document->filename }}
                                    </a>
                                    @elseif ($document->is_document)
                                    <a
                                        href="https://docs.google.com/gview?url={{ $document->full_url }}&embedded=true"
                                        class="html5lightbox"
                                        title="{{ $document->filename }}"
                                    >
                                        <i class="fa {{ $document->icon_class }}"></i> {{ $document->filename }}
                                    </a>
                                    @else
                                    <a
                                        href="{{ $document->full_url }}"
                                        target="_blank"
                                        class="text-break"
                                    >
                                        <i class="fa {{ $document->icon_class }}"></i> {{ $document->filename }}
                                    </a>
                                    @endif
                                @endif
                                </li>
                            @endforeach
                            </ul>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
@endsection

@section('scripts')
<script src="/js/html5lightbox/html5lightbox.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
</script>
@endsection
