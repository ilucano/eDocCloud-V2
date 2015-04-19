@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">File Browser
			<small>{{ Auth::User()->getCompanyName() }}</small>
			</h2>


			 <ol class="breadcrumb">
				<li>
					<i class="fa fa-fw fa-folder"></i> Files
				</li>
				<li class="active">
					<i class="fa fa-search"></i> Browse Files
				</li>
			</ol>
		</div>
	</div>

	<div class="row">

		<div class="col-lg-6">
			<!-- message div -->
			@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif

			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
		</div>

		<div class='clearfix'></div>

		<div class="col-lg-8">
			<!-- search filter -->
			<p>
				<button class="btn btn-sm btn-default" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
				  Filters
				   <span class="caret"></span>
				</button>
			</p>
			<div class="collapse" id="collapseExample">
			  <div class="well">
			  		{{ Form::open(array('route' => 'users.file.index', 'method' => 'get')) }}
			  		@include('partials.metaattribute.filter', array('attributeSets' => $attributeFilters))
			  		
			  		<div class="form-group">
			  			Display Results: {{ Form::select('limit', ['50'=> '50', '200' => '200', '500' => '500', '1000' => '1000',  '99999999' =>  '> 1000'], Input::get('limit')) }}
			  		</div>
			  		{{ Form::submit('Search', array('class' => 'btn btn-sm btn-primary')) }}
			  		{{ Form::close() }}
			  </div>
			</div>
		</div>



		<div class="col-lg-12">

			<table cellpadding="0" cellspacing="0" border="0" class="display table table-condensed small-font" id="datatables">
					<thead>
						<tr>
							<th><i class="fa fa-download" title="Select to download"></i> </th>
							<th>Filename</th>
							<th>Mark</th>
							<th>Created</th>
							<th>Pages</th>
							<th>Size</th>
							<th>Action</th>
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
							<td> {{ Helpers::niceDateTime($file->creadate) }} </td>
							<td> {{ $file->pages }} </td>
							<td> {{ Helpers::bytesToMegabytes( $file->filesize) }} </td>
							<td> <a class="btn btn-sm btn-info" href="{{ URL::to('users/file/attributes/' . $file->row_id . '/edit') }}" data-toggle="modal" data-target="#attributeModal"> <i class="fa fa-edit fa-lg"></i> Attributes </a>  </td>
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

	<style>

		#attributeModal .modal-content
		{
		  height: 600px;
		  width: 700px;
		  overflow:auto;
		}

	</style>
		

		<div class="modal fade" id="attributeModal">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title">Modal title</h4>
		      </div>
		      <div class="modal-body">
		        <p>One fine body&hellip;</p>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary">Save changes</button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->


@stop


@section('loadjs')

	<script type="text/javascript">
		$(document).ready(function() {

			var datatable = $('#datatables').DataTable(
				{
					"aaSorting": [],
					stateSave: true
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
