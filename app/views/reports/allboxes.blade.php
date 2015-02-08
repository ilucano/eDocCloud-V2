@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">Reports 
			<small>All Workflows In Process</small>
			</h2>
				
			<ol class="breadcrumb">
				<li>
					<i class="fa fa-dashboard"></i>  Reports
				</li>
				<li class="active">
					<i class="fa fa-table"></i> All Boxes
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
						<th>Box</th>
						<th>Customer / Company</th>
						<th>Status</th>
						<th>Attachment</th>					
					</tr>
				</thead>
				<tbody>
					
					@foreach($workflows as $workflow)
					
					<tr>			
						<td>{{ substr($workflow->wf_id, 6) }}</td>
						<td>{{ $workflow->company_name }}</td>
						<td>{{ $workflow->status }}</td>			
						<td>
						@if($workflow->attach)
							<a class="btn btn-link" href="{{ URL::to('attachment/download/' . $workflow->attach->row_id) }}">{{ $workflow->attach->attach_name }}</a>
						@endif
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
		
			$('#reports').DataTable(
				{
				   "aaSorting": [[ 0 , "asc" ]]
				}
			);
		 } );


	</script>
	
@stop
