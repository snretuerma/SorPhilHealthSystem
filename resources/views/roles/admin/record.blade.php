@extends('layouts.admin')

@section('content')
<adminrecord-component :login-user="{{auth::user()}}"></adminrecord-component>
@endsection