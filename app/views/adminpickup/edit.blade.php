@extends('layout')

@section('content')

		<div class="col-sm-12">
			

			<h2 class="page-header">System Admin
			<small>View / Edit Pickup</small>
			</h2>
				
			
		</div>
		
		
		{{ Form::model($pickup, array('route' => array('admin.pickup.update', $pickup->row_id), 'method' => 'PUT')) }}	
		
		<div class="col-lg-12">
 
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
		</div>
		
		<div class="col-lg-6">
            
		
			
			<div class="form-group">
				<label>User</label>
				 <div class="form-group input-group">
				 <span class="input-group-addon"><i class="fa fa-user"></i></span>
				{{ Form::select('fk_user', $userDropdown, $pickup->fk_user, array('class'=>'form-control')) }}
				</div>
			</div>
				
			<div class="form-group">
				<label>Company</label>
				 <div class="form-group input-group">
				 <span class="input-group-addon"><i class="fa fa-home"></i></span>
				{{ Form::select('fk_company', $companyDropdown, $pickup->fk_company, array('class'=>'form-control')) }}
				</div>
			</div>
			
			<div class="form-group">
				<label>Order</label>
				 <div class="form-group input-group">
				 <span class="input-group-addon"><i class="fa fa-ticket"></i></span>
				{{ Form::select('fk_order', $orderDropdown, $pickup->fk_order, array('class'=>'form-control')) }}
				</div>
			</div>
				
			
			<div class="form-group @if ($errors->has('fk_barcode')) has-error @endif">
				<label>Barcode</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
					{{ Form::text('fk_barcode',$pickup->fk_barcode, array('class'=>'form-control')) }}
				 </div>
				 @if ($errors->has('fk_barcode')) <p class="help-block">{{ $errors->first('fk_barcode') }}</p> @endif
			</div>
			 
            <div class="form-group">
				<label>Box</label>
				 <div class="form-group input-group">
				 <span class="input-group-addon"><i class="fa fa-cube"></i></span>
				{{ Form::select('fk_box', $boxDropdown, $pickup->fk_box, array('class'=>'form-control')) }}
				</div>
			</div>
				
		   <div class="form-group pull-left">
					
					<a class="btn btn-sm btn-info" href="{{ URL::to('admin/pickup') }}"> Back</a>
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
					{{ Form::text('timestamp', Helpers::niceDateTime($pickup->timestamp), array('class'=>'form-control', 'disabled'=>'disabled')) }}
				 </div>
				
			</div>

				
		</div>
 

       {{ Form::close() }}

@stop



