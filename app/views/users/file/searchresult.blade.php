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
					<i class="fa fa-search"></i> Search Results
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
			
			{{ Form::open(array('url' => 'users/file/search')) }}
			
			<div class="form-group input-group @if ($errors->has('query')) has-error @endif">
			    {{ Form::text('query', $query, array('class'=>'form-control', 'placeholder'=>'Enter filename or text to search')) }}

                <span class="input-group-btn"><button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button></span>
				  
			</div>
            @if ($errors->has('query')) <p class="help-block">{{ $errors->first('query') }}</p> @endif  
		   {{ Form::close() }}
		</div>
		

		
		<div class="col-lg-12">
		
			<table cellpadding="0" cellspacing="0" border="0" class="display table table-condensed" id="datatables">
					<thead>
						<tr>
							<th><i class="fa fa-download" title="Select to download"></i> </th>
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
								{{ Form::checkbox('tozip', $file->row_id, null, ['class' => 'checkbox-zip']) }}
							</td>
							<td>	
								<a class="btn btn-link" target="_blank" href="{{ URL::to('pdfviewer') }}?file={{ URL::to('attachment/file/' . $file->row_id) }}">{{ $file->filename }} </a>
						    </td>
							<td> {{ Form::select('file_mark_id', $filemarkDropdown, $file->file_mark_id, array('class'=>'form-control bootstrap-dropdown', 'data-file-id'=>$file->row_id )) }}</td>
							<td>{{ Helpers::niceDateTime($file->creadate) }} </td>
							<td>{{ Helpers::niceDateTime($file->moddate) }} </td>
							<td>{{ $file->pages }} </td>
							<td>{{ Helpers::bytesToMegabytes( $file->filesize) }} </td>	
							</tr>
						@endforeach
	 
					</tbody>
				</table>
				{{ Form::open(array('url' => 'attachment/fileszip')) }}
				{{ Form::hidden('ziplist', '',  array('id' => 'ziplist')) }}
				{{ Form::submit('Download Selected File', array('class' => 'btn btn-success btn-sm', 'id' => 'download-button', 'disabled' => 'disabled')) }}
				{{ Form::close() }}	
		</div>		

	</div>
   
  

	<div class="modal" id="loading-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
		  <div class="alert alert-warning">
			<i class="fa fa-clock-o fa-fw"></i> Please wait...
		  </div>
		</div>
	</div>
	
	
	
	
@stop


@section('loadjs')
	
	<script type="text/javascript">
		$(document).ready(function() {
		
			var datatable = $('#datatables').DataTable(
				{
					"aaSorting": []
				}
			);

		 } );
	</script>
	
	
    <script type="text/javascript">

		$(document).ready(function() {
		
			//$("#datatables .bootstrap-dropdown").unbind("click").bind("click", function () {
	        $(document).on('click', '.bootstrap-dropdown', function(){
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
					
						setTimeout(function() {
							$('#loading-modal').modal('toggle');
						}, 1000); // milliseconds
	
					}
					else
					{
						$('#loading-modal').modal('toggle');
						alert('Error updating');
					}
				 }
				});
				
			});

			
		});

        

		$(document).ready(function() {
			
			$(document).on('click', '.checkbox-zip', function(){
				var id =  $(this).val();
				var isChecked = $(this).prop('checked');
				
				if ($("#ziplist").val() != '') {
 					var selectedArray = $("#ziplist").val().split(',');
 				}
 				else {
 					var selectedArray = [];
 				}

                if (isChecked == true) {
					selectedArray.push(id);
                }
                else {	

                	var found = $.inArray(id, selectedArray);
					if (found >= 0) {
					    // Element was found, remove it.
					    selectedArray.splice(found, 1);
					} 
                } 

                $("#ziplist").val(selectedArray.join(','));
                if(selectedArray.length >= 1) {
                	$("#download-button").val('Download Selected ' + selectedArray.length  + ' File(s)');
                	$("#download-button").removeAttr("disabled");
                }
                else {
                	$("#download-button").val('Download Selected File');
                	$("#download-button").attr("disabled", true);
                }
			}); 	
		});

	</script>
		

@stop
