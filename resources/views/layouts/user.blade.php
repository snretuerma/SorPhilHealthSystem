<!DOCTYPE html>
  <html>
    <head>
      <!--Import Google Icon Font-->
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

      <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>

    <body>
        <div>
            <nav>
                <div class="nav-wrapper blue">
                    <a href="{{ url('user') }}" class="brand-logo" style="padding-left: 20px">PF Management System</a>
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

            <ul class="sidenav sidenav-fixed">
                <li><div class="user-view">
                    <div class="background">
                      <img height="200" width="400" src="https://picsum.photos/200/300">
                    </div>
                    <a href="#user"><img class="circle" src="https://picsum.photos/200/300"></a>
                    <a href="#name"><span class="white-text name">{{ Auth::user()->username }}</span></a>
                  </div></li>
                <li><a href="{{ url('budget') }}"><span class="fas fa-fw fa-coins"></span>  Budget</a></li>
                <li><div class="divider"></div></li>
                <li><a href="{{ url('physician') }}"><span class="fas fa-user-md"></span>  Physician</a></li>
                <li><div class="divider"></div></li>
                <li><a href="{{ url('records') }}"><span class="fas fa-file-medical"></span> Records</a></li>
                <li><div class="divider"></div></li>
            </ul>

            <main>
                @yield('content')
            </main>
        </div>

      <!--JavaScript at end of body for optimized loading-->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    </body>
  </html>