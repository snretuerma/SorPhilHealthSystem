@extends('layouts.user')

@section('content')
<medical-component :medical-record-id={{$id}}></medical-component>
@endsection