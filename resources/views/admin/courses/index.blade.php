@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') Courses @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Courses
        <div class="pull-right">
            <a href="{!! url('admin/courses/create') !!}"
               class="btn btn-sm btn-primary">Create New Course</a>
        </div>
    </h2>

    <div class="table-responsive table-container">
        <table class="table table-hover">
            <tr>
                <td>Learning Algorithms</td>
                <td>Apr 30, New York City</td>
                <td>John Doe, Alex Smith</td>
                <td class="text-right">
                    <a href="{!! url('admin/course/edit/1') !!}" class="btn btn-primary">Edit</a>
                </td>
            </tr>
            <tr>
                <td>Learning Algorithms</td>
                <td>Apr 30, New York City<br>alsdf fajs l</td>
                <td>John Doe, Alex Smith</td>
                <td class="text-right">
                    <a href="{!! url('admin/course/edit/1') !!}" class="btn btn-primary">Edit</a>
                </td>
            </tr>
            <tr>
                <td>Learning Algorithms</td>
                <td>Apr 30, New York City</td>
                <td>John Doe, Alex Smith</td>
                <td class="text-right">
                    <a href="{!! url('admin/course/edit/1') !!}" class="btn btn-primary">Edit</a>
                </td>
            </tr>
            <tr>
                <td>Learning Algorithms</td>
                <td>Apr 30, New York City</td>
                <td>John Doe, Alex Smith</td>
                <td class="text-right">
                    <a href="{!! url('admin/course/edit/1') !!}" class="btn btn-primary">Edit</a>
                </td>
            </tr>
        </table>
    </div>
@endsection
