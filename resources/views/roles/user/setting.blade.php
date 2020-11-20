@extends('layouts.user')

@section('content')
<setting-component :login-user="{{auth::user()}}"></setting-component>
@endsection