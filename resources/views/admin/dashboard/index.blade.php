@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Dashboard @endsection

{{-- Content --}}
@section('main')
    <h3 class="section-title">Pending Users</h3>

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
                    <a href="{!! url('admin/users/'.$user->id.'/reject') !!}" class="btn btn-danger" onclick="return confirm('Are you sure to reject this user?')">Reject</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection
