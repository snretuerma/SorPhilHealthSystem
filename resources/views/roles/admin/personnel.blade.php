@extends('layouts.admin')

@section('content')
<adminpersonnel-component :login-user="{{auth::user()}}"></adminpersonnel-component>
@endsection