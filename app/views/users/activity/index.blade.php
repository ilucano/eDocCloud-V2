@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">Users
			<small>Activity History</small>
			</h2>
			
			
			 <ol class="breadcrumb">
				<li>
					<i class="fa fa-fw fa-user"></i> User
				</li>
				<li class="active">
					<i class="fa fa-clock-o"></i>  Activity History
				</li>
			</ol>
			
			@if(Auth::User()->isCompanyAdmin())
				<div class="alert alert-info alert-dismissable">
                    <i class="fa fa-info-circle"></i>  <strong>Note:</strong> As a company admin, you are viewing activities of all company users for past 3 months.
				</div>	
			@endif
         
		</div>
	</div>
		
 
	<div class="row">
	
		<div class="col-lg-12">

			@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif
			
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			

			<table cellpadding="0" cellspacing="0" border="0" class="display table table-condensed" id="datatables">
				<thead>
					<tr>
						<th>#</th>
						<th>Username</th>
						<th>Event</th>
						<th>Details (to do more)</th>
						<th>IP Address</th>
						<th>Date Time</th>
						<th>Past</th>
					</tr>
				</thead>
				
				<tbody>
					
					@foreach($activityLogs as $activityLog)
					
					<tr>
						<td>
							 {{ $activityLog->id }} 
						</td>
						<td>
							 {{ $activityLog->username }} 
						</td>
						<td>
							 {{ $activityLog->description }} 
						</td>
						<td>
							 {{ $activityLog->details }}
							 / {{ $activityLog->detailsText }} 
						</td>
						<td>
							 {{ $activityLog->ip_address }} 
						</td>
		                <td>
							{{ Helpers::niceDateTime($activityLog->created_at)}}
						</td>
						
						<td>
							{{ Helpers::timeAgo(strtotime($activityLog->created_at)) }} ago
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
		
			$('#datatables').DataTable(
				{
				  "order": [[ 0, "desc" ]]
				}
			);
		 } );
	</script>
@stop
