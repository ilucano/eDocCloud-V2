@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
		    
			<div class="pull-left"><a class="btn btn-sm btn-info" href="{{ URL::to('users/order/'. $order->row_id) }}"><i class="fa fa-caret-left fa-lg"></i> Back</a></div>
			
			<h2 class="page-header">Boxes
			<small>Chart</small>
			
			</h2>
			
			
			 <ol class="breadcrumb">
				<li>
					<i class="fa fa-fw fa-ticket"></i> <a  href="{{ URL::to('users/order') }}">Orders</a>
				</li>
				
				<li>
				<a  href="{{ URL::to('users/order/'. $order->row_id) }}"> {{ $order->f_code }} / {{ $order->f_name }} </a>
                </li>
					
				<li>
					<i class="fa fa-list" class="active"></i> {{ $box->f_code }} /  {{ $box->f_name }} 
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
						<th>Chart</th>
						<th>Chart Date</th>
						<th>Status</th>
						<th>Pages</th>
						<th>Action</th>	
					</tr>
				</thead>
				<tbody>
					
					@foreach($objects as $object)
						<tr>
						
						<td>@if($object->f_code) {{ $object->f_code }} / @endif @if($object->f_name) {{ $object->f_name }} @endif </td>
						<td>{{ Helpers::niceDateTime($object->creation) }} </td>
						<td>{{ $object->status }} </td>
						<td>{{ $object->qty }} </td>
						<td> <a class="btn btn-sm btn-success" href="{{ URL::to('users/chart/' . $box->row_id .'/'. $order->row_id .'/'. $object->row_id) }}"><i class="fa fa-fw fa-folder-open-o"></i> Show Files</a> </td>
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
				}
			);
		 } );
	</script>
@stop
