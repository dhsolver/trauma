<nav class="navbar navbar-default navbar-primary">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">@section('title')@show</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
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

                @if (!Auth::guest())
                <li class="{{ (Request::is('courses') ? 'active' : '') }}">
                    <a href="{{ url('courses') }}">Courses Catalog</a>
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
                           aria-expanded="false"><i class="fa fa-user"></i> {{ Auth::user()->name }} <i
                                    class="fa fa-caret-down"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            @if(Auth::check())
                                @if(Auth::user()->admin==1)
                                    <li>
                                        <a href="{{ url('admin/dashboard') }}"><i class="fa fa-tachometer"></i> Admin</a>
                                    </li>
                                @endif
                                <li role="presentation" class="divider"></li>
                            @endif
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
