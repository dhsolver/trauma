@extends('layouts.app')
@section('title'){{ $staticPage->title }} @endsection
@section('content')
<div class="page-home">
    @if (Session::has('status'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('status') }}
        </div>
    @endif
    <div class="content-box text-center">
        @if (!empty($staticPage->image))
            <img src="{{ url('images/'.$staticPage->image) }}" alt=""/>
        @endif
        <h3 class="title">{{ $staticPage->subtitle }}</h3>
        <div class="text text-left">
            {!! $staticPage->text !!}
        </div>
    </div>
</div>
@endsection
