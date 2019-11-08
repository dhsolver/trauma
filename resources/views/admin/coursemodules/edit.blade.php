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
    <input type="hidden" name="addnew" value="0">
    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
        {!! Form::label('title', 'Title', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'Title *')) !!}
            <span class="help-block">{{ $errors->first('title', ':message') }}</span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
        {!! Form::label('description', 'Description', array('class' => 'control-label shown')) !!}
        <div class="controls">
            {!! Form::textarea('description', $courseModule->description, array('class' => 'form-control', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('description', ':message') }}</span>
        </div>
    </div>


    <div class="documents">
        @if (count($courseModule->documents))
            @foreach ($courseModule->documents as $document)
            <div class="form-group" data-id="{{ $document->id }}" data-type="{{ $document->type }}" data-url="{{ $document->url }}" data-display_name="{{ $document->display_name }}" data-filename="{{ $document->filename }}" data-embedded="{{ $document->embedded }}">
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
                                @if (!empty($document->display_name))
                                    <i class="fa fa-globe"></i> {{ $document->display_name }}
                                @else
                                    <i class="fa fa-globe"></i> {{ $document->url }}
                                @endif
                            </a>
                        @else
                            <a href="{{ getS3Url($document->file) }}" target="_blank" class="text-break">
                                @if (!empty($document->display_name))
                                    <i class="fa fa-file-o"></i> {{ $document->display_name }}
                                @else
                                    <i class="fa fa-file-o"></i> {{ $document->filename }}
                                @endif
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
                <button type="button" class="btn btn-primary" id="btn-save-and-new">
                    Save and New
                </button>
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
                {!! Form::open(array('id' => 'course-module-document-url-form', 'url' => url('admin/courses/'.$course->id.'/modules/'.$courseModule->id.'/documents'), 'method' => 'post', 'class' => 'form-course-module-doc')) !!}
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
                    <div class="form-group display-name">
                        {!! Form::label('display_name', 'Display Name', array('class' => 'control-label')) !!}
                        <div class="controls">
                            <input class="form-control" placeholder="Display Name" type="text" name="displayName" value="">
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
                {!! Form::open(array('id' => 'course-module-document-file-form', 'url' => url('admin/courses/'.$course->id.'/modules/'.$courseModule->id.'/documents'), 'method' => 'post', 'class' => 'form-course-module-doc')) !!}
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="type" value="file">
                    <input type="hidden" name="embedded" value="0">
                    <!-- <input type="hidden" name="fileKeys[]" value="" /> -->
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Document Info</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group document">
                        <div><span class='label label-info' id="upload-file-info"></span></div>
                        <label class="btn btn-sm btn-primary" for="module-document">
                            <input id="module-document" name="documents[]" type="file" value="Upload" style="display:none"
                            onchange="$('#upload-file-info').html(getFileNames(this.files))">
                            Choose a File
                        </label>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group document-display-name">
                        {!! Form::label('document-display-name', 'Display Name', array('class' => 'control-label')) !!}
                        <div class="controls">
                            <input class="form-control" placeholder="Display Name" type="text" name="displayName" value="">
                            <span class="help-block">{{ $errors->first('title', ':message') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input id="documentEmbedded" type="checkbox" name="embedded"> Embedded
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                    <button
                        id="btn-save-document-file"
                        type="submit"
                        class="btn btn-sm btn-primary"
                        data-upload="s3"
                        data-upload-file="#module-document"
                        data-upload-dir="courses/{{ $course->id }}/modules/{{ $courseModule->id }}/documents"
                        data-upload-form="#course-module-document-file-form"
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
        $('.form-course-module .documents .btn-edit-doc').click(function(event) {
            var $formGroup = $(this).closest('.form-group');
            var id = $formGroup.data('id');
            var type = $formGroup.data('type');

            if (type === 'url') {
                var $modal = $('#linkModal');
                var url = $formGroup.data('url');
                var displayName = $formGroup.data('display_name');
                $modal.modal('show');
                $modal.find('input[name="id"]').val(id);
                $modal.find('input[name="url"]').val(url);
                $modal.find('input[name="displayName"]').val(displayName);
            } else {
                var $modal = $('#documentModal');
                $modal.modal('show');
                var filename= $formGroup.data('filename');
                var embedded= $formGroup.data('embedded');
                var displayName = $formGroup.data('display_name');
                $modal.find('input[name="id"]').val(id);
                $modal.find('input[type="file"]').removeAttr('multiple');
                $modal.find('#upload-file-info').text(filename);
                $modal.find('input[name="embedded"]').prop('checked', embedded);
                $modal.find('input[name="displayName"]').val(displayName);
            }
        });

        $('.modal').on('show.bs.modal', function (event) {
            var $modal = $(this);
            $modal.find('.form-group').removeClass('has-error');
            $modal.find('.form-group .help-block').text('');
            $modal.find('form input[name="id"]').val('');
            $modal.find('form input[type="text"]').val('');
            $modal.find('form input[type="file"]').val('');
            $modal.find('input[type="file"]').attr('multiple', 'multiple');
            $modal.find('form input[type="checkbox"]').prop('checked', false);
            $modal.find('#upload-file-info').text('');
        });

        $('#documentEmbedded').on('change', function (event) {
            $('#course-module-document-file-form input[name="embedded"]').val($(this).prop('checked') ? '1' : '0');
        })

        $('#btn-save-document-file').click(function(e) {
            if ($('#course-module-document-file-form input[name="id"]').val() > 0 &&
                $('#module-document').prop('files').length === 0
            ) {
                $('form.form-course-module-doc').submit();
            }
        });

        $('form.form-course-module-doc').unbind().bind('submit', function(event) {
            event.preventDefault();

            var $form = $(this);
            if ($('#module-document').prop('files').length > 0 || $form.attr('id') == 'course-module-document-url-form') {
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
            }
            else {
                var display_name = $form.find('input[name="displayName"]').val();
                var document_id = $form.find('input[name="id"]').val();
                if (document_id == '' || document_id == undefined) {
                    $form.find('.form-group.document').addClass('has-error');
                    $form.find('.form-group.document .help-block').text('Please upload file');
                }
                else {
                    var url = $form.attr('action') + '/' + document_id + '/update?display_name=' + display_name;
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function (data) {
                            if (data.success) {
                                location.href = data.redirect;
                            }
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                }
            }
        });

        $('form.form-course-module #btn-save-and-new').click(function(event) {
            $('form.form-course-module input[name="addnew"]').val('1');
            $('form.form-course-module').submit();
        });
    });
</script>
@endsection
