@extends('layout_nomenu')

@section('content')


<div class="row centered-form">
  <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
	<div class="panel panel-default">
	  <div class="panel-heading">
		<h3 class="panel-title">eDocCloud - Please Login</h3>
	  </div>
	  <div class="panel-body">
		{{ Form::open(array('url' => 'login')) }}

		  <div class="form-group">
		   {{ Form::text('username', null, array('class'=>'form-control input','placeholder'=>'Username')) }}
		  </div>

		  <div class="form-group">
		   {{ Form::password('password', array('class'=>'form-control input','placeholder'=>'Password')) }}
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

        