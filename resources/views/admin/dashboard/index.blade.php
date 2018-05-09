@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Dashboard @endsection

{{-- Content --}}
@section('main')

    <h3 class="section-title">Latest Courses</h3>
    @if (count($latestCourses) > 0)
    <div class="row equal-height">
        @foreach ($latestCourses as $course)
        <div class="col-sm-4">
            <div class="course text-center">
                @if ($course->photo)
                    <a href="{!! url('admin/courses/'.$course->id.'/edit') !!}"><img class="img img-course" alt="{{$course->photo}}" src="{!! url('images/courses/'.$course->id.'/'.$course->photo) !!}"/></a>
                @else
                    <a href="{!! url('admin/courses/'.$course->id.'/edit') !!}"><img class="img img-course" alt="no avatar" src="{!! url('images/no_photo.png') !!}"/></a>
                @endif
                <div class="course__info text-center m-t-10">
                    <div class="course__title"><a href="{!! url('admin/courses/'.$course->id.'/edit') !!}">{{ $course->title }}</a></div>
                    <div class="course__location">{{ $course->location }}</div>
                    <div class="course__date">{{ $course->online_only ? 'Online' : $course->date_start . ' - ' . $course->date_end }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <h4>No latest courses.</h4>
    @endif

    @if (Auth::user()->role === 'admin')
    <h3 class="section-title m-t-30">Pending Users</h3>

    @if (Session::has('userMessage'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('userMessage') }}
        </div>
    @endif

    <div class="table-responsive table-container">
        <table class="table table-hover">
            @foreach ($pendingUsers as $user)
            <tr>
                <td><a href="{!! url('admin/users/'.$user->id.'/edit') !!}">{{ $user->first_name }} {{ $user->last_name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->birthday }}</td>
                <td class="text-right">
                    <a href="{!! url('admin/users/'.$user->id.'/approve') !!}" class="btn btn-success" onclick="return confirm('Are you sure to approve this user?')">Approve</a>
                    <a href="{!! url('admin/users/'.$user->id.'/deny') !!}" class="btn btn-danger" onclick="return confirm('Are you sure to deny this user?')">Deny</a>
                </td>
            </tr>
            @endforeach
        </table>
        @if (!count($pendingUsers))
        <h4>No pending users.</h4>
        @endif
    </div>
    @endif
@endsection
