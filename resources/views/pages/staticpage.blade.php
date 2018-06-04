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
        <p class="text-left">
            {!! nl2br(e($staticPage->text)) !!}
        </p>
    </div>
</div>
@endsection
