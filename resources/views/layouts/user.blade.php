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
        <!--<div>
            <nav class="blue">
                <div class="nav-wrapper">
                    <ul id="nav-mobile" class="right">
                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">Logout
                            </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form></li>
                    </ul>
                </div>
            </nav>

            <ul class="sidenav sidenav-fixed blue">
                {{-- <li><div class="user-view">
                    <div class="background center">
                      <img height="170" width="200" src="{{asset('images/provincial_logo.png')}}">
                    </div>
                    
                    <a href="#"><span class="white-text name">&nbsp;</span></a>
                    <a href="#user"><img class="circle" src="https://picsum.photos/200/300"></a>
                    <a href="#name"><span class="black-text name">&nbsp;{{ Auth::user()->username }}</span></a>
                  </div></li> --}}
                <li>
                  <a href="{{ url('user') }}" class="brand-logo" style="padding-left: 20px"><h6>PF Management System</h6></a>
                </li>
                <li><div class="divider black"></div></li>
                <li><a href="{{ url('user') }}" class="waves-effect waves-teal white-text"><i class="material-icons">dashboard</i>Dashboard</a></li>
                <li><div class="divider black"></div></li>
                <li><a href="{{ url('budget') }}" class="waves-effect waves-teal white-text"><i class="material-icons">attach_money</i>Budget</a></li>
                <li><div class="divider black"></div></li>
                <li><a href="{{ url('physician') }}" class="waves-effect waves-teal white-text"><i class="material-icons">account_circle</i>Physician</a></li>
                <li><div class="divider black"></div></li>
                <li><a href="{{ url('records') }}" class="waves-effect waves-teal white-text"><i class="material-icons">insert_drive_file</i>Records</a></li>
                <li><div class="divider black"></div></li>
            </ul>

            <main>
                @yield('content')
            </main>
        </div>-->

        <div class="page-wrapper chiller-theme toggled">
          <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
            <i class="fas fa-bars"></i>
          </a>
          <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-content">
              <div class="sidebar-brand">
                <a href="#">PF Management System</a>
                <div id="close-sidebar">
                  <i class="fas fa-times"></i>
                </div>
              </div>
              <div class="sidebar-header">
                <div class="user-pic">
                  <img class="img-responsive img-rounded" src="https://raw.githubusercontent.com/azouaoui-med/pro-sidebar-template/gh-pages/src/img/user.jpg"
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
              </div>
              
              <!-- sidebar-search  -->
              <div class="sidebar-menu">
                <ul>
                  <li class="header-menu">
                    <span>General</span>
                  </li>
                  
                  <li class="sidebar li">
                    <a href="">
                      <i class="fa fa-tachometer-alt"></i><span>Dashboard</span>
                    </a>
                  </li>
                  <li class="sidebar li">
                    <a href="">
                      <i class="fa fa-coins"></i><span>Budget</span>
                    </a>
                  </li>
                  <li class="sidebar li">
                    <a href="">
                      <i class="fa fa-user-md"></i><span>Physician</span>
                    </a>
                  </li>
                  <li class="sidebar li">
                    <a href="">
                      <i class="fa fa-file-medical-alt"></i><span>Records</span>
                    </a>
                  </li>
                </ul>
              </div>
              <!-- sidebar-menu  -->
            </div>
            <!-- sidebar-content  -->
            <div class="sidebar-footer">
              <a href="#">
                <i class="fa fa-power-off"></i>&nbsp;&nbsp;Logout
              </a>
            </div>
          </nav>
          <!-- sidebar-wrapper  -->
          <main class="page-content">
            <div class="container-fluid">
              <h2>Pro Sidebar</h2>
              <hr>

              content
              
        
              <footer class="text-center">
                footer
              </footer>
            </div>
          </main>
          <!-- page-content" -->
        </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
             <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
                crossorigin="anonymous"></script>
 -->

                <script>
                  jQuery(function ($) {

$(".sidebar-dropdown > a").click(function() {
$(".sidebar-submenu").slideUp(200);
if (
$(this)
  .parent()
  .hasClass("active")
) {
$(".sidebar-dropdown").removeClass("active");
$(this)
  .parent()
  .removeClass("active");
} else {
$(".sidebar-dropdown").removeClass("active");
$(this)
  .next(".sidebar-submenu")
  .slideDown(200);
$(this)
  .parent()
  .addClass("active");
}
});

$("#close-sidebar").click(function() {
$(".page-wrapper").removeClass("toggled");
});
$("#show-sidebar").click(function() {
$(".page-wrapper").addClass("toggled");
});




});
                </script>
            
    </body>
  </html>