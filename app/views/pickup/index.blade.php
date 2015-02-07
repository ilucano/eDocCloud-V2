@extends('layout')

@section('content')
    
	

	
	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">Workflow 
			<small>Create Pickup</small>
			</h2>
		</div>
	</div>
				
	{{ Form::open(array('url' => 'pickup')) }}

	<div class="row">
		<div class="col-lg-6">
            	
			@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif
			
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			
	
			<div class="form-group">
					<label>Select Order</label>
					{{ Form::select('object_id', $orderLists, null, array('class' => 'form-control')) }}
			 </div>

			
			<div class="form-group">
                    
					<label>Select Barcodes</label>
					<div class="well">
					
						<div class="form-group @if ($errors->has('barcode')) has-error @endif">
								
							<?php $count = 0; ?>
							
							@foreach($barcodeLists as $barcode)
								
								<label class="checkbox-inline">
									{{ Form::checkbox('barcode[]', $barcode->barcode, null, array('id' => 'barcode'.$barcode->barcode )) }} {{ $barcode->barcode }}
								</label>
									
								<?php
								      $count++;
								      if($count % 3 == 0 ) { echo "<br/>"; }
								?>
							@endforeach
							
							@if ($errors->has('barcode')) <p class="help-block">{{ $errors->first('barcode') }}</p> @endif
								
						</div>
							
					</div>
			 
			</div>
				
			{{ Form::submit('Create Pickup', array('class'=>'btn btn-info')) }}
			
		</div>
	</div>
	

	{{ Form::close() }}
@stop 