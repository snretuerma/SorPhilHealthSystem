@extends('layouts.user')

@section('content')
<record-component :setting-data="{{$setting}}"></budget-component>
@endsection