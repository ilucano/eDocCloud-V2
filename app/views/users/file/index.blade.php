@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">My Orders
			<small>List</small>
			</h2>
			
			
			 <ol class="breadcrumb">
				<li>
					<i class="fa fa-fw fa-ticket"></i> Orders
				</li>
				<li class="active">
					<i class="fa fa-list"></i> Order List
				</li>
			</ol>
			
			
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
						<th>Order</th>
						<th>Order Date</th>
						<th>Status</th>
						<th>Pages</th>
						<th>Cost</th>
						<th>Action</th>	
					</tr>
				</thead>
				<tbody>
					
					@foreach($objects as $object)
					
					<tr>			
						<td>{{ $object->f_code }} / {{ $object->f_name }}</td>
						<td>{{ Helpers::niceDateTime($object->creation) }}</td>
						<td>{{ $object->status }}</td>			
						<td>{{ $object->qty }}</td>
						<td>{{ $object->price }}</td>
						<td style="white-space: no-wrap;">
						 
									<a class="btn btn-sm btn-success" href="{{ URL::to('users/order/' . $object->row_id) }}"><i class="fa fa-caret-right fa-lg"></i> View</a> 
				 		
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
