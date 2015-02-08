@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">Workflow 
			<small>Scan</small>
			</h2>
		</div>
	</div>
		
 
	<div class="row">
	
		<div class="col-lg-10">

			@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif
			
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			

			<table cellpadding="0" cellspacing="0" border="0" class="display" id="preparations">
				<thead>
					<tr>
						<th>Box</th>
						<th>Customer / Company</th>
						<th>Status</th>
						<th>Attachment</th>
						<th>Action</th>						
					</tr>
				</thead>
				<tbody>
					
					@foreach($workflows as $workflow)
					
					<tr>			
						<td>{{ substr($workflow->wf_id, 6) }}</td>
						<td>{{ $workflow->company_name }}</td>
						<td>{{ $workflow->status }}</td>			
						<td><a class="btn btn-link" href="{{ URL::to('attachment/download/' . $workflow->attach->row_id) }}">{{ $workflow->attach->attach_name }}</a></td>
						<td>{{ Form::open(array('url' => 'scan/status')) }}
							{{ Form::hidden('wfid',  $workflow->row_id) }}
							@if($workflow->fk_status == '6')
								{{ Form::hidden('status', '6') }}
								{{ Form::submit('Start Scan', array('class' => 'btn btn-primary btn-sm')) }} 
						    @else
								{{ Form::hidden('status', '7') }}
							    {{ Form::submit('End Scan', array('class' => 'btn btn-danger btn-sm')) }}
							@endif
							{{ Form::close() }}
					    </td>
					 
					</tr>
						
					@endforeach
 
				</tbody>
			</table>

		</div>
			
	</div>

@stop


@section('loadjs')
	
	<script type="text/javascript">
		$(document).ready(function() {
		
			$('#preparations').DataTable(
				{
				   "aaSorting": [[ 2, "desc" ], [ 0 , "asc" ]],
				}
			);
		 } );
	</script>
@stop
