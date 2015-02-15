@extends('layout')

@section('content')

		<div class="col-sm-12">
			

			<h2 class="page-header">System Admin
			<small>Edit Order</small>
			</h2>
				
			
		</div>
		
		{{ Form::model($object, array('route' => array('order.update', $object->row_id), 'method' => 'PUT')) }}

		
		<div class="col-lg-12">
 
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
 
			<h4>{{ $object->f_code }} / {{ $object->f_name }}</h4>
		</div>
		<div class="col-lg-4">
             
			 
		 	
		   <div class="form-group pull-left">
					
					<a class="btn btn-sm btn-info" href="{{ URL::to('order') }}"> Back</a>
			</div>
						
					<div class="form-group pull-right">
							
					{{ Form::submit('Update', array('class' => 'btn btn-sm btn-success')) }}
					</div>
			</div>		
		
 

       {{ Form::close() }}

@stop



