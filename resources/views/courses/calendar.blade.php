@extends('layouts.app')
@section('title')Calendar @endsection
@section('content')
<div class="page-courses">
    <div class="content-box">
        <h1 class="section-title text-primary">Calendar</h1>
        <div class="row">
            <div class="col-sm-4">
                <div id="datepicker" data-date="{{ $dt }}"></div>
            </div>
            <div class="col-sm-8">
                @if (count($latestCourses) > 0)
                <h4>Found {{ count($latestCourses) }} courses</h4>
                @foreach ($latestCourses as $index => $course)
                <div class="course m-b-10 p-10">
                    <div class="course__info">
                        <h4 class="course__title">
                            <a href="{!! url('course/'.$course->slug) !!}">{{ $course->title }}</a>
                        </h4>
                        <div class="course__date">
                            {{ $course->online_only ? 'Online' : $course->date_start . ' - ' . $course->date_end }}
                        </div>
                        <div class="course__location">
                            {{ $course->location }}
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <h4>No available course on this date.</h4>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        $('#datepicker').datepicker({
            format: "yyyy-mm-dd",
        });

        $('#datepicker').on('changeDate', function() {
            var dt = $('#datepicker').datepicker('getFormattedDate');
            location.href = '/calendar?dt=' + dt;
        });
    });
</script>
@endsection
