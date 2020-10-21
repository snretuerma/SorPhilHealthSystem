@extends('layouts.admin')

@section('content')
<users-component :login-user="{{auth::user()}}"></users-component>
@endsection