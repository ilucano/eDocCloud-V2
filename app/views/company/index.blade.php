@extends('layout')

@section('content')

 
		<div class="col-lg-12">
			<h2 class="page-header">Site Admin 
			<small>Company</small>
			<div class="pull-right"><a class="btn btn-sm btn-primary" href="{{ URL::to('company/create') }}"><i class="fa fa-plus fa-lg"></i> Add Company</a></div>
			</h2>
			
		    <ol class="breadcrumb">
				<li>
					<i class="fa fa-dashboard"></i>  Site Admin 
				</li>
				<li class="active">
					<i class="fa fa-group"></i> Manage Company
				</li>
			</ol
			
		</div>
 
 
 
	
		<div class="col-lg-12">
			@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif
			
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			

			<table cellpadding="0" cellspacing="0" border="0" class="table table-hover small-font table-striped" id="datatables">
				<thead>
					<tr>
						<th><i class="fa fa-home"></i> Company</th>
						<th> Contact</th>
						<th><i class="fa fa-user"></i> Administrator</th>
						<th><i class="fa fa-globe"></i> Site Domain</th>
						<th>Action</th>		
					</tr>
				</thead>
				<tbody>
					
					@foreach($companies as $company)
					
					<tr>			
						<td valign="top">
							<strong>{{ $company->company_name }}</strong>
							<address>
							  {{ $company->company_address1 }}<br/>
							  {{ $company->company_address2 }} 
							  {{ $company->company_zip }}<br>
							</address>
						</td>
		 
						<td valign="top">
							@if($company->company_phone) <abbr title="Phone">P:</abbr>  {{ $company->company_phone }}<br> @endif
							@if($company->company_fax) <abbr title="Fax">F:</abbr>  {{ $company->company_fax }}<br> @endif
							@if($company->company_email) <abbr title="Email">E:</abbr> {{ $company->company_email }} @endif
						</td>	
					
						<td valign="top">{{ $company->admin_name }} ({{ $company->admin_username }})</td>
						<td valign="top">{{ $company->app_domain }}</td>		 
						<td nowrap>
							<div class="pull-right">
								<a class="btn btn-sm btn-success" href="{{ URL::to('company/' . $company->row_id) }}">View</a>
								<a class="btn btn-sm btn-info" href="{{ URL::to('company/' . $company->row_id . '/edit') }}"><i class="fa fa-edit fa-lg"></i> Edit</a>
							</div>
						</td>
					 
					</tr>
						
					@endforeach
 
				</tbody>
			</table>

		</div>
			
 

@stop


@section('loadjs')
	
	<script type="text/javascript">
		$(document).ready(function() {
		
			$('#datatables').DataTable(
			
			);
		 } );
	</script>
@stop
