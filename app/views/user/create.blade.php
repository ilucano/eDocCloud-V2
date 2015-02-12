@extends('layout')

@section('content')

		<div class="col-sm-12">
			

			<h2 class="page-header">Site Admin 
			<small>Company</small>
			</h2>
				
		    <ol class="breadcrumb">
				<li>
					<i class="fa fa-dashboard"></i>  Site Admin 
				</li>
					
				<li class="active">
					<i class="fa fa-users"></i> <a href="{{ URL::to('user') }}">Users</a>
				</li>
 
				<li class="active">
					<i class="fa fa-user"></i> New User
				</li>
			</ol
		</div>
		
		{{ Form::open(array('url' => 'user')) }}
		
		<div class="col-lg-12">
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			<h3>New User</h3>
		</div>
		<div class="col-lg-4">
             
			<div class="form-group  @if ($errors->has('fk_empresa')) has-error @endif">
				<label>Select a company</label>
					
				{{ Form::select('fk_empresa', $userCompaniesDropdown, null, array('class'=>'form-control')) }}
				@if ($errors->has('fk_empresa')) <p class="help-block">{{ $errors->first('fk_empresa') }}</p> @endif
				
			</div>
				
			
			<div class="form-group pull-left">
					
			 <a class="btn btn-sm btn-info" href="{{ URL::to('user') }}"> Cancel</a>
			
			</div>
						
			<div class="form-group pull-right">
				    
					{{ Form::hidden('from_step', '1', array('id' => 'from_step')) }}
					
					{{ Form::submit('Next', array('class' => 'btn btn-sm btn-success')) }}
			</div>
						
 
		</div>
		 

       {{ Form::close() }}

@stop


