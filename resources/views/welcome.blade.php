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
        <div>
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <ul class="navbar-nav mr-auto">
                   <li><h4>PF Management System</h4></li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li>
                        @if (Route::has('login'))
                            <div>
                                @if (Auth::check())
                                    <a class="navbar-brand" href="{{ url('/home') }}">Home</a>
                                @else
                                    <a class="navbar-brand"  href="{{ url('/login') }}">Login</a>
                                @endif
                            </div>
                        @endif
                    </li>
                </ul>
            </nav> 
            
            <img src="{{asset('images/provincial_logo.png')}}" class="rounded mx-auto d-block" alt="Responsive image" height="500px" width="500px">
        
        </div>
    </body>
</html>
