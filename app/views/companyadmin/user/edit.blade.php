@extends('layout')

@section('content')

		<div class="col-sm-12">
			

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
		   </ol>
			
		</div>
		
		{{ Form::model($user, array('route' => array('companyadmin.user.update', $user->row_id), 'method' => 'PUT')) }}

		
		<div class="col-lg-12">
 
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
 
			<h3>{{ $user->first_name }} {{ $user->last_name }} </h3>
		</div>
		<div class="col-lg-4">
             
			<div class="form-group @if ($errors->has('username')) has-error @endif">
				<label>Username</label>
				{{ Form::text('username',$user->username, array('class'=>'form-control', 'disabled' => 'disabled')) }}
				
				
				@if ($errors->has('username')) <p class="help-block">{{ $errors->first('username') }}</p> @endif
					
			</div>
			
			<div class="form-group @if ($errors->has('password')) has-error @endif">
				<label>Password</label>
				{{ Form::password('password', array('class'=>'form-control', 'placeholder' => 'Enter New Password If Change')) }}
					
				@if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
				
			</div>
				
				
			<div class="form-group @if ($errors->has('first_name')) has-error @endif">
				<label>First Name</label>
				{{ Form::text('first_name',$user->first_name, array('class'=>'form-control')) }}
				@if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
			</div>
				
			
			<div class="form-group @if ($errors->has('last_name')) has-error @endif">
				<label>Last Name</label>
				{{ Form::text('last_name',$user->last_name, array('class'=>'form-control')) }}
				@if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
			</div>
				
			
		 
		
			
			
		</div>
		
		<div class="col-lg-4">
		   
			<div class="form-group">
				<label>Phone</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-phone"></i></span>
					{{ Form::text('phone',$user->company_phone, array('class'=>'form-control')) }}
				 </div>
			</div>
			
			<div class="form-group @if ($errors->has('email')) has-error @endif">
				<label>Email</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					{{ Form::text('email', $user->email, array('class'=>'form-control')) }}
				 </div>
				
				 @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
			</div>
				
				
			
			<!-- Only ImagingXperts User can be system admin -->
	
			
			<div class="form-group">
				<label>Company Admin</label>
				 <div class="form-group input-group">
				 <span class="input-group-addon"><i class="fa fa-user"></i></span>
				{{ Form::select('company_admin', $companyAdminDropdown, strtoupper($user->company_admin), array('class'=>'form-control')) }}
                </div>
			</div>
			
					
			<div class="form-group">
				<label>User Group</label>
				 <div class="form-group input-group">
				 <span class="input-group-addon"><i class="fa fa-users"></i></span>
				{{ Form::select('group_id', $userGroupsDropdown, strtoupper($user->group_id), array('class'=>'form-control')) }}
                </div>
			</div>
				
			<div class="form-group">
				<label>Active</label>
				 <div class="form-group input-group">
				 <span class="input-group-addon"><i class="fa fa-check"></i></span>
				{{ Form::select('status', $activeDropDown, strtoupper($user->status), array('class'=>'form-control')) }}
                </div>
			</div>
		 	
		   <div class="form-group pull-left">
					
					<a class="btn btn-sm btn-info" href="{{ URL::to('companyadmin/user/'. $user->row_id ) }}"> Cancel</a>
			</div>
						
					<div class="form-group pull-right">
							
					{{ Form::submit('Save', array('class' => 'btn btn-sm btn-success')) }}
					</div>
			</div>		
		
 

       {{ Form::close() }}

@stop


