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
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#modules" aria-controls="modules" role="tab" data-toggle="tab">Course Modules</a></li>
                <li role="presentation"><a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Discussion</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="modules">
                    <div class="row">
                        <div class="col-sm-3">
                            @if (count($course->modules))
                            <ul class="nav nav-pills nav-stacked">
                                @foreach ($course->modules as $key=>$module)
                                @if ($module->is_visible)
                                <li role="presentation" @if ($key===0) class="active" @endif>
                                    <a href="#module-{{ $module->id }}" aria-controls="module-{{ $module->id }}" role="tab" data-toggle="tab">
                                        {{ $module->title }}
                                    </a>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                            @else
                                <p>No course module</p>
                            @endif
                        </div>
                        <div class="col-sm-9">
                            <div class="tab-content">
                                <?php $active = true ?>
                                @foreach ($course->modules as $key=>$module)
                                @if ($module->is_visible)
                                <div role="tabpanel" class="tab-pane @if ($active) active @endif" id="module-{{ $module->id }}">
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
                                            @elseif ($document->is_document || $document->is_pdf)
                                            <a
                                                href="https://docs.google.com/gview?url={{ $document->full_url }}&embedded=true"
                                                class="course-document html5lightbox text-break"
                                                title="{{ $document->filename }}"
                                                data-document-id="{{ $document->id }}"
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
                                            @if ($document->type == 'file' && ($document->is_document || $document->is_pdf))
                                            <a
                                                target="_blank"
                                                href="{{ $document->full_url }}"
                                                class="course-document text-break m-l-15"
                                                data-document-id="{{ $document->id }}"
                                            >
                                                <i class="fa fa-download"></i>
                                            </a>
                                            @endif
                                        </li>
                                    @endforeach
                                    </ul>
                                </div>
                                <?php $active = false ?>
                                @endif
                                @endforeach
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
                <div role="tabpanel" class="tab-pane" id="comments">
                    @foreach ($course->comments as $key=>$comment)
                    <article data-id="{{ $comment->id }}">
                        <div class="avatar">
                            <img class="img img-avatar img-circle" alt="user avatar" src="{!! getS3Url($comment->user->avatar) !!}"/>
                        </div>
                        <div class="comment">
                            <div class="text">
                                <span class="author">{{ $comment->user->first_name }} {{ $comment->user->last_name }}: </span>
                                {{ $comment->text }}
                            </div>
                            <div class="meta">
                                <span class="timestamp">{{ $comment->created_at }}</span>
                                @if ($comment->attachment)
                                    <a href="{!! getS3Url($comment->attachment) !!}" class="attachment"><i class="fa fa-paperclip"></i> {{ $comment->attachment_filename }}</a>
                                @endif
                            </div>
                            @foreach ($comment->comments as $key => $reply)
                            <article>
                                <div class="avatar">
                                    <img class="img img-avatar img-circle" alt="user avatar" src="{!! getS3Url($reply->user->avatar) !!}"/>
                                </div>
                                <div class="comment">
                                    <div class="text">
                                        <span class="author">{{ $reply->user->first_name }} {{ $reply->user->last_name }}: </span>
                                        {{ $reply->text }}
                                    </div>
                                    <div class="meta">
                                        <span class="timestamp">{{ $reply->created_at }}</span>
                                        @if ($reply->attachment)
                                            <a href="{!! getS3Url($reply->attachment) !!}" class="attachment"><i class="fa fa-paperclip"></i> {{ $reply->attachment_filename }}</a>
                                        @endif
                                    </div>
                                </div>
                            </article>
                            @endforeach
                            <div class="comment-reply-container hidden">
                                <div class="comment-reply">
                                    <div class="row">
                                        <div class="col-md-9">
                                            {!! Form::open(array('id' => 'comment-form-' . $comment->id, 'url' => url('course/'.$course->id.'/comments'), 'method' => 'post', 'class' => 'form-comment')) !!}
                                            <input type="hidden" name="parent_id" value="{{$comment->id}}"></input>
                                            <textarea rows="2" class="form-control" name="comment" placeholder="Write a reply"></textarea>
                                            <div class="m-b-5 hidden" id="comment-file-wrapper-{{$comment->id}}">
                                                <span class='label label-info' id="comment-file-info-{{$comment->id}}"></span>
                                                <button type="button" class="btn btn-sm btn-default btn-remove">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                            <div class="has-error">
                                                <span class="help-block"></span>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <label class="btn btn-default" for="comment-file-{{$comment->id}}">
                                                <input
                                                    id="comment-file-{{$comment->id}}"
                                                    name="comment-file-{{$comment->id}}"
                                                    type="file"
                                                    value="Upload"
                                                    style="display:none"
                                                    accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf, application/zip,application/x-zip,application/x-zip-compressed, image/*"
                                                    onchange="$('#comment-file-info-{{$comment->id}}').html(this.files[0].name); $('#comment-file-wrapper-{{$comment->id}}').removeClass('hidden');"
                                                >
                                                <i class="fa fa-paperclip"></i>
                                            </label>

                                            <button
                                                type="submit"
                                                class="btn btn-default btn-send"
                                                data-upload="s3"
                                                data-upload-file="#comment-file-{{$comment->id}}"
                                                data-upload-dir="courses/{{ $course->id }}/comments"
                                                data-upload-form="#comment-form-{{$comment->id}}"
                                                data-uploading-text="..."
                                            >
                                                <i class="fa fa-paper-plane-o"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="action">
                            <button type="button" class="btn btn-default btn-reply">
                                <i class="fa fa-reply"></i>
                            </button>
                        </div>
                    </article>
                    @endforeach

                    <div class="comment-reply-container">
                        <div class="comment-reply">
                            <div class="row">
                                <div class="col-md-9">
                                    {!! Form::open(array('id' => 'comment-form', 'url' => url('course/'.$course->id.'/comments'), 'method' => 'post', 'class' => 'form-comment')) !!}
                                    <textarea rows="2" class="form-control" name="comment" placeholder="Write a reply"></textarea>
                                    <div class="m-b-5 hidden" id="comment-file-wrapper">
                                        <span class='label label-info' id="comment-file-info"></span>
                                        <button type="button" class="btn btn-sm btn-default btn-remove">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="has-error">
                                        <span class="help-block"></span>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                                <div class="col-md-3 text-right">
                                    <label class="btn btn-default" for="comment-file">
                                        <input
                                            id="comment-file"
                                            name="comment-file"
                                            type="file"
                                            value="Upload"
                                            style="display:none"
                                            accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf, application/zip,application/x-zip,application/x-zip-compressed, image/*"
                                            onchange="$('#comment-file-info').html(this.files[0].name); $('#comment-file-wrapper').removeClass('hidden');"
                                        >
                                        <i class="fa fa-paperclip"></i>
                                    </label>

                                    <button
                                        type="submit"
                                        class="btn btn-default btn-send"
                                        data-upload="s3"
                                        data-upload-file="#comment-file"
                                        data-upload-dir="courses/{{ $course->id }}/comments"
                                        data-upload-form="#comment-form"
                                        data-uploading-text="..."
                                    >
                                        <i class="fa fa-paper-plane-o"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="/js/html5lightbox/html5lightbox.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $(function() {
        if (location.hash === '#comments') {
            $('a[href="#comments"]').tab('show');
        }

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

        $('.btn-remove').click(function(e) {
            $(this).parents('form').find('input[name="fileKeys[]"]').remove();
            $(this).parent().addClass('hidden');
        });

        $('.btn-reply').click(function(e) {
            $(this).parents('article').find('.comment-reply-container').toggleClass('hidden');
        });

        $('.form-comment').submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            $form.find('.help-block').text('').hide();

            if ($form.find('textarea[name=comment]').val() === '') {
                $form.find('.help-block').text('Please enter the comment.').show();
                return;
            }

            var formData = new FormData(this);
            var url = $form.attr('action');
             $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function (data) {
                    if (data.success) {
                        location.href = data.redirect;
                        location.reload(true);
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            }).fail(function(xhr) {
                var errors = xhr.responseJSON;
                $form.find('.help-block').text(errors[0][key]);
            });
        });
    });
</script>
@endsection
