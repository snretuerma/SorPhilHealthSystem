@extends('layouts.admin')

@section('content')
<hospital-component :login-user="{{auth::user()}}"></hospital-component>
@endsection