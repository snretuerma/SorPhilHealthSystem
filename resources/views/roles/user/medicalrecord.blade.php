@extends('layouts.user')

@section('content')
<medical-component :medical-record-id={{$id}} :total-fee={{$total_fee}}></medical-component>
@endsection