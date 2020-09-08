@extends('backend.layouts.app')

@section('title', __('labels.backend.access.roles.management') . ' | ' . __('labels.backend.access.roles.create'))

@section('content')

    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        @lang('labels.backend.access.roles.management')
                        <small class="text-muted">View Role</small>
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
                                            <th>Role ID</th>
                                            <td>{{ $role->uid }}</td>
                                        </tr>
                            
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $role->name }}</td>
                                        </tr>
                            
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $role->description }}</td>
                                        </tr>
                            
                                        <tr>
                                            <th>Composite</th>
                                            <td>
                                                @if ($role->composite)
                                                    <span class="badge badge-success">@lang('labels.general.yes')</span>
                                                @else
                                                    <span class="badge badge-danger" style="cursor:pointer">@lang('labels.general.no')</span>           
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                            
                                </div><!--tab-->
                            </div><!--tab-content-->
                                        
                        </div><!--tab-->
                    </div><!--tab-content-->
                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <small class="float-right text-muted">
                        
                    </small>
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->

@endsection
