<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">


    <title>PF Management System</title>

</head>

<body>
    <div class="page-wrapper chiller-theme toggled">
        <a id="show-sidebar" class="btn btn-sm btn-dark" href="javascript:void(0)" style="z-index: 100;">
            <i class="fas fa-bars"></i>
        </a>
        <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-content" style="overflow-x: hidden;overflow-y:auto;">
               <!-- <div class="sidebar-brand" style="height: 0%">
                    <a href="{{ url('user') }}">PF Management System</a>
                    <div id="close-sidebar">
                        <i class="fas fa-times"></i>
                    </div>
                </div>-->
                
                <!--background-image:url({{asset('images/sidebar_background.jpg')}});
                background-repeat: no-repeat;background-size: 100% 100%;-->
                <div class="row image-box">
                 <div id="close-sidebar" class="sidebar-colapse-btn" >
                    <i class="fas fa-bars" style="font-size:18px;"></i>
                </div>
                <div class="dropdown"> 
                    <span class="sidebar-setting" 
                            id="dropdownMenuButton" 
                            data-toggle="dropdown" 
                            aria-haspopup="true" 
                            aria-expanded="false"> 
                           <i class="fas fa-cog" style="font-size:18px;"></i>

                    </span> 
                    
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"> 
                        
                        <!--<a class="dropdown-item" href="#">  Reset Password </a> 
                        <a class="dropdown-item" href="#">  Logout </a>--> 
                        <span class="sidebar-setting-item">
                        <i class="fa fa-key sidebar-setting-item-icon"></i>
                        <a href="{{route('reset')}}">
                            Reset Password
                        </a></span><br>
                        <span class="sidebar-setting-item">
                        <i class="fa fa-power-off sidebar-setting-item-icon"></i>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
                    >
                    
                   <label id="user" class="sidebar-username">{{ Auth::user()->username }}</label>
                   <label class="sidebar-user-title">username</label>

                    
                       
                 
                    
                </div>
                
                <!--<div class="sidebar-header">
                    <div class="user-pic">
                        <img class="img-responsive img-rounded" 
                          src="{{asset('images/user.jpg')}}" 
                          alt="User picture">
                    </div>
                    <div class="user-info">
                        <span class="user-name">
                            <strong>{{ Auth::user()->username }}</strong>
                        </span>
                        <span class="user-role">User</span>
                        <span class="user-status">
                            <i class="fa fa-circle"></i>
                            <span>Online</span>
                        </span>
                    </div>
                </div>-->

                <!-- sidebar-search  -->
                <div class="sidebar-menu">
                    <ul>
                        <li class="header-menu">
                            <!--<span>Menu</span>-->
                            <label></label>
                        </li>
                        <li class="sidebar li">
                            <a href="{{ url('user') }}">
                                <i class="fa fa-tachometer-alt"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar li">
                            <a href="{{route('budget')}}">
                                <i class="fa fa-coins"></i><span>Budget</span>
                            </a>
                        </li>
                        <li class="sidebar li">
                            <a href="{{route('personnel')}}">
                                <i class="fa fa-user-md"></i><span>Staffs</span>
                            </a>
                        </li>
                        <li class="sidebar li">
                            <a href="{{route('patients')}}">
                                <i class="fa fa-hospital-user"></i><span>Patients</span>
                            </a>
                        </li>
                        <li class="sidebar li">
                            <a href="{{route('record')}}">
                                <i class="fa fa-file-medical-alt"></i><span>Records</span>
                            </a>
                        </li>
                        <li class="sidebar li">
                            <a href="{{route('restore')}}">
                                <i class="fa fa-file-medical-alt"></i><span>Restore</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- sidebar-menu  -->
            </div>
            <!-- sidebar-content 
            <div class="sidebar-footer">
                <a href="{{route('reset')}}">
                    <i class="fa fa-key"></i>&nbsp;&nbsp;Reset Password
                </a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
                    <i class="fa fa-power-off"></i>&nbsp;&nbsp;Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div> -->
        </nav>


        <!-- sidebar-wrapper  -->
        <main class="page-content">
            <div class="container-fluid">
                <div id="app">
                    @yield('content')
                </div>
            </div>
        </main>

        <!-- page-content" -->
    </div>
    <script src="{{asset('js/app.js')}}"></script>
</body>

</html>