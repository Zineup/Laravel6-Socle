@extends('crud.layout')
 
@section('content')
<h2 class="text-center text-danger mt-4">Edit City</a></h2>
<br>

<form action="{{ route('city.update', $city->id) }}" method="POST" name="update_city">
{{ csrf_field() }}
@method('PATCH')
 
<div class="row">
    <div class="col">
        <div class="form-group">
            <strong>Name</strong>
            <input type="text" name="name" class="form-control" placeholder="Enter city name.." value="{{ old('name', $city->name) }}">
            <span class="text-danger">{{ $errors->first('name') }}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <strong>Postal_code</strong>
            <input type="text" name="postal_code" class="form-control" placeholder="Enter city postal code.." value="{{ old('postal_code', $city->postal_code) }}">
            <span class="text-danger">{{ $errors->first('postal_code') }}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <strong>Population</strong>
            <input type="text" name="population" class="form-control" placeholder="Enter city population.." value="{{ old('population', $city->population) }}">
            <span class="text-danger">{{ $errors->first('population') }}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <strong>Region</strong>
            <input type="text" name="region" class="form-control" placeholder="Enter city region.." value="{{ old('region', $city->region) }}">
            <span class="text-danger">{{ $errors->first('region') }}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <strong>Country</strong>
            <input type="text" name="country" class="form-control" placeholder="Enter city country.." value="{{ old('country', $city->country) }}">
            <span class="text-danger">{{ $errors->first('country') }}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <button type="submit" class="btn btn-warning">Edit</button>
    </div>
</div>
 
</form>
@endsection
