@extends('layout')

@section('content')

		<div class="col-sm-12">
			

			<h2 class="page-header">{{ Auth::User()->getCompanyName() }}
			<small>User Roles</small>
			</h2>
				
		    <ol class="breadcrumb">
				<li>
					<i class="fa fa-dashboard"></i>  Company Admin
				</li>
					
				<li class="active">
					<i class="fa fa-tags"></i> <a href="{{ URL::to('companyadmin/role') }}">User Roles</a>
				</li>
 
				<li class="active">
					<i class="fa fa-plus"></i> New Role
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
			
		<div class="clearfix"></div>
		
		{{ Form::open(array('url' => 'companyadmin/role')) }}
				
			<div class="col-lg-4">
				
				
			
				
				<div class="form-group @if ($errors->has('name')) has-error @endif">
					<label>Role Name</label>
					{{ Form::text('name',Input::old('name'), array('class'=>'form-control')) }}
					@if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
				</div>
					
			
			    <div class="form-group @if ($errors->has('permission')) has-error @endif">
					<label>Role Permission</label>
					
					
					
					@if ($errors->has('permission')) <p class="help-block">{{ $errors->first('permission') }}</p> @endif					

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
								    
									@foreach($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->display_name }}</td>
                                        <td></td>
                                        
                                    </tr>
									@endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
						

					
				{{ Form::submit('Create', array('class' => 'btn btn-sm btn-success')) }}

			</div>		
	    
		{{ Form::close() }}
@stop


