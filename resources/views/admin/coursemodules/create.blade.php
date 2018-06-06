@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')New Course Module @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        New Course Module <small>/ <a href="{{ url('admin/courses/'.$course->id.'/edit') }}">{{ $course->title }}</a></small>
    </h2>

    @if (Session::has('message'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    {!! Form::open(array('url' => url('admin/courses/'.$course->id.'/modules'), 'method' => 'post', 'class' => 'form-course-module')) !!}
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
            {!! Form::textarea('description', null, array('class' => 'form-control', 'rows' => '3')) !!}
            <span class="help-block">{{ $errors->first('description', ':message') }}</span>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-sm-12 text-right">
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
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        $('form.form-course-module #btn-save-and-new').click(function(event) {
            $('form.form-course-module input[name="addnew"]').val('1');
            $('form.form-course-module').submit();
        });
    });
</script>
@endsection
