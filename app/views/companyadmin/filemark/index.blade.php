@extends('layout')

@section('content')

 
		<div class="col-lg-10">
			<h2 class="page-header">{{ Auth::User()->getCompanyName() }}
			<small>Filemarks</small>
			<div class="pull-right"><a class="btn btn-sm btn-success" href="{{ URL::to('companyadmin/filemark/create') }}"><i class="fa fa-plus-circle fa-lg"></i> Create New Label</a></div>
			</h2>
			
		    <ol class="breadcrumb">
				<li>
					<i class="fa fa-fw fa-dashboard"></i> Company Admin
				</li>
				<li class="active">
					<i class="fa fa-tag"></i> Filemarks
				</li>
			</ol>
			
		</div>
 
 
 
	
		<div class="col-lg-10">
			@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif
			
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			

			<table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-striped" id="datatables">
				<thead>
					<tr>
						<th><i class="fa fa-tag"></i> Label</th>
						<th> Created Date</th>
						<th> Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach($filemarks as $filemark)
					
					<tr>			
						<td>
							 {{ $filemark->label }} <span class="badge">{{ $filemark->file_count }} files</span>
							 
						</td>
		                <td>
							{{ date("F j, Y",strtotime($filemark->create_date)) }}
						</td>
						
						<td>
							@if($filemark->global == '0')
								<a class="btn btn-sm btn-info" href="{{ URL::to('companyadmin/filemark/' . $filemark->id . '/edit') }}"><i class="fa fa-edit fa-lg"></i> Rename</a>
							@else
								<button type="button" class="btn btn-default btn-sm" disabled="disabled">(Built-in label)</button>
							@endif
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
				{ "order": [[ 2, "desc" ]] }
			);
		 } );
	</script>
@stop
