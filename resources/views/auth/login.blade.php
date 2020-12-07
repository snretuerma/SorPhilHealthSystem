@extends('layouts.app')

@section('content')
    <section class="login-block center">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7 banner-sec">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <img
                                class="img-fluid d-block"
                                src="{{asset('images/provincial_logo.png')}}"
                                alt="First slide"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 login-sec">
                <div class="login-sec-container">
                    <div class="row">
                        <div class="col-sm-12">
                            <h2 class="text-center">Login</h2>
                        </div>
                        <div class="col-sm-12">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="username" class="text-uppercase">Username</label>
                                            <input
                                                id="username"
                                                type="text"
                                                class="form-control @error('username') is-invalid @enderror"
                                                name="username"
                                                value="{{ old('username') }}"
                                                autofocus
                                            />
                                            @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="password" class="text-uppercase">
                                                Password
                                            </label>
                                            <input
                                                id="password"
                                                type="password"
                                                class="form-control
                                                @error('password') is-invalid @enderror"
                                                name="password"
                                                required
                                                autocomplete="current-password"
                                            />
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 center">
                                        <div class="form-check">
                                            <button type="submit" class="btn btn-outline-primary float-right">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-12 copy-text">
                            Created with <i class="fa fa-heart"></i> Provincial Government of Sorsogon ICT Division
                        </div>
                    </div>
                </div>
                <div class="row ">

                </div>
            </div>
        </div>
    </section>
@endsection
