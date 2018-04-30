@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Courses @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Courses
        <div class="pull-right">
            <a href="{!! url('admin/courses/create') !!}" class="btn btn-sm btn-primary">Create New Course</a>
        </div>
    </h2>

    @if (Session::has('courseMessage'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('courseMessage') }}
        </div>
    @endif
    @if (Session::has('message'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="table-responsive table-container">
        <table class="table table-hover">
            @foreach ($courses as $course)
            <tr>
                <td><a href="{!! url('admin/courses/'.$course->id.'/edit') !!}">{{ $course->title }}</a></td>
                <td>{{ $course->online_only ? 'Online' : $course->date_start . '-' . $course->date_end }}</td>
                <td>{{ $course->location }}</td>
                <td>John Doe, Alex Smith</td>
                <td class="text-right">
                    <a href="{!! url('admin/courses/'.$course->id.'/edit') !!}" class="btn btn-primary">Edit</a>
                </td>
            </tr>
            @endforeach
        </table>
        @if (!count($courses))
        <h4>No courses found.</h4>
        @endif
    </div>
@endsection
