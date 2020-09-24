@extends('layouts.user')

@section('content')
<h2>Patient List</h2>
<hr>

<div class="card">
    <div class="card-body">
        <patient-component></patient-component>
    </div>
</div>
@endsection