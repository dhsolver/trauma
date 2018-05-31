<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@section('title')@show- Trauma Analytics</title>
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

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

</head>
<body>

@include('partials.header')

@include('partials.nav')

<div class="page-container">
    <div class="container">
    @yield('content')
    </div>
</div>

@if (!empty($s3Data))
<script type="text/javascript">
    var S3_MAX_SIZE = {{ $s3Data['max_size'] }};
</script>

<form id="s3-form" action="//{{ $s3Data['bucket'] }}.s3-{{ $s3Data['region'] }}.amazonaws.com/" method="post" enctype="multipart/form-data">
    <input type="hidden" name="key" value="" />
    <input type="hidden" name="acl" value="public-read" />
    <input type="hidden" name="X-Amz-Credential" value="{{ $s3Data['access_key'] }}/{{ $s3Data['short_date'] }}/{{ $s3Data['region'] }}/s3/aws4_request" />
    <input type="hidden" name="X-Amz-Algorithm" value="{{ $s3Data['hash_algorithm'] }}" />
    <input type="hidden" name="X-Amz-Date" value="{{ $s3Data['iso_date'] }}" />
    <input type="hidden" name="Policy" value="{{ $s3Data['policy'] }}" />
    <input type="hidden" name="X-Amz-Signature" value="{{ $s3Data['signature'] }}" />
</form>
@endif

@include('partials.footer')

<!-- Scripts -->
@yield('scripts')

</body>
</html>
