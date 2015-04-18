@extends('layout')

@section('content')

		<div class="col-sm-12">
			

			<h2 class="page-header">System Admin
			<small>Create Box</small>
			</h2>
				
			
		</div>
		
		
		{{ Form::open(array('url' => 'admin/box')) }}
		
		<div class="col-lg-12">
 
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
 
		</div>
		
		<div class="col-lg-4">
            
		
			
			<div class="form-group">
				<label>Company Name</label>
				 <div class="form-group input-group">
				 <span class="input-group-addon"><i class="fa fa-home"></i></span>
				{{ Form::select('fk_company', $companyDropdown, Input::old('fk_company'), array('class'=>'form-control')) }}
				</div>
			</div>

			 
			<div class="form-group @if ($errors->has('f_code')) has-error @endif">
				<label>Code</label>
				{{ Form::text('f_code', Input::old('f_code'), array('class'=>'form-control')) }}
				@if ($errors->has('f_code')) <p class="help-block">{{ $errors->first('f_code') }}</p> @endif
			</div>
			
			<div class="form-group @if ($errors->has('f_name')) has-error @endif">
				<label>Name</label>
				{{ Form::text('f_name', Input::old('f_name'), array('class'=>'form-control')) }}
				@if ($errors->has('f_name')) <p class="help-block">{{ $errors->first('f_name') }}</p> @endif
			</div>
		 	
			<div class="form-group @if ($errors->has('ppc')) has-error @endif">
				<label>Price per Page</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
					{{ Form::text('ppc', Input::old('ppc'), array('class'=>'form-control')) }}
				 </div>
				 @if ($errors->has('ppc')) <p class="help-block">{{ $errors->first('ppc') }}</p> @endif
			</div>
				
			  
			
		   <div class="form-group pull-left">
					
					<a class="btn btn-sm btn-info" href="{{ URL::to('admin/box') }}"> Back</a>
			</div>
						
					<div class="form-group pull-right">
							
					{{ Form::submit('Create', array('class' => 'btn btn-sm btn-success')) }}
					</div>
		</div>		
		

       {{ Form::close() }}

@stop



