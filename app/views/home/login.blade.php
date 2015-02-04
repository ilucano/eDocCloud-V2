@extends('layout_nomenu')

@section('content')


<div class="row centered-form">
  <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
	<div class="panel panel-default">
	
	  

	  <div class="panel-heading">
		<h3 class="panel-title">Welcome to eDocCloud</h3>
	  </div>
	  <div class="panel-body">
        
		<!-- will be used to show any messages -->
		@if (Session::has('error'))
			<div class="alert alert-danger">{{ Session::get('error') }}</div>
		@endif

		{{ Form::open(array('url' => 'login')) }}

		<div class="form-group @if ($errors->has('username')) has-error @endif">
			{{ Form::text('username', null, array('class'=>'form-control input','placeholder'=>'Username')) }}
		   
			@if ($errors->has('username')) <p class="help-block">{{ $errors->first('username') }}</p> @endif
		   
		</div>

		<div class="form-group @if ($errors->has('password')) has-error @endif">
			{{ Form::password('password', array('class'=>'form-control input','placeholder'=>'Password')) }}
			@if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
		</div>
           
			{{ Form::submit('Login', array('class'=>'btn btn-info btn-block')) }}

		{{ Form::close() }}
	  </div>
	</div>
	<div class="text-center">
	  &copy; ImagingXperts <?php echo date("Y"); ?>
	</div>
  </div>
</div>

@stop 