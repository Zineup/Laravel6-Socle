@extends('frontend.crud.layout')
   
@section('content')
<div class="card">
   <div class="card-body">
       <div class="row">
           <div class="col-sm-5">
               <h4 class="card-title mb-0">
                  CRUD Management (City)
               </h4>
           </div><!--col-->

           <div class="col-sm-7">
               <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                  <a href="{{ route('frontend.crud.city.create') }}" class="btn btn-success ml-1" data-toggle="tooltip" title="@lang('labels.general.create_new')"><i class="fas fa-plus-circle"></i></a>
               </div><!--btn-toolbar-->
           </div><!--col-->
       </div><!--row-->

       <div class="row mt-4">
           <div class="col">
               <div class="table-responsive">
                   <table class="table">
                       <thead>
                       <tr>
                           <th>Name</th>
                           <th>Postal Code</th>
                           <th>Population</th>
                           <th>Region</th>
                           <th>Country</th>
                           <th>Created At</th>
                           <th>Actions</th>
                       </tr>
                       </thead>
                       <tbody>
                       @foreach($cities as $city)
                           <tr>
                               <td>{{ $city->name }}</td>
                               <td>{{ $city->postal_code }}</td>
                               <td>{{ $city->population }}</td>
                               <td>{{ $city->region }}</td>
                               <td>{{ $city->country }}</td>
                               <td>{{ $city->created_at }}</td>
                               <td>
                                 <div class="btn-group" role="group">
                                    <a href="{{ route('frontend.crud.city.show', $city) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.view')" class="btn btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('frontend.crud.city.edit', $city) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.edit')" class="btn btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('frontend.crud.city.destroy', $city->id) }}"
                                       data-method="delete"
                                       data-trans-button-cancel="@lang('buttons.general.cancel')"
                                       data-trans-button-confirm="@lang('buttons.general.crud.delete')"
                                       data-trans-title="@lang('strings.backend.general.are_you_sure')"
                                       class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.delete')">
                                          <i class="fas fa-trash"></i>
                                    </a>
                                 <div>
                               </td>
                           </tr>
                       @endforeach
                       </tbody>
                   </table>
               </div>
           </div><!--col-->
       </div><!--row-->
       <div class="row">
           <div class="col-7">
               <div class="float-left">
                   {!! $cities->total() !!} {{ trans_choice('cities total', $cities->total()) }}
               </div>
           </div><!--col-->

           <div class="col-5">
               <div class="float-right">
                   {!! $cities->render() !!}
               </div>
           </div><!--col-->
       </div><!--row-->
   </div><!--card-body-->
</div><!--card-->
@endsection
