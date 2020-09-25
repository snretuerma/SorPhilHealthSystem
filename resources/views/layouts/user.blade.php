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
    <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
      <i class="fas fa-bars"></i>
    </a>
    <nav id="sidebar" class="sidebar-wrapper">
      <div class="sidebar-content">
        <div class="sidebar-brand">
          <a href="{{ url('user') }}">PF Management System</a>
          <div id="close-sidebar">
            <i class="fas fa-times"></i>
          </div>
        </div>
        <div class="sidebar-header">
          <div class="user-pic">
            <img class="img-responsive img-rounded"
              src="https://raw.githubusercontent.com/azouaoui-med/pro-sidebar-template/gh-pages/src/img/user.jpg"
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
                <i class="fa fa-coins"></i><span>Budget</span>
              </a>
            </li>
            <li class="sidebar li">
              <a href="">
                <i class="fa fa-user-md"></i><span>Physician</span>
              </a>
            </li>
            <li class="sidebar li">
              <a href="{{route('patients')}}">
                <i class="fa fa-file-medical-alt"></i><span>Patients</span>
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
        <a href="{{ route('logout') }}" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
          <i class="fa fa-power-off"></i>&nbsp;&nbsp;Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </div>
    </nav>


    <!-- sidebar-wrapper  -->
    <main class="page-content">
      <div class="container-fluid">
        <div id="app">
          @yield('content')
        </div>
        <footer class="text-center">
          footer
        </footer>
      </div>
    </main>
    <!-- page-content" -->
  </div>
  <script src="{{asset('js/app.js')}}"></script>
</body>

</html>