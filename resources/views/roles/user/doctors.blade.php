@extends('layouts.user')

@section('content')
<doctors-component :setting-data="{{$setting}}"/>
@endsection