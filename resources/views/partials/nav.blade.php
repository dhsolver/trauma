<nav class="navbar navbar-default navbar-primary">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#main-navbar">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#" data-toggle="collapse" data-target="#main-navbar">@section('title')@show</a>
        </div>

        <div class="collapse navbar-collapse" id="main-navbar">
            <ul class="nav navbar-nav">
                <li class="{{ (Request::is('/') ? 'active' : '') }}">
                    <a href="{{ url('') }}">Home</a>
                </li>
                <li class="{{ (Request::is('consulting') ? 'active' : '') }}">
                    <a href="{{ url('consulting') }}">Consulting</a>
                </li>
                <li class="{{ (Request::is('data') ? 'active' : '') }}">
                    <a href="{{ url('data') }}">Data</a>
                </li>
                <li class="{{ (Request::is('education') ? 'active' : '') }}">
                    <a href="{{ url('education') }}">Education</a>
                </li>
                @if (!Auth::guest() && Auth::user()->approval == 'approved')
                <li class="{{ (Request::is('courses') ? 'active' : '') }}">
                    <a href="{{ url('courses') }}">Course Catalog</a>
                </li>
                @endif
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li class="{{ (Request::is('auth/login') ? 'active' : '') }}"><a href="{{ url('auth/login') }}"><i
                                    class="fa fa-sign-in"></i> Login</a></li>
                    <li class="{{ (Request::is('auth/register') ? 'active' : '') }}"><a
                                href="{{ url('auth/register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false"><i class="fa fa-user"></i> {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} <i
                                    class="fa fa-caret-down"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('profile') }}">My Profile</a>
                            </li>
                            @if(Auth::check())
                                @if(Auth::user()->role=='admin' or Auth::user()->role === 'faculty')
                                    <li role="presentation" class="divider"></li>
                                    <li>
                                        <a href="{{ url('admin/dashboard') }}">Admin Dashboard</a>
                                    </li>
                                @endif
                            @endif
                            <li role="presentation" class="divider"></li>
                            <li>
                                <a href="{{ url('auth/logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
