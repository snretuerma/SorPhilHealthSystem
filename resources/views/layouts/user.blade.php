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
            <nav class="blue">
                <div class="nav-wrapper">
                    <a href="{{ url('user') }}" class="brand-logo" style="padding-left: 20px">PF Management System</a>
                    <ul id="nav-mobile" class="right">
                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"><i class="material-icons tooltipped" data-position="bottom" data-tooltip="Logout">power_settings_new</i>
                            </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form></li>
                    </ul>
                </div>
            </nav>

            <ul class="sidenav sidenav-fixed">
                <li><div class="user-view">
                    <div class="background center">
                      <img height="170" width="200" src="{{asset('images/provincial_logo.png')}}">
                    </div>
                    
                    <a href="#"><span class="white-text name">&nbsp;</span></a>
                    <a href="#user"><img class="circle" src="https://picsum.photos/200/300"></a>
                    <a href="#name"><span class="black-text name">&nbsp;{{ Auth::user()->username }}</span></a>
                  </div></li>
                <li><div class="divider"></div></li>
                <li><a href="{{ url('budget') }}"><i class="material-icons">attach_money</i>Budget</a></li>
                <li><div class="divider"></div></li>
                <li><a href="{{ url('physician') }}"><i class="material-icons">account_circle</i>Physician</a></li>
                <li><div class="divider"></div></li>
                <li><a href="{{ url('records') }}"><i class="material-icons">insert_drive_file</i>Records</a></li>
                <li><div class="divider"></div></li>
            </ul>

            <main>
                @yield('content')
            </main>
        </div>

      <!--JavaScript at end of body for optimized loading-->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          var elems = document.querySelectorAll('.tooltipped');
          var instances = M.Tooltip.init(elems, options);
        });

        // Or with jQuery

        $(document).ready(function(){
          $('.tooltipped').tooltip();
        });
      </script>
    </body>
  </html>