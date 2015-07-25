@extends('layout')

@section('content')

        <div class="col-sm-12">
            

            <h2 class="page-header">Site Admin 
            <small>Edit Price Plan</small>
            </h2>
                
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  Site Admin 
                </li>
                    
                <li class="active">
                    <i class="fa fa-users"></i> <a href="{{ URL::route('priceplan.index') }}">Price Plan</a>
                </li>
 
                <li class="active">
                    <i class="fa fa-user"></i> {{ $pricePlan->plan_name }}
                </li>
            </ol
        </div>

        <div class="row">
            <div class="col-lg-8">

                @if ($pricePlan->is_template == 1)
                     <div class="alert alert-info">This is price template. Update template will not affect plan previous  assigned to company.</div>
                @endif

                @if ($company)
                     <div class="alert alert-success"><i class="fa fa-star"></i> This plan is assigned to <strong>{{ $company->company_name }} </strong></div>
                @endif
                
                @if (Session::has('message'))
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                @endif

                @if (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif
                

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div> 

        {{ Form::open(['route' => ['priceplan.update', $pricePlan->id], 'method' => 'PUT']) }}

        <h3>General</h3>
        
        <div class="row">

            <div class="col-lg-5">

                @if ($pricePlan->is_template == 1)
                    <div class="form-group @if ($errors->has('plan_name')) has-error @endif">
                        <label>Price Plan Name</label>

                        {{ Form::text('plan_name', $pricePlan->plan_name, array('class'=>'form-control')) }}
                        
                        @if ($errors->has('plan_name')) <p class="help-block">{{ $errors->first('plan_name') }}</p> @endif         
                    </div>
     
                    <div class="form-group @if ($errors->has('plan_code')) has-error @endif">
                         
                        <label>Plan Code</label>
                       
                            {{ Form::text('plan_code', $pricePlan->plan_code, array('class'=>'form-control')) }}
                       
                        @if ($errors->has('plan_code')) <p class="help-block">{{ $errors->first('plan_code') }}</p> @endif
                            
                    </div>
                @endif

                 <div class="form-group @if ($errors->has('base_price')) has-error @endif">
                     
                    <label>Base Price</label>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                       
                            {{ Form::text('base_price', $pricePlan->base_price, array('class'=>'form-control', 'placeholder' => '0.00')) }}
       
                        </div>
                    

                    @if ($errors->has('base_price')) <p class="help-block">{{ $errors->first('base_price') }}</p> @endif
                </div>
            </div>
        </div>

        <h3>Users</h3>

        <div class="row">
            <div class="col-lg-8">
                <div class="form-group form-inline @if ($errors->has('free_users')) has-error @endif">
                <label>Free Users on Plan:</label>
                
                    {{ Form::text('free_users', $pricePlan->free_users, array('class'=>'form-control')) }}
                   
                    @if ($errors->has('free_users')) <p class="help-block">{{ $errors->first('free_users') }}</p> @endif
                </div>
            </div> 
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="form-group form-inline @if ($errors->has('user_to.0')) has-error @endif">
                    <label>Up to </label>
                        {{ Form::text('user_to[]', @$pricePlan->plan_user_tiers[0]->user_to, array('class'=>'form-control', 'id' => 'user_to_0')) }}
                     <label> Users </label>
                        {{ Form::text('price_per_user[]', @$pricePlan->plan_user_tiers[0]->price_per_user, array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_user_0')) }}
                     <label> USD each user </label>
                </div>

            
                <div class="form-group form-inline @if ($errors->has('user_to.1')) has-error @endif">
                    <label>Up to </label>
                        {{ Form::text('user_to[]', @$pricePlan->plan_user_tiers[1]->user_to, array('class'=>'form-control', 'id' => 'user_to_1')) }}
                     <label> Users </label>
                        {{ Form::text('price_per_user[]', @$pricePlan->plan_user_tiers[1]->price_per_user, array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_user_1')) }}
                     <label> USD each user </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('user_to.2')) has-error @endif">
                     <label>Up to </label>
                        {{ Form::text('user_to[]', @$pricePlan->plan_user_tiers[2]->user_to, array('class'=>'form-control', 'id' => 'user_to_2')) }}
                     <label> Users </label>
                        {{ Form::text('price_per_user[]', @$pricePlan->plan_user_tiers[2]->price_per_user, array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_user_2')) }}
                     <label> USD each user </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('user_to.3')) has-error @endif">
                    <label>Up to </label>
                        {{ Form::text('user_to[]', @$pricePlan->plan_user_tiers[3]->user_to, array('class'=>'form-control', 'id' => 'user_to_3')) }}
                    <label> Users </label>
                        {{ Form::text('price_per_user[]', @$pricePlan->plan_user_tiers[3]->price_per_user, array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_user_3')) }}
                    <label> USD each user </label>
                </div>

            </div>
        </div>


        <h3>Storage</h3>

        <div class="row">
            <div class="col-lg-8">
                <div class="form-group form-inline @if ($errors->has('free_gb')) has-error @endif">
                <label>Free GB on Plan:</label>
                
                    {{ Form::text('free_gb', $pricePlan->free_gb, array('class'=>'form-control')) }}
                   
                    @if ($errors->has('free_gb')) <p class="help-block">{{ $errors->first('free_gb') }}</p> @endif
                </div>
            </div> 
        </div>

        <div class="row">
            <div class="col-lg-8">

                <div class="form-group form-inline @if ($errors->has('gb_to.0')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('gb_to[]', @$pricePlan->plan_storage_tiers[0]->gb_to, array('class'=>'form-control', 'id' => 'gb_to_0')) }}
                    <label> GB </label>
                        {{ Form::text('price_per_gb[]', @$pricePlan->plan_storage_tiers[0]->price_per_gb, array('class'=>'form-control', 'id' => 'price_per_gb_0', 'placeholder' => '0.00')) }}
                    <label> USD each GB </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('gb_to.1')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('gb_to[]', @$pricePlan->plan_storage_tiers[1]->gb_to, array('class'=>'form-control', 'id' => 'gb_to_1')) }}
                    <label> GB </label>
                        {{ Form::text('price_per_gb[]', @$pricePlan->plan_storage_tiers[1]->price_per_gb, array('class'=>'form-control', 'id' => 'price_per_gb_1', 'placeholder' => '0.00')) }}
                    <label> USD each GB </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('gb_to.2')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('gb_to[]', @$pricePlan->plan_storage_tiers[2]->gb_to, array('class'=>'form-control', 'id' => 'gb_to_2')) }}
                    <label> GB </label>
                        {{ Form::text('price_per_gb[]', @$pricePlan->plan_storage_tiers[2]->price_per_gb, array('class'=>'form-control', 'id' => 'price_per_gb_2', 'placeholder' => '0.00')) }}
                    <label> USD each GB </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('gb_to.3')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('gb_to[]', @$pricePlan->plan_storage_tiers[3]->gb_to, array('class'=>'form-control', 'id' => 'gb_to_3')) }}
                    <label> GB </label>
                        {{ Form::text('price_per_gb[]', @$pricePlan->plan_storage_tiers[3]->price_per_gb, array('class'=>'form-control', 'id' => 'price_per_gb_3', 'placeholder' => '0.00')) }}
                    <label> USD each GB </label>
                </div>

            </div>
        </div>


        <h3>Own Scans</h3>
         <div class="row">
            <div class="col-lg-8">
                <div class="form-group form-inline @if ($errors->has('free_own_scans')) has-error @endif">
                <label>Free Own Scans on Plan:</label>
                
                    {{ Form::text('free_own_scans', $pricePlan->free_own_scans, array('class'=>'form-control')) }}
                   
                    @if ($errors->has('free_own_scans')) <p class="help-block">{{ $errors->first('free_own_scans') }}</p> @endif
                </div>
            </div> 
        </div>
        
        <div class="row">
            <div class="col-lg-10">

                <div class="form-group form-inline @if ($errors->has('own_scan_to.0')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('own_scan_to[]', @$pricePlan->plan_own_scan_tiers[0]->own_scan_to, array('class'=>'form-control', 'id' => 'own_scan_to_0')) }}
                    <label> Own Scans </label>
                        {{ Form::text('price_per_own_scan[]', @$pricePlan->plan_own_scan_tiers[0]->price_per_own_scan, array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_own_scan_0')) }}
                    <label> USD each own scan page </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('own_scan_to.1')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('own_scan_to[]', @$pricePlan->plan_own_scan_tiers[1]->own_scan_to, array('class'=>'form-control', 'id' => 'own_scan_to_1')) }}
                    <label> Own Scans </label>
                        {{ Form::text('price_per_own_scan[]', @$pricePlan->plan_own_scan_tiers[1]->price_per_own_scan, array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_own_scan_1')) }}
                    <label> USD each own scan page </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('own_scan_to.2')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('own_scan_to[]', @$pricePlan->plan_own_scan_tiers[2]->own_scan_to, array('class'=>'form-control', 'id' => 'own_scan_to_2')) }}
                    <label> Own Scans </label>
                        {{ Form::text('price_per_own_scan[]', @$pricePlan->plan_own_scan_tiers[2]->price_per_own_scan, array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_own_scan_2')) }}
                    <label> USD each own scan page </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('own_scan_to.3')) has-error @endif">
                   <label>Up to </label>
                    
                        {{ Form::text('own_scan_to[]', @$pricePlan->plan_own_scan_tiers[3]->own_scan_to, array('class'=>'form-control', 'id' => 'own_scan_to_3')) }}
                    <label> Own Scans </label>
                        {{ Form::text('price_per_own_scan[]', @$pricePlan->plan_own_scan_tiers[3]->price_per_own_scan, array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_own_scan_3')) }}
                    <label> USD each own scan page </label>
                </div>

            </div>
        </div>

        <h3>Scans</h3>

        <div class="row">
            <div class="col-lg-8">
                <div class="form-group form-inline @if ($errors->has('free_plan_scans')) has-error @endif">
                <label>Free Scans on Plan:</label>
                
                    {{ Form::text('free_plan_scans', $pricePlan->free_plan_scans, array('class'=>'form-control')) }}
                   
                    @if ($errors->has('free_plan_scans')) <p class="help-block">{{ $errors->first('free_plan_scans') }}</p> @endif
                </div>
            </div> 
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="form-group form-inline @if ($errors->has('plan_scan_to.0')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('plan_scan_to[]', @$pricePlan->plan_plan_scan_tiers[0]->plan_scan_to , array('class'=>'form-control', 'id' => 'plan_scan_to_0')) }}
                    <label> Scans </label>
                        {{ Form::text('price_per_plan_scan[]', @$pricePlan->plan_plan_scan_tiers[0]->price_per_plan_scan, array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_plan_scan_0')) }}
                    <label> USD each scan page </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('plan_scan_to.1')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('plan_scan_to[]', @$pricePlan->plan_plan_scan_tiers[1]->plan_scan_to, array('class'=>'form-control', 'id' => 'plan_scan_to_1')) }}
                    <label> Scans </label>
                        {{ Form::text('price_per_plan_scan[]', @$pricePlan->plan_plan_scan_tiers[1]->price_per_plan_scan, array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_plan_scan_1')) }}
                    <label> USD each scan page </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('plan_scan_to.2')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('plan_scan_to[]', @$pricePlan->plan_plan_scan_tiers[2]->plan_scan_to, array('class'=>'form-control', 'id' => 'plan_scan_to_2')) }}
                    <label> Scans </label>
                        {{ Form::text('price_per_plan_scan[]', @$pricePlan->plan_plan_scan_tiers[2]->price_per_plan_scan, array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_plan_scan_2')) }}
                    <label> USD each scan page </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('plan_scan_to.3')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('plan_scan_to[]', @$pricePlan->plan_plan_scan_tiers[3]->plan_scan_to, array('class'=>'form-control', 'id' => 'plan_scan_to_3')) }}
                    <label> Scans </label>
                        {{ Form::text('price_per_plan_scan[]', @$pricePlan->plan_plan_scan_tiers[3]->price_per_plan_scan, array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_plan_scan_3')) }}
                    <label> USD each scan page </label>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-6">
                    <div class="form-group pull-left">
                    
                    <a class="btn btn-sm btn-info" href="{{ URL::route('priceplan.index') }}"> Cancel</a>
                    </div>
                        
                    <div class="form-group pull-right">
                            
                    {{ Form::submit('Update', array('class' => 'btn btn-sm btn-success')) }}
                    </div>
        </div>
        {{ Form::close() }}

@stop


