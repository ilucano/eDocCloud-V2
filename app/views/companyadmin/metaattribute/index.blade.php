@extends('layout')

@section('content')

 
		<div class="col-lg-10">
			<h2 class="page-header">{{ Auth::User()->getCompanyName() }}
			<small>Meta Data (Attributes)</small>
			<div class="pull-right"><a class="btn btn-sm btn-success" href="{{ URL::to('companyadmin/metaattribute/create') }}"><i class="fa fa-plus-circle fa-lg"></i> Create New Attribute</a></div>
			</h2>
			
		    <ol class="breadcrumb">
				<li>
					<i class="fa fa-fw fa-dashboard"></i> Company Admin
				</li>
				<li class="active">
					<i class="fa fa-tag"></i> Meta Data / Attributes
				</li>
			</ol>
			
		</div>
 
 
 
	
		<div class="col-lg-10">
			@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif
			
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			

			<table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-striped" id="datatables">
				<thead>
					<tr>
						<th><i class="fa fa-tag"></i> Label</th>
						<th> Created Date</th>
						<th> Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach($attributes as $attribute)
					
					<tr>			
						<td>
							 
							 
						</td>
		                <td>
						 
						</td>
						
						<td>
							 
						</td>
	
					</tr>
						
					@endforeach
 
				</tbody>
			</table>

		</div>
			
 

@stop


@section('loadjs')
	
	<script type="text/javascript">
		$(document).ready(function() {
		
			$('#datatables').DataTable(
				{ "order": [[ 2, "desc" ]] }
			);
		 } );
	</script>
@stop
