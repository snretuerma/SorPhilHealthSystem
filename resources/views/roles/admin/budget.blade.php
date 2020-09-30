@extends('layouts.admin')

@section('content')
<adminbudget-component :login-user="{{auth::user()}}"></adminbudget-component>
@endsection