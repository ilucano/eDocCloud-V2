@extends('layout')

@section('content')

 
		<div class="col-lg-12">
			<h2 class="page-header">System Administration
			<small>Users</small>
			<div class="pull-right"><a class="btn btn-sm btn-primary" href="{{ URL::to('user/create') }}"><i class="fa fa-plus-circle fa-lg"></i> Create</a></div>
			</h2>
			
		    <ol class="breadcrumb">
				<li>
					<i class="fa fa-dashboard"></i> System Administration
				</li>
				<li class="active">
					<i class="fa fa-users"></i> Users
				</li>
			</ol>
			
		</div>
 
 
 
	
		<div class="col-lg-12">
			@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif
			
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			

			<table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-striped" id="datatables">
				<thead>
					<tr>
						<th><i class="fa fa-user"></i> Username</th>
						<th> Name</th>
						<th> Email</th>
						<th> Company</th>
						<th> Active</th>
						<th> Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach($users as $user)
					
					<tr>			
						<td>
							<strong>{{ $user->username }}</strong>
							@if(strtolower($user->company_admin) == 'x')
								<abbr title="Company Admin"><i class="fa fa-user" style="color:#337ab7"></i> </abbr> 
							@endif
						</td>
		                <td>
							{{ $user->first_name }} {{ $user->last_name }}
							
						</td>
						
						<td>
							{{ $user->email }}
						</td>
							
						<td>
							{{ $user->company_name }}
						</td>
		 
						<td style="text-align: center;">
							@if(strtolower($user->status) == 'x')
								 <span class="label label-success">Yes</span>
							@else
								<span class="label label-danger">No</span>
							@endif
							
						</td>				 
						<td style="white-space: nowrap;">
							<div class="pull-right">
								<a class="btn btn-sm btn-success" href="{{ URL::to('user/' . $user->row_id) }}">View</a>
								<a class="btn btn-sm btn-info" href="{{ URL::to('user/' . $user->row_id . '/edit') }}"><i class="fa fa-edit fa-lg"></i> Edit</a>
							</div>
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
			
			);
		 } );
	</script>
@stop
