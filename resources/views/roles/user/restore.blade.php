@extends('layouts.user')

@section('content')
<restore-component :login-user="{{auth::user()}}"></restore-component>
@endsection