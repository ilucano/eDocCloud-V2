@extends('layout')

@section('content')
        
		
		
		<div class="col-lg-12">
			<h2 class="page-header">System Administration
			<small>Users</small>
			</h2>
			
		    <ol class="breadcrumb">
				<li>
					<i class="fa fa-dashboard"></i> System Administration
				</li>
				<li>
					<i class="fa fa-users"></i> <a href="{{ URL::to('user') }}">Users</a>
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
			
			<div class="form-group">
				<label>Company Name</label>
				{{ Form::text('company_name',$user->company_name, array('class'=>'form-control', 'disabled'=>'disabled')) }}
			</div>

			<div class="form-group">
				<label>Phone</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-phone"></i></span>
					{{ Form::text('phone', $user->phone, array('class'=>'form-control', 'disabled'=>'disabled')) }}
				 </div>
			</div>
				
		
            
		</div>
		
		<div class="col-lg-4">
		   
		
			<div class="form-group">
				<label>User Group</label>
					
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					{{ Form::text('group_name',$user->group_name , array('class'=>'form-control', 'disabled'=>'disabled')) }}
				 </div>

			</div>
			
			<div class="form-group">
				<label>Is Company Admin</label>
				<?php
					$admin = (strtolower($user->company_admin) == 'x') ? 'Yes' : 'No';
				?>
				{{ Form::text('company_admin', $admin, array('class'=>'form-control', 'disabled'=>'disabled')) }}
				
			</div>
				
			<div class="form-group">
				<label>Is System Admin</label>
				<?php
					$admin = (strtolower($user->is_admin) == 'x') ? 'Yes' : 'No';
				?>
				{{ Form::text('system_admin', $admin, array('class'=>'form-control', 'disabled'=>'disabled')) }}
				
			</div>
				
				<div class="form-group pull-left">
				
				<a class="btn btn-sm btn-info" href="{{ URL::to('user') }}"><i class="fa fa-angle-double-left fa-lg"></i> Back</a>
			</div>
				
			<div class="form-group pull-right">
				<a class="btn btn-sm btn-warning" href="{{ URL::to('user/' . $user->row_id . '/edit') }}"><i class="fa fa-edit fa-lg"></i> Edit</a>
			</div>
					
			
			 
		</div>		
 


@stop


