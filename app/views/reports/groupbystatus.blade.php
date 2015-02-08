@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">Reports 
			<small>Group By Status</small>
			</h2>
				
			<ol class="breadcrumb">
				<li>
					<i class="fa fa-dashboard"></i>  Reports
				</li>
				<li class="active">
					<i class="fa fa-table"></i> Group By Status
				</li>
			</ol>
							
		</div>
	</div>
		
 
	<div class="row">
	
		<div class="col-lg-10">

			@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif
			
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			

			<table cellpadding="0" cellspacing="0" border="0" class="display" id="reports">
				<thead>
					<tr>
						<th>Status</th>
						<th>Quantity</th>
						<th>Amount</th>
						<th>Customer / Company</th>					
					</tr>
				</thead>
				<tbody>
					
					@foreach($results as $result)
					
					<tr>			
						<td>{{ $result->status }}</td>
						<td>{{ $result->qty }}</td>
						<td>{{ Helpers::money($result->amount) }}</td>			
						<td>{{ $result->company_name }}</td>	

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
		
			$('#reports').DataTable(
			);
		 } );


	</script>
	
@stop
