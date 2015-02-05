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
			<div class="form-group">
					<label>Select Order</label>
					{{ Form::select('object_id', $orderLists, null, array('class' => 'form-control')) }}
			 </div>
								
		</div>
	</div>
	

	{{ Form::close() }}
@stop 