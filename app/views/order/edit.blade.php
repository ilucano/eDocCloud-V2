@extends('layout')

@section('content')

		<div class="col-sm-12">
			

			<h2 class="page-header">System Admin
			<small>View / Edit Order</small>
			</h2>
				
			
		</div>
		
		
		{{ Form::model($object, array('route' => array('order.update', $object->row_id), 'method' => 'PUT')) }}	
		
		<div class="col-lg-12">
 
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
 
			<h4>{{ $object->f_code }} / {{ $object->f_name }}  <span class="label label-info">{{ $status }}</span></h4>
		</div>
		
		<div class="col-lg-4">
            
		
			
			<div class="form-group">
				<label>Company Name</label>
				 <div class="form-group input-group">
				 <span class="input-group-addon"><i class="fa fa-home"></i></span>
				{{ Form::select('fk_company', $companyDropdown, $object->fk_company, array('class'=>'form-control')) }}
				</div>
			</div>

			 
			<div class="form-group @if ($errors->has('f_code')) has-error @endif">
				<label>Code</label>
				{{ Form::text('f_code',$object->f_code, array('class'=>'form-control')) }}
				@if ($errors->has('f_code')) <p class="help-block">{{ $errors->first('f_code') }}</p> @endif
			</div>
			
			<div class="form-group @if ($errors->has('f_name')) has-error @endif">
				<label>Name</label>
				{{ Form::text('f_name',$object->f_name, array('class'=>'form-control')) }}
				@if ($errors->has('f_name')) <p class="help-block">{{ $errors->first('f_name') }}</p> @endif
			</div>
		 	
			<div class="form-group @if ($errors->has('ppc')) has-error @endif">
				<label>Price per Page</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
					{{ Form::text('ppc',$object->ppc, array('class'=>'form-control')) }}
				 </div>
				 @if ($errors->has('ppc')) <p class="help-block">{{ $errors->first('ppc') }}</p> @endif
			</div>
				
			 
			
			<div class="form-group @if ($errors->has('qty')) has-error @endif">
				<label>Quantity</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-calculator"></i></span>
					{{ Form::text('qty', $object->qty, array('class'=>'form-control','disabled'=>'disabled')) }}
				 </div>
				 @if ($errors->has('qty')) <p class="help-block">{{ $errors->first('qty') }}</p> @endif
			</div>
				
			
		   <div class="form-group pull-left">
					
					<a class="btn btn-sm btn-info" href="{{ URL::to('order') }}"> Back</a>
			</div>
						
					<div class="form-group pull-right">
							
					{{ Form::submit('Update', array('class' => 'btn btn-sm btn-success')) }}
					</div>
		</div>		
		
		<div class="col-lg-4">
			<div class="form-group">
				<label>Create Date</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					{{ Form::text('creation', Helpers::niceDateTime($object->creation), array('class'=>'form-control', 'disabled'=>'disabled')) }}
				 </div>
				
			</div>
				
			<div class="form-group">
				<label>Pickup</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					{{ Form::text('pickup', Helpers::niceDateTime($object->pickup), array('class'=>'form-control', 'disabled'=>'disabled')) }}
				 </div>
				
			</div>
				
			
			<div class="form-group">
				<label>Scan</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					{{ Form::text('scan', Helpers::niceDateTime($object->scan), array('class'=>'form-control', 'disabled'=>'disabled')) }}
				 </div>
				
			</div>
			
			<div class="form-group">
				<label>QA</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					{{ Form::text('quality', Helpers::niceDateTime($object->quality), array('class'=>'form-control', 'disabled'=>'disabled')) }}
				 </div>
				
			</div>
			 
				
		</div>
 

       {{ Form::close() }}

@stop



