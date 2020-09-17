<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SorPhilHealth System</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        {{-- TODO clean this and move to separate stylesheet --}}
        <!-- Styles -->
        <style>
            @media only screen and (max-width: 600px) {
                #landing_image {
                    display: block;
                    margin-left: auto;
                    margin-right: auto;
                    width: 250px;
                    height: 250px;
                    margin-top: 15px;
                }
            }
            @media only screen and (min-width: 601px) and (max-width: 992px){
                #landing_image {
                    display: block;
                    margin-left: auto;
                    margin-right: auto;
                    width: 50%;
                    margin-top: 15px;
                }
            }
            @media only screen and (min-width: 993px) and (max-width: 1200px){
                #landing_image {
                    display: block;
                    margin-left: auto;
                    margin-right: auto;
                    width: 45%;
                    margin-top: 20px;
                }
            }
            @media only screen and (min-width: 1201px) {
                #landing_image {
                    display: block;
                    margin-left: auto;
                    margin-right: auto;
                    width: 45%;
                    margin-top: 30px;
                }
            }
        </style>
    </head>
    <body>
        <nav>
            <div class="nav-wrapper">
                <a href="#" class="brand-logo center">Logo</a>
                <ul id="nav-mobile" class=" hide-on-med-and-down">
                </ul>
            </div>
        </nav>
        <main class="container">
        <div class="row">
                <div class="col s12 m12 l12">
                    <img id="landing_image" src="{{asset('images/provincial_logo.png')}}">
                </div>
                <div class="col s12 m12 l12">
                    <h1 class="center-align">Sorsogon PhilHealth System</h1>
                </div>
                <div class="row">
                    <div class="col s12 m12 l12 center">
                        @if (Route::has('login'))
                            <div class="top-right links">
                                @if (Auth::check())
                                    <a class="waves-effect waves-light btn" href="{{ url('/home') }}">Home</a>
                                @else
                                    <a class="waves-effect waves-light btn" href="{{ url('/login') }}">Login</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
