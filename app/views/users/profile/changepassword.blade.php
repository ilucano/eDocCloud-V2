@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">Profile
			<small></small>
			</h2>
			
			
			 <ol class="breadcrumb">
				<li>
					<i class="fa fa-fw fa-user"></i> Profile
				</li>
				<li class="active">
					<i class="fa fa-list"></i> Change Password
				</li>
			</ol>
			
			
		</div>
		
		<div class="col-lg-4">
		
		@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
		@endif

		@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
		@endif
		@if (Session::has('expired_reminder'))
				<div class="alert alert-danger">Your password has expired. Please change password in order to proceed. </div>
		@endif
		

		{{ Form::open(array('url' => 'users/profile/password')) }}
		
			<div class="form-group @if ($errors->has('password')) has-error @endif">
			
				<div class="form-group input-group">
				   <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
				   {{ Form::password('password', array('class'=>'form-control', 'placeholder'=>'New Password')) }}
				</div>
				@if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
			</div>
			
			<div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
				<div class="form-group input-group">
				   <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
				   {{ Form::password('password_confirmation',array('class'=>'form-control','placeholder'=>'Confirm New Password')) }}
				</div>
				@if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('confirm') }}</p> @endif
			</div>	
			
			{{ Form::submit('Change Password', array('class' => 'btn btn-sm btn-info')) }}
		</div>
			
		{{ Form::close() }}
	</div>
		
  

@stop
 
