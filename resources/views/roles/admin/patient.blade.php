@extends('layouts.admin')

@section('content')
<adminpatient-component :login-user="{{auth::user()}}"></adminpatient-component>
@endsection