@extends('layouts.app')

@section('content')
    <div class="columns is-marginless is-centered">
        <div class="column is-5">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">
                        Login
                    </p>
                </header>
                <div class="card-content">
                    <form class="login-form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label">Username</label>
                            </div>
                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input class="input" id="username" type="text" name="username"
                                               value="{{ old('username') }}" required autofocus>
                                    </p>
                                    @if ($errors->has('username'))
                                        <p class="help is-danger">
                                            {{ $errors->first('username') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label">Password</label>
                            </div>

                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input class="input" id="password" type="password" name="password" required>
                                    </p>

                                    @if ($errors->has('password'))
                                        <p class="help is-danger">
                                            {{ $errors->first('password') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div  class="buttons is-centered">
                            <button type="submit" class="button is-primary">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
