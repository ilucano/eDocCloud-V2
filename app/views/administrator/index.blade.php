@extends('layout')

@section('content')

 
		<div class="col-lg-12">
			<h2 class="page-header">System Administration
			</h2>
			
		    <ol class="breadcrumb">
				<li>
					<i class="fa fa-dashboard"></i> System Administration
				</li>
				<li class="active">
					<i class="fa fa-users"></i> Manage System Administrators
				</li>
			</ol>
			
		</div>
 
 
 
	
		<div class="col-lg-12">
		
			<div class="alert alert-warning">
			<strong>Note:</strong> This section is to create <strong>System  Administrators</strong> of the entire eDocCloud system. Currently only users from
			<strong> {{ $adminCompanyName }}</strong>  can be chosen as system admin.<br/>
			If you would like to manage Company Administrator, please go to  <a href="{{ URL::to('user') }}">Manage Users</a>.
 			</div> 
		
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
						<th> Is System Admin</th>
						<th> Active</th>
						<th> Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach($users as $user)
					
					<tr>			
						<td>
							<strong>{{ $user->username }}</strong> 
							@if(strtolower($user->is_admin) == 'x')
								<abbr title="System Admin"><i class="fa fa-group" style="color:#d9534f"></i> </abbr> 
							@endif
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
							
						<td style="text-align: center;">
							@if(strtolower($user->is_admin) == 'x')
								 <span class="label label-success">Yes</span>
							@else
								<span class="label label-danger">No</span>
							@endif
							
						</td>
							
						<td style="text-align: center;">
							@if(strtolower($user->status) == 'x')
								 <span class="label label-success">Yes</span>
							@else
								<span class="label label-danger">No</span>
							@endif
							
						</td>
							
						<td>
							
							{{ Form::open(array('url' => 'administrator/isadmin')) }}
							{{ Form::hidden('row_id',  $user->row_id) }}
							@if(strtolower($user->is_admin) == 'x')
								{{ Form::hidden('is_admin', '') }}
								{{ Form::button('<i class="fa fa-times fa-lg"></i> Remove', array('type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'title' => 'Remove as admin')) }}
								
						    @else
								{{ Form::hidden('is_admin', 'X') }}
							    {{ Form::button('<i class="fa fa-check fa-lg"></i> Add', array('type' => 'submit', 'class' => 'btn btn-primary btn-sm', 'title' => 'Set as admin')) }}
							@endif
							{{ Form::close() }}
							
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
