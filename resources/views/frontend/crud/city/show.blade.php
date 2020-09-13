@extends('frontend.crud.layout')
 
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    View City
                </h4>
            </div><!--col-->
        </div><!--row-->

        <div class="row mt-4 mb-4">
            <div class="col">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-expanded="true"><i class="fas fa-user"></i> @lang('labels.backend.access.users.tabs.titles.overview')</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="overview" role="tabpanel" aria-expanded="true">
                        <div class="col">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $city->name }}</td>
                                    </tr>
                        
                                    <tr>
                                        <th>Postal Code</th>
                                        <td>{{ $city->postal_code }}</td>
                                    </tr>  

                                    <tr>
                                        <th>Population</th>
                                        <td>{{ $city->population }}</td>
                                    </tr>
                        
                                    <tr>
                                        <th>Region</th>
                                        <td>{{ $city->region }}</td>
                                    </tr> 
                                    
                                    <tr>
                                        <th>Country</th>
                                        <td>{{ $city->country }}</td>
                                    </tr>                      
                                </table>
                            </div>
                        </div><!--table-responsive-->
                    </div><!--tab-->
                </div><!--tab-content-->
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->

    <div class="card-footer">
        <div class="row">
            <div class="col">
                <small class="float-right text-muted">
                    <strong>@lang('labels.backend.access.users.tabs.content.overview.created_at'):</strong> {{ timezone()->convertToLocal($city->created_at) }} ({{ $city->created_at->diffForHumans() }}),
                    <strong>@lang('labels.backend.access.users.tabs.content.overview.last_updated'):</strong> {{ timezone()->convertToLocal($city->updated_at) }} ({{ $city->updated_at->diffForHumans() }})
                </small>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-footer-->
</div><!--card-->

@endsection
