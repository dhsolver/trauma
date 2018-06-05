@extends('layouts.app')
@section('title')My Courses @endsection
@section('content')
<div class="page-courses">
    <div class="content-box">
        <h1 class="section-title text-primary">My Courses</h1>
        <?php $index = 0 ?>
        @foreach ($registrations as $registration)
            <?php $course = $registration->course ?>
            <?php if (!$course->enabled) continue?>
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
                            <hr>
                            <div>
                                @if ($registration->completed_at)
                                <label class="label label-lg label-success">Completed on {{ $registration->completed_at }}</label>
                                @else
                                <label class="label label-default">In Progress</label>
                                @endif
                            </div>
                            @if ($registration->completed_at)
                            <hr>
                            <div>
                                @if ($registration->certified_at)
                                <label class="label label-lg label-success">Certified on {{ $registration->certified_at }}</label>
                                @else
                                <label class="label label-default">Not certified</label>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            <?php $index++ ?>
            @if ($index%3 === 0)
            </div>
            @endif
        @endforeach
        @if ($index === 0)
        <h4>No registered courses.</h4>
        @endif
    </div>
</div>
@endsection
