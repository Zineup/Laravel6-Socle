@extends('crud.layout')
 
@section('content')
<h2 class="text-center text-danger mt-4 mb-5">Show City</a></h2>
<br>
 
<form action="{{ route('city.store') }}" method="POST">
{{ csrf_field() }}
    
    <div class="row">
        <div class="col-md-6 offset-md-3 text-center">
            <span class="h5">Name : </span>
            <span> {{ $city->name }} </span>
        </div>
    </div><br>

    <div class="row">
        <div class="col-md-6 offset-md-3 text-center">
            <span class="h5">Postal Code : </span>
            <span> {{ $city->postal_code }} </span>
        </div>
    </div><br>

    <div class="row">
        <div class="col-md-6 offset-md-3 text-center">
            <span class="h5">Population : </span>
            <span> {{ $city->population }} </span>
        </div>
    </div><br>

    <div class="row">
        <div class="col-md-6 offset-md-3 text-center">
            <span class="h5">Region : </span>
            <span> {{ $city->region }} </span>
        </div>
    </div><br>

    <div class="row">
        <div class="col-md-6 offset-md-3 text-center">
            <span class="h5">Country : </span>
            <span> {{ $city->country }} </span>
        </div>
    </div><br>


</div>
 
</form>
@endsection