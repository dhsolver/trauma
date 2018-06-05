@extends('layouts.app')
@section('title'){{ $staticPage->title }} @endsection
@section('content')
<div class="page-home">
    @if (Session::has('status'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('status') }}
        </div>
    @endif
    <div class="row equal-height bg-white">
        <div class="col-sm-4">
            <div class="content-box text-center">
                <h2>Consulting</h2>
                <img src="/images/page-consulting.jpg" alt="magnifying glass" />
                <h3 class="title">Focused Trauma Solutions</h3>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="content-box text-center">
                <h2>Data</h2>
                <img src="/images/page-data.jpg" alt="barchart" />
                <h3 class="title">Quantitative Analysis & Information Visualization Solutions</h3>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="content-box text-center">
                <h2>Education</h2>
                <img src="/images/page-education.png" alt="computer coding screen" />
                <h3 class="title"><a href="/docs/TraumaInjuryCodingCourseBrochure.pdf" target="_blank">Specialized Continuing Education Solutions</a></h3>
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
