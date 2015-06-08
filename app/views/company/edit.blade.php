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
					<i class="fa fa-group"></i> <a href="{{ URL::to('company') }}"> Manage Company</a>
				</li>
					
				<li class="active">
					<i class="fa fa-home"></i> <a href="{{ URL::to('company/'.$company->row_id) }}"> {{ $company->company_name }}</a>
				</li>
				
				<li class="active">
					<i class="fa fa-edit"></i> Edit
				</li>
			</ol
		</div>
		
		{{ Form::model($company, array('route' => array('company.update', $company->row_id), 'method' => 'PUT')) }}

		
		<div class="col-lg-12">
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			<h3>{{ $company->company_name }}</h3>
		</div>
		<div class="col-lg-4">
             
			<div class="form-group @if ($errors->has('company_name')) has-error @endif">
				<label>Company Name</label>
				{{ Form::text('company_name',$company->company_name, array('class'=>'form-control')) }}
				
				
				@if ($errors->has('company_name')) <p class="help-block">{{ $errors->first('company_name') }}</p> @endif
					
			</div>
				
			<div class="form-group ">
				<label>Address 1</label>
				{{ Form::text('company_address1',$company->company_address1, array('class'=>'form-control')) }}
			
			</div>
				
			<div class="form-group">
				<label>Address 2</label>
				{{ Form::text('company_address2',$company->company_address2, array('class'=>'form-control')) }}
			</div>
			<div class="form-group @if ($errors->has('company_zip')) has-error @endif">
				<label>Zip</label>
				{{ Form::text('company_zip',$company->company_zip, array('class'=>'form-control')) }}
				@if ($errors->has('company_zip')) <p class="help-block">{{ $errors->first('company_zip') }}</p> @endif
				
			</div>
        
			<div class="form-group">
				<label>Administrator</label>
					
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
						
					{{ Form::select('fk_admin', $companyUsersDropdown, $company->fk_admin, array('class'=>'form-control')) }}

				 </div>

			</div>
			

			
		</div>
		
		<div class="col-lg-4">
		   
			<div class="form-group">
				<label>Phone</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-phone"></i></span>
					{{ Form::text('company_phone',$company->company_phone, array('class'=>'form-control')) }}
				 </div>
			</div>
				
			<div class="form-group">
				<label>Fax</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-fax"></i></span>
					{{ Form::text('company_fax',$company->company_fax, array('class'=>'form-control')) }}
				 </div>
			</div>
	 
		    <div class="form-group @if ($errors->has('company_email')) has-error @endif">
				<label>Email</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					{{ Form::text('company_email',$company->company_email, array('class'=>'form-control')) }}

				 </div>
				@if ($errors->has('company_email')) <p class="help-block">{{ $errors->first('company_email') }}</p> @endif
			</div>
				
			
			 <div class="form-group @if ($errors->has('fk_terms')) has-error @endif">
				<label>Terms</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					{{ Form::text('fk_terms',$company->fk_terms, array('class'=>'form-control')) }}
					
					
				 </div>
				
				@if ($errors->has('fk_terms')) <p class="help-block">{{ $errors->first('fk_terms') }}</p> @endif
				
			</div>
			
			 <div class="form-group @if ($errors->has('creditlimit')) has-error @endif">
				<label>Credit Limit</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
					{{ Form::text('creditlimit',$company->creditlimit, array('class'=>'form-control')) }}
					<span class="input-group-addon">.00</span>
					
				 </div>
				
				@if ($errors->has('creditlimit')) <p class="help-block">{{ $errors->first('creditlimit') }}</p> @endif
				
			</div>
		   
		</div>		
		
        
		<div class="clearfix"></div>
			
			<div class="col-lg-8">
					<div class="form-group pull-left">
					
					<a class="btn btn-sm btn-info" href="{{ URL::to('company/'. $company->row_id ) }}"> Cancel</a>
					</div>
						
					<div class="form-group pull-right">
							
					{{ Form::submit('Save', array('class' => 'btn btn-sm btn-success')) }}
					</div>
			</div>

       {{ Form::close() }}

@stop


