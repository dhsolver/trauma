@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Editing page - {{ $staticPage->title }} @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Edit Page
    </h2>

    @if (Session::has('pageMessage'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('pageMessage') }}
        </div>
    @endif
    @if (Session::has('message'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    @if (!empty($staticPage->image))
    <div class="form-group {{ $errors->has('fileKeys') ? 'has-error' : '' }}">
        <div class="row">
            <div class="col-xs-offset-2 col-xs-8 col-sm-offset-3 col-sm-6 text-center">
                <img class="img" alt="static page image" src="{!! url('images/'.$staticPage->image) !!}"/>
                <!-- <div class="m-b-5"><span class='label label-info' id="upload-file-info"></span></div> -->
                <!-- <label class="btn btn-sm btn-primary" for="static-page-image">
                    <input id="static-page-image" name="image" type="file" value="Upload" style="display:none"
                    onchange="$('#upload-file-info').html(this.files[0].name);">
                    Choose Image
                </label> -->
                <!-- <span class="help-block">{{ $errors->first('fileKeys', ':message') }}</span> -->
            </div>
        </div>
    </div>
    @endif

    {!! Form::model($staticPage, array('id' => 'form-static-page', 'url' => url('admin/staticpages/'.$staticPage->id), 'method' => 'put', 'class' => 'form-static-page')) !!}
    <!-- <input type="hidden" name="fileKeys[]" value="" /> -->

    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
        {!! Form::label('title', 'Title', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('title', $staticPage->title, array('class' => 'form-control', 'placeholder' => 'Title *')) !!}
            <span class="help-block">{{ $errors->first('title', ':message') }}</span>
        </div>
    </div>

    <div class="form-group {{ $errors->has('subtitle') ? 'has-error' : '' }}">
        {!! Form::label('subtitle', 'Subtitle', array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('subtitle', $staticPage->subtitle, array('class' => 'form-control', 'placeholder' => 'Subtitle')) !!}
            <span class="help-block">{{ $errors->first('subtitle', ':message') }}</span>
        </div>
    </div>

    <div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
        {!! Form::label('text', 'Overview', array('class' => 'control-label shown')) !!}
        <div class="controls">
            {!! Form::textarea('text', $staticPage->text, array('class' => 'form-control', 'rows' => '10')) !!}
            <span class="help-block">{{ $errors->first('text', ':message') }}</span>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-xxs-6">
            </div>
            <div class="col-xxs-6 text-right">
                <button
                    type="submit"
                    class="btn btn-primary"
                    id="btn-save-static-page"
                    type="submit"
                    class="btn btn-sm btn-primary"
                >
                    Save
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('scripts')
<script type="text/javascript">
    /*
    $(function() {
        data-upload="s3"
        data-upload-file="#static-page-image"
        data-upload-dir="pages/{{ $staticPage->id }}"
        data-upload-form="#form-static-page"

        $('#btn-save-static-page').click(function(e) {
            if ($('#static-page-image').prop('files').length === 0) {
                $('#form-static-page').submit();
            }
        });
    });
    */
</script>
@endsection
