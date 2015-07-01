@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">Files
			<small>Search</small>
			</h2>
			
			
			 <ol class="breadcrumb">
				<li>
					<i class="fa fa-fw fa-folder"></i> Files
				</li>
				<li class="active">
					<i class="fa fa-search"></i> Search
				</li>
			</ol>
			
			
		</div>
	</div>
		
	<div class="row">
	
		<div class="col-lg-6">

			@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif
			
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			
			{{ Form::open(array('url' => 'users/file/query', 'method' => 'get')) }}
			
			<div class="form-group input-group @if ($errors->has('query')) has-error @endif">
			    {{ Form::text('query', Input::old('query'), array('class'=>'form-control', 'placeholder'=>'Enter filename or text to search')) }}

                <span class="input-group-btn"><button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button></span>
				  
			</div>
            @if ($errors->has('query')) <p class="help-block">{{ $errors->first('query') }}</p> @endif  
		   


			<div class='clearfix'></div>
 
			<!-- search filter -->
			<p>
				<button class="btn btn-sm btn-default" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
				  Filters
				   <span class="caret"></span>
				</button>
			</p>
			<div class="collapse @if ($filterExpand) in @endif" id="collapseExample">
			  <div class="well small-font">
			  		@include('partials.metaattribute.filter', array('attributeSets' => $attributeFilters))
			  		
			  		<div class="form-group">
			  			Display Results: {{ Form::select('limit', ['50'=> '50', '200' => '200', '500' => '500', '1000' => '1000',  '99999999' =>  '> 1000'], Input::get('limit')) }}
			  		</div>
			  		
			  </div>
			</div>

		   {{ Form::close() }}
		</div>
			
	</div>

@stop


@section('loadjs')
	 
@stop
