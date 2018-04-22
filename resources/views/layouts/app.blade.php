<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@section('title') Trauma Analytics @show</title>
    @section('meta_keywords')
        <meta name="keywords" content="your, awesome, keywords, here"/>
    @show @section('meta_author')
        <meta name="author" content="Mike Walker, Sean Madhavan"/>
    @show @section('meta_description')
        <meta name="description"
              content="Use Trauma Analytics to improve your learning experience."/>
    @show
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <link href="{{ asset('css/site.css') }}" rel="stylesheet">
		<link href="{{ asset('css/index.css') }}" rel="stylesheet">

        <script src="{{ asset('js/site.js') }}"></script>

    @yield('styles')
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="{!! asset('assets/site/ico/favicon.ico')  !!} ">
</head>
<body>

@include('partials.header')

@include('partials.nav')

<div class="page-container">
    <div class="container">
    @yield('content')
    </div>
</div>

@include('partials.footer')

<!-- Scripts -->
@yield('scripts')

</body>
</html>
