@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Edit Course Module @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Edit Course Module <small>/ <a href="{{ url('admin/courses/'.$course->id.'/edit') }}">{{ $course->title }}</a></small>
    </h2>

    @if (Session::has('message'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    @if (Session::has('courseModuleMessage'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('courseModuleMessage') }}
        </div>
    @endif

    {!! Form::model($courseModule, array('url' => url('admin/courses/'.$course->id.'/modules/'.$courseModule->id), 'method' => 'put', 'class' => 'form-course-module')) !!}
    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
        {!! Form::label('title', 'Title', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'Title *')) !!}
            <span class="help-block">{{ $errors->first('title', ':message') }}</span>
        </div>
    </div>

    <div class="documents">
        @if (count($courseModule->documents))
            @foreach ($courseModule->documents as $document)
            <div class="form-group" data-id="{{ $document->id }}" data-type="{{ $document->type }}" data-url="{{ $document->url }}" data-filename="{{ $document->filename }}" data-embedded="{{ $document->embedded }}">
                <div class="row">
                    <div class="col-xs-4 col-sm-2 text-right">
                        <a class="btn btn-xs btn-circle btn-primary btn-edit-doc">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a class="btn btn-xs btn-circle btn-danger" href="{{ url('admin/courses/'.$course->id.'/modules/'.$courseModule->id.'/documents/'.$document->id.'/delete') }}" onclick="return confirm('Are you sure?')">
                            <i class="fa fa-close"></i>
                        </a>
                    </div>
                    <div class="col-xs-8 col-sm-10">
                        @if ($document->type === 'url')
                            <a href="{{ $document->url }}" target="_blank" class="text-break">
                                <i class="fa fa-globe"></i> {{ $document->url }}
                            </a>
                        @else
                            <a href="{{ url('images/courses/'.$course->id.'/modules/'.$courseModule->id.'/'.$document->file) }}" target="_blank" class="text-break">
                                <i class="fa fa-file-o"></i> {{ $document->filename }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <strong>There are no URLs or documents added.</strong>
        @endif
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-10">
                <button type="button" class="btn btn-xs btn-primary btn-add-url" data-toggle="modal" data-target="#linkModal">
                    Add new URL
                </button>
                <button type="button" class="btn btn-xs btn-primary btn-add-doc" data-toggle="modal" data-target="#documentModal">
                    Upload document
                </button>
            </div>
        </div>
    </div>

    <hr>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-6">
                <a class="btn btn-danger" href="{!! url('admin/courses/'.$course->id.'/modules/'.$courseModule->id.'/delete') !!}" onclick="return confirm('Are you sure?')">
                    Delete
                </a>
            </div>
            <div class="col-xs-6 text-right">
                <button type="submit" class="btn btn-primary">
                    Save
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <div class="modal fade" tabindex="-1" role="dialog" id="linkModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::open(array('url' => url('admin/courses/'.$course->id.'/modules/'.$courseModule->id.'/documents'), 'method' => 'post', 'class' => 'form-course-module-doc')) !!}
                <input type="hidden" name="id" value="">
                <input type="hidden" name="type" value="url">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">URL Info</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group url">
                        {!! Form::label('url', 'URL', array('class' => 'control-label')) !!}
                        <div class="controls">
                            <input class="form-control" placeholder="URL" type="text" name="url" value="">
                            <span class="help-block">{{ $errors->first('title', ':message') }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="documentModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::open(array('url' => url('admin/courses/'.$course->id.'/modules/'.$courseModule->id.'/documents'), 'method' => 'post', 'files' => true, 'class' => 'form-course-module-doc')) !!}
                <input type="hidden" name="id" value="">
                <input type="hidden" name="type" value="file">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Document Info</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group document">
                        <div><span class='label label-info' id="upload-file-info"></span></div>
                        <label class="btn btn-sm btn-primary" for="module-document">
                            <input id="module-document" name="document" type="file" value="Upload" style="display:none"
                            onchange="$('#upload-file-info').html(this.files[0].name)">
                            Choose a File
                        </label>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="embedded" value="1"> Embedded
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        $('.form-course-module .documents .btn-edit-doc').click(function(event) {
            var $formGroup = $(this).closest('.form-group');
            var id = $formGroup.data('id');
            var type = $formGroup.data('type');

            if (type === 'url') {
                var $modal = $('#linkModal');
                var url = $formGroup.data('url');
                $modal.modal('show');
                $modal.find('input[name="id"]').val(id);
                $modal.find('input[name="url"]').val(url);
            } else {
                var $modal = $('#documentModal');
                $modal.modal('show');
                var filename= $formGroup.data('filename');
                var embedded= $formGroup.data('embedded');
                $modal.find('input[name="id"]').val(id);
                $modal.find('#upload-file-info').text(filename);
                $modal.find('input[name="embedded"]').prop('checked', embedded);
            }
        });

        $('.modal').on('show.bs.modal', function (event) {
            var $modal = $(this);
            $modal.find('.form-group').removeClass('has-error');
            $modal.find('.form-group .help-block').text('');
            $modal.find('form input[name="id"]').val('');
            $modal.find('form input[type="text"]').val('');
            $modal.find('form input[type="file"]').val('');
            $modal.find('form input[type="checkbox"]').prop('checked', false);
            $modal.find('#upload-file-info').text('');
        });

        $('form.form-course-module-doc').submit(function(event) {
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
