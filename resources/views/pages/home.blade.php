@extends('layouts.app')
@section('title') Home :: @parent @endsection
@section('content')
<div class="page-home">
    <div class="row equal-height bg-white">
        <div class="col-sm-4">
            <div class="content-box text-center">
                <img src="/images/barchart.jpg" alt="barchart" />
                <h3 class="title">Quantitative Analysis & Information Visualization Solutions</h3>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="content-box text-center">
                <img src="/images/computer_w_coding.png" alt="computer coding screen" />
                <h3 class="title"><a href="/docs/TraumaInjuryCodingCourseBrochure.pdf">Specialized Injury Coding Solutions</a></h3>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="content-box text-center">
                <img src="/images/magnifying_glass.jpg" alt="magnifying glass" />
                <h3 class="title">Focused Trauma Consulting Solutions</h3>
            </div>
        </div>
    </div>
    <div class="row m-t-30">
        <div class="col-sm-12">
            <div class="content-box">
                <div class="row">
                    <div class="col-sm-6">
                        <p class="text-justify">Our team of clinical data information experts made up of certified trauma registrars, coders, nurses, analysts, and software engineers are a catalyst for innovative initiatives concentrating in trauma biomedical informatics. We deliver specialized trauma data management solutions allowing trauma registrars, clinicians, coders, surgeons, and trauma centers to focus on evidence based data driven trauma initiatives that achieve the most optimal outcomes for injured patients. Quality reliable trauma data and information that enhances patient outcomes is our business specialty.<br>
                        <br>
                        We leverage our team's advanced technological skill, intellectual knowledge, and trauma data familiarity to deliver meaningful trauma data solutions needed to achieve excellence.</p>
                    </div>
                    <div class="col-sm-6 text-center">
                        <img src="/images/bulb_color.jpg" class="img img-large" alt="trauma bulb" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
