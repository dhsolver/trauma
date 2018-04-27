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
    <div class="table-responsive table-container">
        <table class="table table-hover">
            @foreach ($courses as $course)
            <tr>
                <td>{{ $course->title }}</td>
                <td>{{ $course->date }}, {{ $course->location }}</td>
                <td>John Doe, Alex Smith</td>
                <td class="text-right">
                    <a href="{!! url('admin/courses/'.$course->id.'/edit') !!}" class="btn btn-primary">Edit</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection
