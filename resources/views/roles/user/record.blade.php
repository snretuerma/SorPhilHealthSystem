@extends('layouts.user')

@section('content')
<record-component :login-user="{{auth::user()}}"></budget-component>
@endsection