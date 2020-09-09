@extends('crud.layout')
   
@section('content')

<h1 class="text-center my-4">List of Cities</h1>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<a href="{{ route('city.create') }}" class="btn btn-success my-3">Add City</a> 
<br>

<div class="row">
   <div class="col-12">
        
      <table class="table table-bordered" id="laravel_crud">
         <thead>
            <tr>
              <th>Id</th>
              <th>Name</th>
              <th>Postal Code</th>
              <th>Population</th>
              <th>Regiont</th>
              <th>Country</th>
              <th>Created at</th>
              <th colspan="3">Actions</th>
            </tr>
         </thead>

         <tbody>
            @foreach($cities as $city)
            <tr>
               <td>{{ $city->id }}</td>
               <td>{{ $city->name }}</td>
               <td>{{ $city->postal_code }}</td>
               <td>{{ $city->population }}</td>                 
               <td>{{ $city->region }}</td>
               <td>{{ $city->country }}</td>
               <td>{{ date('Y-m-d', strtotime($city->created_at)) }}</td>
               <td>
                  <a href="{{ route('city.show',$city->id)}}" class="btn btn-primary">Show</a>
                </td>
               <td>
                  <a href="{{ route('city.edit',$city->id)}}" class="btn btn-warning">Edit</a>
               </td>
               <td>
                  <form action="{{ route('city.destroy', $city->id)}}" method="post">
                     {{ csrf_field() }}
                     @method('DELETE')
                     <button class="btn btn-danger" type="submit">Delete</button>
                  </form>
               </td>
            </tr>
            @endforeach
         </tbody>
      </table>
   </div> 

</div>
@endsection
