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
					<i class="fa fa-group"></i> <a href="{{ URL::to('company') }}">Manage Company</a>
				</li>
					
				<li class="active">
					<i class="fa fa-home"></i> {{ $company->company_name }}
				</li>
				
			</ol
		</div>
		
		<div class="col-lg-12">
			<h3>{{ $company->company_name }}</h3>
		</div>
		<div class="col-lg-4">

			<div class="form-group ">
				<label>Address 1</label>
				{{ Form::text('company_address1',$company->company_address1, array('class'=>'form-control', 'disabled'=>'disabled')) }}
			</div>
				
			<div class="form-group">
				<label>Address 2</label>
				{{ Form::text('company_address2',$company->company_address2, array('class'=>'form-control', 'disabled'=>'disabled')) }}
			</div>
			<div class="form-group">
				<label>Zip</label>
				{{ Form::text('company_zip',$company->company_zip, array('class'=>'form-control', 'disabled'=>'disabled')) }}
			</div>
        
			<div class="form-group">
				<label>Administrator</label>
					
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					{{ Form::text('company_admin',$company->admin_name . '(' . $company->admin_username . ')', array('class'=>'form-control', 'disabled'=>'disabled')) }}
				 </div>

			</div>
			
			
		</div>
		
		<div class="col-lg-4">
		   
			<div class="form-group">
				<label>Phone</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-phone"></i></span>
					{{ Form::text('company_phone',$company->company_phone, array('class'=>'form-control', 'disabled'=>'disabled')) }}
				 </div>
			</div>
				
			<div class="form-group">
				<label>Fax</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-fax"></i></span>
					{{ Form::text('company_fax',$company->company_fax, array('class'=>'form-control', 'disabled'=>'disabled')) }}
				 </div>
			</div>
	 
		    <div class="form-group">
				<label>Email</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					{{ Form::text('company_email',$company->company_email, array('class'=>'form-control', 'disabled'=>'disabled')) }}
				 </div>
			</div>
				
			
			 <div class="form-group">
				<label>Terms</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					{{ Form::text('fk_terms',$company->fk_terms, array('class'=>'form-control', 'disabled'=>'disabled')) }}
				 </div>
			</div>
			
			 <div class="form-group">
				<label>Credit Limit</label>
				 <div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
					{{ Form::text('creditlimit',$company->creditlimit, array('class'=>'form-control', 'disabled'=>'disabled')) }}
					<span class="input-group-addon">.00</span>
				 </div>
			</div>
		   
		</div>		
		

			<div class="col-lg-8">
				<div class="form-group pull-left">
				
				<a class="btn btn-info" href="{{ URL::to('company') }}"><i class="fa fa-angle-double-left fa-lg"></i> Back</a>
				</div>	
				<div class="form-group pull-right">
				<a class="btn btn-warning" href="{{ URL::to('company/' . $company->row_id . '/edit') }}"><i class="fa fa-edit fa-lg"></i> Edit</a>
				</div>
			</div>


@stop


