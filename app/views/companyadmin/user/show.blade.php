@extends('layout')

@section('content')
        
		
		
		<div class="col-lg-12">
			<h2 class="page-header">{{ Auth::User()->getCompanyName() }}
			<small>Users</small>
			</h2>
			
		    <ol class="breadcrumb">
				<li>
					<i class="fa fa-dashboard"></i> Company Admin
				</li>
				<li>
					<i class="fa fa-users"></i> <a href="{{ URL::to('companyadmin/user') }}">Users</a>
				</li>
				
				<li class="active">
					<i class="fa fa-user"></i> {{ $user->first_name }} {{ $user->last_name }}
				</li>	
			</ol
			
		</div>

		
		<div class="col-lg-12">
			<h3>{{ $user->first_name }} {{ $user->last_name }} </h3>
		</div>
		<div class="col-lg-4">

			<div class="form-group ">
				<label>Username</label>
				{{ Form::text('username', $user->username, array('class'=>'form-control', 'disabled'=>'disabled')) }}
			</div>
				
			<div class="form-group">
				<label>Password</label>
				{{ Form::text('password', '******', array('class'=>'form-control', 'disabled'=>'disabled')) }}
			</div>
			
			<div class="form-group">
				<label>Email</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					{{ Form::text('email', $user->email, array('class'=>'form-control', 'disabled'=>'disabled')) }}
				 </div>
			</div>
		</div>
		
		<div class="col-lg-4">
		
		 
			
			<div class="form-group">
				<label>Phone</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-phone"></i></span>
					{{ Form::text('phone', $user->phone, array('class'=>'form-control', 'disabled'=>'disabled')) }}
				 </div>
			</div>
				
		    <div class="form-group">
				<label>Files Permission</label>
				 
				<div class="form-group">
				
				{{ Form::select('file_permission', $filemarkDropdown, json_decode($user->file_permission, true), array('class'=>'form-control', 'multiple'=>'multiple', 'id'=>'file_permission')) }}

				</div>
			</div>
				
				
			<div class="form-group">
				<label>User Role</label>
				 <div class="form-group">
				 
				{{ Form::select('assigned_roles[]', $roleDropdown, $assignedRoles, array('class'=>'form-control', 'multiple'=>'multiple', 'id'=>'assigned_roles')) }}
                </div>
			</div>
				
				
			
			<div class="form-group">
				<label>Company Admin</label>
				<?php
					$admin = (strtolower($user->company_admin) == 'x') ? 'Yes' : 'No';
				?>
				{{ Form::text('company_admin', $admin, array('class'=>'form-control', 'disabled'=>'disabled')) }}
				
			</div>
				
            
			<div class="form-group">
				<label>Active</label>
				<?php
					$active = (strtolower($user->status) == 'x') ? 'Yes' : 'No';
				?>
				{{ Form::text('status', $active, array('class'=>'form-control', 'disabled'=>'disabled')) }}
			</div>
				
			
			
				
			<div class="form-group pull-left">
				
				<a class="btn btn-sm btn-info" href="{{ URL::to('companyadmin/user') }}"><i class="fa fa-angle-double-left fa-lg"></i> Back</a>
			</div>
		
			<div class="form-group pull-right">
				<a class="btn btn-sm btn-warning" href="{{ URL::to('companyadmin/user/' . $user->row_id . '/edit') }}"><i class="fa fa-edit fa-lg"></i> Edit</a>
			</div>		
			
			 
		</div>		

	 
@stop



@section('loadjs')
	
	<script type="text/javascript">

		$(document).ready(function() {
			$('#file_permission').multiselect(
					{
						onChange: function(element, checked) {
							if(checked === true) {
								$("#file_permission").multiselect('deselect', element.val());
							}
							else if(checked === false) {
								$("#file_permission").multiselect('select', element.val());
							}
						}
			
					});
					
			
			$('#assigned_roles').multiselect(
					{
						onChange: function(element, checked) {
							if(checked === true) {
								$("#assigned_roles").multiselect('deselect', element.val());
							}
							else if(checked === false) {
								$("#assigned_roles").multiselect('select', element.val());
							}
							
						}
					});
					
		});

	</script>
@stop

