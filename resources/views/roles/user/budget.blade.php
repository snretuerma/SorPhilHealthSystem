@extends('layouts.user')

@section('content')
<budget-component :login-user="{{auth::user()}}"></budget-component>
@endsection