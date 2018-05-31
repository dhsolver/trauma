@extends('layouts.app')
@section('title')Course Catalog @endsection
@section('content')
<div class="page-courses">
    <div class="content-box">
        <h1 class="section-title text-primary">Latest Courses</h1>
        @if (count($latestCourses) > 0)
        @foreach ($latestCourses as $index => $course)
            @if ($index%3 === 0)
            <div class="row equal-height m-b-10">
            @endif
                <div class="col-sm-4">
                    <div class="course text-center">
                        <a href="{!! url('course/'.$course->slug) !!}">
                            <img class="img img-course" alt="course photo" src="{!! getS3Url($course->photo) !!}"/>
                        </a>
                        <div class="course__info text-center m-t-10">
                            <h4 class="course__title">
                                <a href="{!! url('course/'.$course->slug) !!}">{{ $course->title }}</a>
                            </h4>
                            <hr>
                            <div class="course__instructors">
                                @if (is_array($course->instructors) && count($course->instructors))
                                    <small>Instructors:</small>
                                    @foreach ($course->instructors as $faculty_id)
                                        <?php $faculty = $faculties[$faculty_id]; ?>
                                        <div>{{ $faculty['first_name'] }} {{ $faculty['last_name'] }}</div>
                                    @endforeach
                                @endif
                            </div>
                            <hr>
                            <div class="course__date">
                                {{ $course->online_only ? 'Online' : $course->date_start . ' - ' . $course->date_end }}
                            </div>
                            <hr>
                            <div class="course__location">
                                {{ $course->location }}
                            </div>
                            @if ($course->purchase_enabled && $course->price > 0)
                            <hr>
                            <div class="course__location text-primary">
                                <strong>${{ $course->price }}</strong>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @if ($index%3 === 2)
            </div>
            @endif
        @endforeach
        @else
        <h4>No latest courses.</h4>
        @endif
    </div>
</div>
@endsection
