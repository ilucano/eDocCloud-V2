@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">System Admin 
			<small>Pickup</small>
				<div class="pull-right"><a class="btn btn-sm btn-success" href="{{ URL::to('admin/pickup/create') }}"><i class="fa fa-plus-circle fa-lg"></i> Create Pickup</a></div>
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
						<th>Company</th>
						<th>Order</th>
						<th>Barcode</th>
						<th>Box</th>
						<th>Actions</th>	
					</tr>
				</thead>
				<tbody>
					
					@foreach($pickups as $pickup)
					
					<tr>			
						<td>{{ $pickup->row_id }}</td>
						<td>{{ $pickup->company_name }}</td>
						<td>{{ $pickup->order }}</td>			
						<td>{{ $pickup->fk_barcode }}</td>
						<td>{{ $pickup->box }}</td>
						<td style="white-space: no-wrap;">
								<div class="pull-right">
									<a class="btn btn-sm btn-info" href="{{ URL::to('admin/pickup/' . $pickup->row_id . '/edit') }}"><i class="fa fa-edit fa-lg"></i> View / Edit</a> 
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
