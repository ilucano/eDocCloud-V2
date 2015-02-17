@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
		    
			<div class="pull-left"><a class="btn btn-sm btn-info" href="{{ URL::to('users/chart/'. $box->row_id. '/'.$order->row_id) }}"><i class="fa fa-caret-left fa-lg"></i> Back</a></div>
			
			<h2 class="page-header">Chart
			<small>Files</small>
			
			</h2>
			
			
			 <ol class="breadcrumb">
				<li>
					<i class="fa fa-fw fa-ticket"></i> <a  href="{{ URL::to('users/order') }}">Orders</a>
				</li>
				
				<li>
					<a  href="{{ URL::to('users/order/'. $order->row_id) }}"> {{ $order->f_code }} / {{ $order->f_name }} </a>
                </li>
					
				<li>
					<i class="fa fa-list"></i> <a href="{{ URL::to('users/chart/'. $box->row_id. '/'.$order->row_id) }}"> {{ $box->f_code }} /  {{ $box->f_name }} </a>
				</li>
                
				<li>
					<i class="fa fa-folder-open-o" class="active"></i> Show Files 
				</li>

				
			</ol>
			
			
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
						<th>Filename</th>
						<th>Mark</th>
						<th>Created</th>
						<th>Updated</th>
						<th>Pages</th>
						<th>Size</th>	
					</tr>
				</thead>
				<tbody>
				    
					
					@foreach($files as $file)
						<tr>
						
						<td>	
							<a class="btn btn-link" href="{{ URL::to('attachment/file/' . $file->row_id) }}">{{ $file->filename }} </a>
						</td>	
						
						<td>{{ Form::select('file_mark_id', $filemarkDropdown, $file->file_mark_id, array('class'=>'form-control bootstrap-dropdown', 'data-file-id'=>$file->row_id )) }}</td>
						<td>{{ Helpers::niceDateTime($file->creadate) }} </td>
						<td>{{ Helpers::niceDateTime($file->moddate) }} </td>
						<td>{{ $file->pages }} </td>
						<td>{{ Helpers::bytesToMegabytes( $file->filesize) }} </td>	
						</tr>
					@endforeach
 
				</tbody>
			</table>

		</div>
			
	</div>


	
	<div class="modal" id="loading-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
		  <div class="alert alert-warning">
			Please wait...
		  </div>
		</div>
	</div>
	
	
@stop


@section('loadjs')
	
	<script type="text/javascript">
		$(document).ready(function() {
		
			$('#datatables').DataTable(
				{
				}
			);
		 } );
	</script>
	
    <script type="text/javascript">

		$(document).ready(function() {
		
			$(".bootstrap-dropdown").unbind("click").bind("click", function () {
	
				mark_id = $(this).val();
				file_id = $(this).attr('data-file-id');
				$('#loading-modal').modal('toggle');
				$.ajax({
				   type: "GET",
				   url: "{{ URL::to('users/file/mark/') }}",
				   data: "id="+file_id+"&file_mark_id="+mark_id,
				   success: function(html){
					if(html != "")
					{
						$('#loading-modal').modal('toggle');
						 
					}
					else
					{
						$('#loading-modal').modal('toggle');
					}
				 }
				});
				
			});

			
		});

	</script>
		

@stop
