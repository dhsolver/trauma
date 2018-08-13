@extends('layouts.app')
@section('title'){{ $staticPage->title }} @endsection
@section('content')
<div class="page-home">
    @if (Session::has('status'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('status') }}
        </div>
    @endif
    @if (Session::has('authMessage'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('authMessage') }}
        </div>
    @endif
    <div class="row equal-height bg-white">
        <div class="col-sm-4">
            <div class="content-box text-center">
                <a href="{{ url('consulting') }}">
                    <h2>Consulting</h2>
                    <img src="/images/page-consulting.jpg" alt="magnifying glass" />
                    <h3 class="title">Focused Trauma Solutions</h3>
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="content-box text-center">
                <a href="{{ url('data') }}">
                    <h2>Data</h2>
                    <img src="/images/page-data.jpg" alt="barchart" />
                    <h3 class="title">Quantitative Analysis & Information Visualization Solutions</h3>
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="content-box text-center">
                <a href="{{ url('education') }}">
                    <h2>Education</h2>
                    <img src="/images/page-education.png" alt="computer coding screen" />
                    <h3 class="title">Specialized Continuing Education Solutions</h3>
                </a>
            </div>
        </div>
    </div>
    <div class="row m-t-30">
        <div class="col-sm-12">
            <div class="content-box">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="text text-justify">
                            {!! $staticPage->text !!}
                        </div>
                    </div>
                    <div class="col-sm-6 text-center">
                        <img src="images/bulb_color.jpg" class="img img-large" alt="trauma bulb" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
