@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Users @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Users
        <div class="pull-right">
            <a href="{!! url('admin/users/create') !!}" class="btn btn-sm btn-primary">Invite a User</a>
        </div>
    </h2>

    @if (Session::has('userMessage'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('userMessage') }}
        </div>
    @endif
    <h3 class="section-title">
        Students
        <!-- <div class="pull-right">
            <a href="{!! url('admin/users/create') !!}" class="btn btn-sm btn-primary">Invite a User</a>
        </div> -->
    </h3>
    <div class="table-responsive table-container">
        <table class="table table-hover">
            @foreach ($students as $user)
            <tr>
                <td>#{{ $user->id }}</td>
                <td><a href="{!! url('admin/users/'.$user->id.'/edit') !!}">{{ $user->first_name }} {{ $user->last_name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->birthday }}</td>
                <td>{{ $user->hospital_name }}</td>
                <td class="text-right">
                    <a href="{!! url('admin/users/'.$user->id.'/edit') !!}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

    <h3 class="section-title">
        Faculties
        <!-- <div class="pull-right">
            <a href="{!! url('admin/users/create') !!}" class="btn btn-sm btn-primary">Invite a User</a>
        </div> -->
    </h3>
    <div class="table-responsive table-container">
        <table class="table table-hover">
            @foreach ($faculties as $user)
            <tr>
                <td>#{{ $user->id }}</td>
                <td><a href="{!! url('admin/users/'.$user->id.'/edit') !!}">{{ $user->first_name }} {{ $user->last_name }}</a></td>
                <td>{{ $user->email }}</td>
                <td class="text-right">
                    <a href="{!! url('admin/users/'.$user->id.'/edit') !!}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection
