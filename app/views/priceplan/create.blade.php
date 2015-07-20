@extends('layout')

@section('content')

        <div class="col-sm-12">
            

            <h2 class="page-header">Site Admin 
            <small>Create Price Plan Template</small>
            </h2>
                
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  Site Admin 
                </li>
                    
                <li class="active">
                    <i class="fa fa-users"></i> <a href="{{ URL::route('priceplan.index') }}">Price Plan Template</a>
                </li>
 
                <li class="active">
                    <i class="fa fa-user"></i> New Template
                </li>
            </ol
        </div>

        <div class="row">
            <div class="col-lg-8">
                @if (Session::has('message'))
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                @endif
            
            </div>
        </div> 

        {{ Form::open(['route' => 'priceplan.store']) }}

        <h3>General</h3>
        
        <div class="row">

            <div class="col-lg-5">
                <div class="form-group @if ($errors->has('plan_name')) has-error @endif">
                    <label>Price Plan Name</label>
                    {{ Form::text('plan_name', Input::old('plan_name'), array('class'=>'form-control')) }}
                    
                    @if ($errors->has('plan_name')) <p class="help-block">{{ $errors->first('plan_name') }}</p> @endif         
                </div>
 
                <div class="form-group @if ($errors->has('plan_code')) has-error @endif">
                     
                    <label>Plan Code</label>
                   
                        {{ Form::text('plan_code', Input::old('plan_code'), array('class'=>'form-control')) }}
                   
                    @if ($errors->has('plan_code')) <p class="help-block">{{ $errors->first('plan_code') }}</p> @endif
                        
                </div>

                 <div class="form-group @if ($errors->has('base_price')) has-error @endif">
                     
                    <label>Base Price</label>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                       
                            {{ Form::text('base_price', Input::old('base_price'), array('class'=>'form-control', 'placeholder' => '0.00')) }}
       
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
                
                    {{ Form::text('free_users', Input::old('free_users'), array('class'=>'form-control')) }}
                   
                    @if ($errors->has('free_users')) <p class="help-block">{{ $errors->first('free_users') }}</p> @endif
                </div>
            </div> 
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="form-group form-inline @if ($errors->has('user_to.0')) has-error @endif">
                    <label>Up to </label>
                        {{ Form::text('user_to[]', null, array('class'=>'form-control', 'id' => 'user_to_0')) }}
                     <label> Users </label>
                        {{ Form::text('price_per_user[]', null, array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_user_0')) }}
                     <label> USD each user </label>
                </div>

            
                <div class="form-group form-inline @if ($errors->has('user_to.1')) has-error @endif">
                    <label>Up to </label>
                        {{ Form::text('user_to[]', Input::old('user_to')[1], array('class'=>'form-control', 'id' => 'user_to_1')) }}
                     <label> Users </label>
                        {{ Form::text('price_per_user[]', Input::old('price_per_user')[1], array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_user_1')) }}
                     <label> USD each user </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('user_to.2')) has-error @endif">
                     <label>Up to </label>
                        {{ Form::text('user_to[]', Input::old('user_to')[2], array('class'=>'form-control', 'id' => 'user_to_2')) }}
                     <label> Users </label>
                        {{ Form::text('price_per_user[]', Input::old('price_per_user')[2], array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_user_2')) }}
                     <label> USD each user </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('user_to.3')) has-error @endif">
                    <label>Up to </label>
                        {{ Form::text('user_to[]', Input::old('user_to')[3], array('class'=>'form-control', 'id' => 'user_to_3')) }}
                    <label> Users </label>
                        {{ Form::text('price_per_user[]', Input::old('price_per_user')[3], array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_user_3')) }}
                    <label> USD each user </label>
                </div>

            </div>
        </div>


        <h3>Storage</h3>

        <div class="row">
            <div class="col-lg-8">
                <div class="form-group form-inline @if ($errors->has('free_gb')) has-error @endif">
                <label>Free GB on Plan:</label>
                
                    {{ Form::text('free_gb', Input::old('free_gb'), array('class'=>'form-control')) }}
                   
                    @if ($errors->has('free_gb')) <p class="help-block">{{ $errors->first('free_gb') }}</p> @endif
                </div>
            </div> 
        </div>

        <div class="row">
            <div class="col-lg-8">

                <div class="form-group form-inline @if ($errors->has('gb_to.0')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('gb_to[]', Input::old('gb_to.0'), array('class'=>'form-control', 'id' => 'gb_to_0')) }}
                    <label> GB </label>
                        {{ Form::text('price_per_gb[]', Input::old('price_per_gb.0'), array('class'=>'form-control', 'id' => 'price_per_gb_0', 'placeholder' => '0.00')) }}
                    <label> USD each GB </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('gb_to.1')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('gb_to[]', Input::old('gb_to.1'), array('class'=>'form-control', 'id' => 'gb_to_1')) }}
                    <label> GB </label>
                        {{ Form::text('price_per_gb[]', Input::old('price_per_gb.1'), array('class'=>'form-control', 'id' => 'price_per_gb_1', 'placeholder' => '0.00')) }}
                    <label> USD each GB </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('gb_to.2')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('gb_to[]', Input::old('gb_to.2'), array('class'=>'form-control', 'id' => 'gb_to_2')) }}
                    <label> GB </label>
                        {{ Form::text('price_per_gb[]', Input::old('price_per_gb.2'), array('class'=>'form-control', 'id' => 'price_per_gb_2', 'placeholder' => '0.00')) }}
                    <label> USD each GB </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('gb_to.3')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('gb_to[]', Input::old('gb_to.3'), array('class'=>'form-control', 'id' => 'gb_to_3')) }}
                    <label> GB </label>
                        {{ Form::text('price_per_gb[]', Input::old('price_per_gb.3'), array('class'=>'form-control', 'id' => 'price_per_gb_3', 'placeholder' => '0.00')) }}
                    <label> USD each GB </label>
                </div>

            </div>
        </div>


        <h3>Own Scans</h3>
         <div class="row">
            <div class="col-lg-8">
                <div class="form-group form-inline @if ($errors->has('free_own_scans')) has-error @endif">
                <label>Free Own Scans on Plan:</label>
                
                    {{ Form::text('free_own_scans', Input::old('free_own_scans'), array('class'=>'form-control')) }}
                   
                    @if ($errors->has('free_own_scans')) <p class="help-block">{{ $errors->first('free_own_scans') }}</p> @endif
                </div>
            </div> 
        </div>
        
        <div class="row">
            <div class="col-lg-8">

                <div class="form-group form-inline @if ($errors->has('own_scan_to.0')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('own_scan_to[]', Input::old('own_scan_to.0'), array('class'=>'form-control', 'id' => 'own_scan_to_0')) }}
                    <label> Own Scans </label>
                        {{ Form::text('price_per_own_scan[]', Input::old('price_per_own_scan.0'), array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_own_scan_0')) }}
                    <label> USD each own scan page </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('own_scan_to.1')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('own_scan_to[]', Input::old('own_scan_to.1'), array('class'=>'form-control', 'id' => 'own_scan_to_1')) }}
                    <label> Own Scans </label>
                        {{ Form::text('price_per_own_scan[]', Input::old('price_per_own_scan.1'), array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_own_scan_1')) }}
                    <label> USD each own scan page </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('own_scan_to.2')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('own_scan_to[]', Input::old('own_scan_to.2'), array('class'=>'form-control', 'id' => 'own_scan_to_2')) }}
                    <label> Own Scans </label>
                        {{ Form::text('price_per_own_scan[]', Input::old('price_per_own_scan.2'), array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_own_scan_2')) }}
                    <label> USD each own scan page </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('own_scan_to.3')) has-error @endif">
                   <label>Up to </label>
                    
                        {{ Form::text('own_scan_to[]', Input::old('own_scan_to.3'), array('class'=>'form-control', 'id' => 'own_scan_to_3')) }}
                    <label> Own Scans </label>
                        {{ Form::text('price_per_own_scan[]', Input::old('price_per_own_scan.3'), array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_own_scan_3')) }}
                    <label> USD each own scan page </label>
                </div>

            </div>
        </div>

        <h3>Scans</h3>

        <div class="row">
            <div class="col-lg-8">
                <div class="form-group form-inline @if ($errors->has('free_plan_scans')) has-error @endif">
                <label>Free Scans on Plan:</label>
                
                    {{ Form::text('free_plan_scans', Input::old('free_plan_scans'), array('class'=>'form-control')) }}
                   
                    @if ($errors->has('free_plan_scans')) <p class="help-block">{{ $errors->first('free_plan_scans') }}</p> @endif
                </div>
            </div> 
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="form-group form-inline @if ($errors->has('plan_scan_to.0')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('plan_scan_to[]', Input::old('plan_scan_to.0'), array('class'=>'form-control', 'id' => 'plan_scan_to_0')) }}
                    <label> Scans </label>
                        {{ Form::text('price_per_plan_scan[]', Input::old('price_per_plan_scan.0'), array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_plan_scan_0')) }}
                    <label> USD each scan page </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('plan_scan_to.1')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('plan_scan_to[]', Input::old('plan_scan_to.1'), array('class'=>'form-control', 'id' => 'plan_scan_to_1')) }}
                    <label> Scans </label>
                        {{ Form::text('price_per_plan_scan[]', Input::old('price_per_plan_scan.1'), array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_plan_scan_1')) }}
                    <label> USD each scan page </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('plan_scan_to.2')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('plan_scan_to[]', Input::old('plan_scan_to.2'), array('class'=>'form-control', 'id' => 'plan_scan_to_2')) }}
                    <label> Scans </label>
                        {{ Form::text('price_per_plan_scan[]', Input::old('price_per_plan_scan.2'), array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_plan_scan_2')) }}
                    <label> USD each scan page </label>
                </div>

                <div class="form-group form-inline @if ($errors->has('plan_scan_to.3')) has-error @endif">
                    <label>Up to </label>
                    
                        {{ Form::text('plan_scan_to[]', Input::old('plan_scan_to.3'), array('class'=>'form-control', 'id' => 'plan_scan_to_3')) }}
                    <label> Scans </label>
                        {{ Form::text('price_per_plan_scan[]', Input::old('price_per_plan_scan.3'), array('class'=>'form-control', 'placeholder' => '0.00', 'id' => 'price_per_plan_scan_3')) }}
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
                            
                    {{ Form::submit('Create', array('class' => 'btn btn-sm btn-success')) }}
                    </div>
        </div>
        {{ Form::close() }}

@stop


