@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
		    
			<div class="pull-left"><a class="btn btn-sm btn-info" href="{{ URL::to('users/order') }}"><i class="fa fa-caret-left fa-lg"></i> Back</a></div>
			
			<h2 class="page-header">Order
			<small>Boxes</small>
			
			</h2>
			
			
			 <ol class="breadcrumb">
				<li>
					<i class="fa fa-fw fa-ticket"></i> <a  href="{{ URL::to('users/order') }}">Orders</a>
				</li>
				<li>
					<i class="fa fa-list" class="active"></i> {{ $parent->f_code }} /  {{ $parent->f_name }} 
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
						<th>Box</th>
						<th>Box Date</th>
						<th>Status</th>
						<th>Pages</th>
						<th>Cost</th>
						<th>Action</th>	
					</tr>
				</thead>
				<tbody>
					
					@foreach($objects as $object)
						<tr>
						<td>{{ $object->f_code }} / {{ $object->f_name }} </td>
						<td>{{ Helpers::niceDateTime($object->creation) }} </td>
						<td>{{ $object->estatus }} </td>
						<td>{{ $object->qty }} </td>
						<td>{{ Helpers::money($object->price) }} </td>
						<td> <a class="btn btn-sm btn-success" href="{{ URL::to('users/chart/' . $object->row_id .'/'. $parent->row_id) }}"><i class="fa fa-fw fa-list"></i> List File</a> </td>
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
