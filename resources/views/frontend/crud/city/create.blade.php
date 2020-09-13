@extends('frontend.crud.layout')
 
@section('content')
{{ html()->form('POST', route('frontend.crud.city.store'))->class('form-horizontal')->open() }}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        Create City
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4 mb-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label('Name')->class('col-md-2 form-control-label')->for('name') }}

                        <div class="col-md-10">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder('Name')
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                    {{ html()->label('Postal Code')->class('col-md-2 form-control-label')->for('postal_code') }}

                        <div class="col-md-10">
                            {{ html()->text('postal_code')
                                ->class('form-control')
                                ->placeholder('Postal Code')
                                ->attribute('maxlength', 191)
                                ->required() }}
                            <span class="text-danger">{{ $errors->first('postal_code') }}</span>
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label('Population')->class('col-md-2 form-control-label')->for('population') }}

                        <div class="col-md-10">
                            {{ html()->text('population')
                                ->class('form-control')
                                ->placeholder('Population')
                                ->attribute('maxlength', 191)
                                ->required() }}
                            <span class="text-danger">{{ $errors->first('population') }}</span>
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label('Region')->class('col-md-2 form-control-label')->for('region') }}

                        <div class="col-md-10">
                            {{ html()->text('region')
                                ->class('form-control')
                                ->placeholder('Region')
                                ->attribute('maxlength', 191)
                                ->required() }}
                            <span class="text-danger">{{ $errors->first('region') }}</span>
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label('Country')->class('col-md-2 form-control-label')->for('country') }}

                        <div class="col-md-10">
                            {{ html()->text('country')
                                ->class('form-control')
                                ->placeholder('Country')
                                ->attribute('maxlength', 191)
                                ->required() }}
                            <span class="text-danger">{{ $errors->first('country') }}</span>
                        </div><!--col-->
                    </div><!--form-group-->
                    
                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer clearfix">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('frontend.crud.city.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}
@endsection
