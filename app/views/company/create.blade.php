@extends('layout')

@section('content')

		<div class="col-sm-12">
			

			<h2 class="page-header">Site Admin 
			<small>Company</small>
			</h2>
				
		    <ol class="breadcrumb">
				<li>
					<i class="fa fa-dashboard"></i>  Site Admin 
				</li>
				<li class="active">
					<i class="fa fa-group"></i> <a href="{{ URL::to('company') }}"> Manage Company</a>
				</li>
				
				<li class="active">
					<i class="fa fa-plus"></i> Create New Company
				</li>
			</ol
		</div>
		
		{{ Form::open(array('url' => 'company')) }}
		
		<div class="col-lg-12">
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			<h3>Create New Company</h3>
		</div>
		<div class="col-lg-4">
             
			<div class="form-group @if ($errors->has('company_name')) has-error @endif">
				<label>Company Name</label>
				{{ Form::text('company_name',Input::old('company_name'), array('class'=>'form-control')) }}
				
				
				@if ($errors->has('company_name')) <p class="help-block">{{ $errors->first('company_name') }}</p> @endif
					
			</div>
				
			<div class="form-group ">
				<label>Address 1</label>
				{{ Form::text('company_address1', Input::old('company_address1'), array('class'=>'form-control')) }}
			
			</div>
				
			<div class="form-group">
				<label>Address 2</label>
				{{ Form::text('company_address2', Input::old('company_address2'), array('class'=>'form-control')) }}
			</div>
			<div class="form-group @if ($errors->has('company_zip')) has-error @endif">
				<label>Zip</label>
				{{ Form::text('company_zip', Input::old('company_zip'), array('class'=>'form-control')) }}
				@if ($errors->has('company_zip')) <p class="help-block">{{ $errors->first('company_zip') }}</p> @endif
				
			</div>
        
			<div class="form-group">
				<label>Administrator</label>
					
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
						
					{{ Form::text('', 'No user for this company yet', array('class'=>'form-control', 'disabled' => 'disabled')) }}

				 </div>

			</div>
			


			 <div class="form-group @if ($errors->has('app_domain')) has-error @endif">
				<label>Site Domain</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-globe"></i></span>
					{{ Form::text('app_domain', Input::old('app_domain') ? Input::old('app_domain') : Config::get('app.url'), array('class'=>'form-control')) }}

				 </div>
				@if ($errors->has('app_domain')) <p class="help-block">{{ $errors->first('app_domain') }}</p> @endif
			</div>
			
		</div>
		
		<div class="col-lg-4">
		   
			<div class="form-group">
				<label>Phone</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-phone"></i></span>
					{{ Form::text('company_phone', Input::old('company_phone'), array('class'=>'form-control')) }}
				 </div>
			</div>
				
			<div class="form-group">
				<label>Fax</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-fax"></i></span>
					{{ Form::text('company_fax', Input::old('company_fax'), array('class'=>'form-control')) }}
				 </div>
			</div>
	 
		    <div class="form-group @if ($errors->has('company_email')) has-error @endif">
				<label>Email</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					{{ Form::text('company_email',Input::old('company_email'), array('class'=>'form-control')) }}

				 </div>
				@if ($errors->has('company_email')) <p class="help-block">{{ $errors->first('company_email') }}</p> @endif
			</div>
				
			
			 <div class="form-group @if ($errors->has('fk_terms')) has-error @endif">
				<label>Terms</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					{{ Form::text('fk_terms', Input::old('fk_terms'), array('class'=>'form-control')) }}
					
					
				 </div>
				
				@if ($errors->has('fk_terms')) <p class="help-block">{{ $errors->first('fk_terms') }}</p> @endif
				
			</div>
			
			 <div class="form-group @if ($errors->has('creditlimit')) has-error @endif">
				<label>Credit Limit</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
					{{ Form::text('creditlimit',Input::old('creditlimit'), array('class'=>'form-control')) }}
					<span class="input-group-addon">.00</span>
					
				 </div>
				
				@if ($errors->has('creditlimit')) <p class="help-block">{{ $errors->first('creditlimit') }}</p> @endif
				
			</div>
		   
		</div>		
		
        
		<div class="clearfix"></div>
			
			<div class="col-lg-8">
					<div class="form-group pull-left">
					
					<a class="btn btn-sm btn-info" href="{{ URL::to('company') }}"> Cancel</a>
					</div>
						
					<div class="form-group pull-right">
							
					{{ Form::submit('Create', array('class' => 'btn btn-sm btn-success')) }}
					</div>
			</div>

       {{ Form::close() }}

@stop


