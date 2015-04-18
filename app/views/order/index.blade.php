@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">System Admin 
			<small>Orders</small>
				<div class="pull-right"><a class="btn btn-sm btn-success" href="{{ URL::to('order/create') }}"><i class="fa fa-plus-circle fa-lg"></i> Create Order</a></div>
			</h2>
		</div>
	</div>
		
 
	<div class="row">
	
		<div class="col-lg-12">

			@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif
			
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			

			<table cellpadding="0" cellspacing="0" border="0" class="display table table-condensed" id="datatables">
				<thead>
					<tr>
						<th>#id</th>
						<th>Code</th>
						<th>Name</th>
						<th>Company / Customer</th>
						<th>Qty</th>
						<th>Status</th>
						<th>Price</th>
						<th style="width: 130px;">Actions</th>	
					</tr>
				</thead>
				<tbody>
					
					@foreach($objects as $object)
					
					<tr>			
						<td>{{ $object->row_id }}</td>
						<td>{{ $object->f_code }}</td>
						<td>{{ $object->f_name }}</td>			
						<td>{{ $object->company_name }}</td>
						<td>{{ $object->qty }}</td>
						<td>{{ $object->status }}</td>
						<td>{{ $object->ppc }}</td>
						<td style="white-space: no-wrap;">
								<div class="pull-left" style="margin-right:2px;">
									<a class="btn btn-sm btn-info" href="{{ URL::to('order/' . $object->row_id . '/edit') }}"> Edit</a> 
								</div>
								<div class="pull-left">
								@if($object->fk_status != '5')
									{{ Form::open(array('url' => 'order/status')) }}
									{{ Form::hidden('row_id',  $object->row_id ) }}
									{{ Form::hidden('status', '5') }}
									{{ Form::submit(' Close', array('class' => 'btn btn-danger btn-sm')) }} 
									{{ Form::close() }}
								@endif
								</div>
								
					    </td>
					 
					</tr>
						
					@endforeach
 
				</tbody>
			</table>

		</div>
			
	</div>

@stop


@section('loadjs')
	
	<script type="text/javascript">
		$(document).ready(function() {
		
			$('#datatables').DataTable(
				{
				   "aaSorting": [[ 5, "asc" ]],
				}
			);
		 } );
	</script>
@stop
