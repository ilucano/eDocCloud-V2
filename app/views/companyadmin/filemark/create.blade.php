@extends('layout')

@section('content')

		<div class="col-sm-12">
			

			<h2 class="page-header">{{ Auth::User()->getCompanyName() }}
			<small>Filemark</small>
			</h2>
				
		    <ol class="breadcrumb">
				<li>
					<i class="fa fa-dashboard"></i>  Company Admin
				</li>
					
				<li class="active">
					<i class="fa fa-tags"></i> <a href="{{ URL::to('companyadmin/filemark') }}">Filemarks</a>
				</li>
 
				<li class="active">
					<i class="fa fa-tag"></i> New Filemark
				</li>
			</ol
		</div>
		
		<div class="col-lg-8">
 
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			
			@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif
		</div>
			
		
		
		<div class="col-lg-8">
            
			{{ Form::open(array('url' => 'companyadmin/filemark', 'class' => 'form-inline')) }}
		
		
			<div class="form-group input-group @if ($errors->has('label')) has-error @endif">
			
			 
				<span class="input-group-addon"><i class="fa fa-tag"></i></span>
			    
				{{ Form::text('label', Input::old('label'), array('class'=>'form-control', 'placeholder'=>'Enter label')) }}
				
				
					
			</div>
			{{ Form::submit('Create', array('class' => 'btn btn-sm btn-success')) }}
			@if ($errors->has('label')) <p class="help-block">{{ $errors->first('label') }}</p> @endif
			
			
				
			{{ Form::close() }}

		</div>		
	
@stop


