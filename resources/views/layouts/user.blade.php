<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="icon" href="{{asset('images/favicon.ico') }}" type="image/x-icon"/>
    <title>PF Management System</title>
</head>

<body class="page-wrapper chiller-theme toggled">
        <a id="show-sidebar" class="btn btn-sm btn-dark" href="javascript:void(0)">
            <i class="fas fa-bars"></i>
        </a>
        <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-content">
                <div class="row image-box">
                    <div id="close-sidebar" class="sidebar-colapse-btn" >
                        <i class="fas fa-bars" class="sidebar-icons"></i>
                    </div>
                    <div class="dropdown">
                        <span class="sidebar-setting"
                                id="dropdownMenuButton"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            <i class="fas fa-cog" class="sidebar-icons"></i>
                        </span>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <span class="sidebar-setting-item">
                                <i class="fa fa-key sidebar-setting-item-icon"/></i>
                                <a href="{{route('reset')}}">
                                    Reset Password
                                </a>
                            </span><br>
                            <span class="sidebar-setting-item">
                            <i class="fa fa-power-off sidebar-setting-item-icon"></i>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                            </span>
                        </div>
                    </div>
                    <img class="img-responsive rounded-circle img-sidebar"
                        src="{{asset('images/user.jpg')}}"
                        alt="User picture"
                        width = "80"
                        height = "80"
                    />
                   <label id="user" class="sidebar-username">{{ Auth::user()->username }}</label>
                   <label class="sidebar-user-title">username</label>
                </div>
                <div class="sidebar-menu">
                    <ul>
                        <li class="header-menu">
                            <label></label>
                        </li>
                        <li class="sidebar li">
                            <a href="{{ route('summary') }}">
                                <i class="fa fa-tachometer-alt"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <!--<li class="sidebar li">
                            <a href="{{ url('user') }}">
                                <i class="fa fa-tachometer-alt"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar li">
                            <a href="{{route('summary')}}">
                                <i class="fa fa-list-alt"></i><span>Summary</span>
                            </a>
                        </li>-->
                        <li class="sidebar li">
                            <a href="{{route('doctors')}}">
                                <i class="fa fa-user-md"></i><span>Doctors</span>
                            </a>
                        </li>
                        <li class="sidebar li">
                            <a href="{{route('records')}}">
                                <i class="fa fa-file-medical-alt"></i><span>Records</span>
                            </a>
                        </li>
                        <li class="sidebar li">
                            <a href="{{route('restore')}}">
                                <i class="fa fa-history"></i><span>Restore</span>
                            </a>
                        </li>
                        <li class="sidebar li">
                            <a href="{{route('setting')}}">
                                <i class="fa fa-cogs"></i><span>Setting</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <main class="page-content">
            <div class="container-fluid">
                <div id="app">
                    @yield('content')
                </div>
            </div>
        </main>
    <script src="{{asset('js/app.js')}}"></script>
</body>

</html>