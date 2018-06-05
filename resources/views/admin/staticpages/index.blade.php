@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title')Static Pages @endsection

{{-- Content --}}
@section('main')
    <h2 class="page-title">
        Static Pages
    </h2>

    @if (Session::has('pageMessage'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('pageMessage') }}
        </div>
    @endif

    @if (Session::has('message'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="table-responsive table-container">
        <table class="table table-hover">
            @foreach ($pages as $page)
            <tr>
                <td><a href="{!! url('admin/staticpages/'.$page->id.'/edit') !!}">{{ $page->slug }}</a></td>
                <td><a href="{!! url('admin/staticpages/'.$page->id.'/edit') !!}">{{ $page->title }}</a></td>
                <td class="text-right">
                    <a
                        href="{!! url('admin/staticpages/'.$page->id.'/edit') !!}"
                        class="btn btn-primary"
                    >
                         <i class="fa fa-pencil"></i> Edit
                    </a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection
