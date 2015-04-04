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
						<th><i class="fa fa-tag"></i> Attribute Name</th>
						<th> Type</th>
						<th> Required</th>
						<th> Created Date</th>
						<th> Action</th>
					</tr>
				</thead>
				<tbody>
					
					 
					<tr>			
						<td>
							Industry		 
						</td>
		                <td>
							Dropdown
						</td>
						<td>
							Yes
						</td>
						<td>
							Jan 1, 2015
						</td>
						<td>
							 <a class="btn btn-sm btn-info"><i class="fa fa-edit fa-lg"></i> Edit</a>
						</td>
	
					</tr>
					
					<tr>			
						<td>
							Sales Contact		 
						</td>
		                <td>
							Text
						</td>
						<td>
							No
						</td>
						<td>
							Jan 12, 2015
						</td>
						<td>
							 <a class="btn btn-sm btn-info"><i class="fa fa-edit fa-lg"></i> Edit</a>
						</td>
	
					</tr>


					<tr>			
						<td>
							Status
						</td>
		                <td>
							Dropdown
						</td>
						<td>
							Yes
						</td>
						<td>
							Jan 12, 2015
						</td>
						<td>
							 <a class="btn btn-sm btn-info"><i class="fa fa-edit fa-lg"></i> Edit</a>
						</td>
	
					</tr>

					<tr>			
						<td>
							Products
						</td>
		                <td>
							Check Boxes
						</td>
						<td>
							No
						</td>
						<td>
							Jan 10, 2015
						</td>
						<td>
							 <a class="btn btn-sm btn-info"><i class="fa fa-edit fa-lg"></i> Edit</a>
						</td>
	
					</tr>
					 
				</tbody>
			</table>

		</div>
			
 

@stop


@section('loadjs')
	
	<script type="text/javascript">
		$(document).ready(function() {
		
			$('#datatables').DataTable(
				{ "order": [[ 0, "asc" ]] }
			);
		 } );
	</script>
@stop
