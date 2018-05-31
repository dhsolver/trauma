<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@section('title')@show- Administration</title>
    @section('meta_keywords')
        <meta name="keywords" content="Trauma Analytics admin page"/>
    @show @section('meta_author')
        <meta name="author" content="Sean Madhavan"/>
    @show
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
        <link href="{{ asset('css/index.css') }}" rel="stylesheet">

        <script src="{{ asset('js/admin.js') }}"></script>
    @yield('styles')
</head>
<body>

@include('partials.header')

@include('admin.partials.nav')

<div class="page-container">
    <div class="container container-admin">
        <div class="content-box">
            @yield('main')
        </div>
    </div>
</div>

@include('../partials.s3form')
@include('partials.footer')

@yield('scripts')
</body>
</html>
