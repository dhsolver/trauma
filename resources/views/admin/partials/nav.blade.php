<nav class="navbar navbar-inverse" role="navigation" style="margin-bottom: 0">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/admin/dashboard">Trauma Admin</a>
        </div>

        <div class="collapse navbar-collapse" id="main-navbar">
            <ul class="nav navbar-nav">
                <li class="{{ (Request::is('admin/courses', 'admin/courses/*') ? 'active' : '') }}">
                    <a href="{{url('admin/courses')}}"><i class="fa fa-language"></i> Courses</a>
                </li>
            </ul>
            @if(Auth::user()->role === 'faculty')
            <ul class="nav navbar-nav">
                <li class="{{ (Request::is('admin/my-teaching', 'admin/my-teaching/*') ? 'active' : '') }}">
                    <a href="{{url('admin/my-teaching')}}"><i class="fa fa-graduation-cap"></i> My Teaching</a>
                </li>
            </ul>
            @endif

            @if (Auth::user()->role === 'admin')
            <ul class="nav navbar-nav">
                <li class="{{ (Request::is('admin/users', 'admin/users/*') ? 'active' : '') }}">
                    <a href="{{url('admin/users')}}"><i class="fa fa-users"></i> Users</a>
                </li>
            </ul>
            @endif
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="{{ url('/') }}">Go to Home</a>
                </li>
                <li>
                    <a href="{{ url('auth/logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
