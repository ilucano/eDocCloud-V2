@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">My Orders
			<small>List</small>
			</h2>
			
			
			 <ol class="breadcrumb">
				<li>
					<i class="fa fa-fw fa-ticket"></i> Orders
				</li>
				<li class="active">
					<i class="fa fa-list"></i> Order List
				</li>
			</ol>
			
			
		</div>
	</div>
		
 
	<div class="row">
		
		<div class='clearfix'></div>

		<div class="col-lg-8">
			<!-- search filter -->
			<p>
				<button class="btn btn-sm btn-default" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
				  Filters
				   <span class="caret"></span>
				</button>
			</p>
			<div class="collapse @if ($filterExpand) in @endif" id="collapseExample">
			  <div class="well small-font">
			  		{{ Form::open(array('route' => 'users.order.index', 'method' => 'get')) }}
			  		@include('partials.metaattribute.filter', array('attributeSets' => $attributeFilters))
			  		<a class="btn btn-sm btn-info" href="{{ URL::to('users/order') }}"> Clear</a>
			  		{{ Form::submit('Search', array('class' => 'btn btn-sm btn-primary')) }}
			  		{{ Form::close() }}
			  </div>
			</div>
		</div>

		<div class="col-lg-12" style="overflow: auto;">

			@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif
			
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			

			<table cellpadding="0" cellspacing="0" border="0" class="display table table-condensed small-font" id="datatables">
				<thead>
					<tr>
						<th>Order</th>
						<th>Order Date</th>
						<th>Status</th>
						<th>Pages</th>
						<th>Cost</th>
						@foreach ($companyAttributeHeaders as $header)
								<th>{{ $header }}</th>
						@endforeach
						<th>Action</th>	
					</tr>
				</thead>
				<tbody>
					
					@foreach($objects as $object)
					
					<tr>			
						<td nowrap>{{ $object->f_code }} / {{ $object->f_name }}</td>
						<td>{{ Helpers::niceDateTime($object->creation) }}</td>
						<td>{{ $object->status }}</td>			
						<td>{{ $object->qty }}</td>
						<td>{{ $object->price }}</td>
						@foreach ($companyAttributeHeaders as $_attributeId => $header)
							@if(isset($object->attributeValues[$_attributeId]))
							<td>{{  implode(", ", $object->attributeValues[$_attributeId]) }}</td>
							@else
							<td> </td>
							@endif
						@endforeach
						<td style="white-space: no-wrap;" nowrap>
						 
									<a class="btn btn-sm btn-success" href="{{ URL::to('users/order/' . $object->row_id) }}"><i class="fa fa-caret-right fa-lg"></i> View</a> 

									<a class="btn btn-sm btn-info" href="{{ URL::to('users/order/attributes/' . $object->row_id . '/edit') }}" data-toggle="modal" data-target="#attributeModal"> <i class="fa fa-edit fa-lg"></i> Attributes </a>
				 		
					    </td>
					 
					</tr>
						
					@endforeach
 
				</tbody>
			</table>

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
		         
		      </div>
		      <div class="modal-body">
		        
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
		
			$('#datatables').DataTable(
				{
				   "aaSorting": [[ 5, "asc" ]],
				   stateSave: true
				}
			);
		 } );
	</script>
@stop
