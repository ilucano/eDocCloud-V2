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
					<i class="fa fa-users"></i> <a href="{{ URL::to('companyadmin/role') }}">User Roles</a>
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
				
			<div class="col-lg-6">
				
				
			
				
				<div class="form-group @if ($errors->has('name')) has-error @endif">
					<label>Role Name</label>
					{{ Form::text('name',Input::old('name'), array('class'=>'form-control','placeholder'=>'e.g. Sales Dept')) }}
					@if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
				</div>
					
			
			    <div class="form-group @if ($errors->has('permission_list')) has-error @endif">
					<label>Role Permission</label>
                        
						@if ($errors->has('permission_list')) <p class="help-block">{{ $errors->first('permission_list') }}</p> @endif
							
                        <div class="table-responsive">
                            <table class="table table-hover" id="permission-table">
                                <tbody>
								    
									@foreach($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->display_name }}</td>
                                        <td>
										    
											{{ Form::checkbox('permission_list[]', $permission->id, null, ['data-style'=>'btn-group-xs']) }}
											
										</td>
                                        
                                    </tr>
									@endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
						

				 <div class="form-group pull-left">
					<a class="btn btn-sm btn-info" href="{{ URL::to('companyadmin/role') }}"> Back</a>
				</div>
							
				<div class="form-group pull-right">	
					{{ Form::submit('Create', array('class' => 'btn btn-sm btn-success')) }}
				</div>	
		 

			</div>		
	    
		{{ Form::close() }}
@stop




@section('loadjs')
	
	<script type="text/javascript">

		$(function() {
				$('#permission-table input[type="checkbox"]').checkboxpicker({
				});
		});

	</script>
@stop

