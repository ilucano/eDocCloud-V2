@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
		    
			<div class="pull-left"><a class="btn btn-sm btn-info" href="{{ URL::to('users/order') }}"><i class="fa fa-caret-left fa-lg"></i> Back</a></div>
			
			<h2 class="page-header">Order
			<small>Boxes</small>
			
			</h2>
			
			
			 <ol class="breadcrumb">
				<li>
					<i class="fa fa-fw fa-ticket"></i> <a  href="{{ URL::to('users/order') }}">Orders</a>
				</li>
				<li>
					<i class="fa fa-list" class="active"></i> {{ $parent->f_code }} /  {{ $parent->f_name }} 
				</li>

			</ol>
			
			
		</div>
	</div>
		
 
	<div class="row">
	
		<div class="col-lg-12" style="overflow: auto;">

			@if (Session::has('error'))
				<div class="alert alert-danger">{{ Session::get('error') }}</div>
			@endif
			
			@if (Session::has('message'))
				<div class="alert alert-info">{{ Session::get('message') }}</div>
			@endif
			

			<table cellpadding="0" cellspacing="0" border="0" class="display table table-condensed  small-font" id="datatables">
				<thead>
					<tr>
						<th>Box</th>
						<th>Box Date</th>
						<th>Status</th>
						<th>Pages</th>
						<th>Cost</th>
                        @foreach ($companyAttributeHeaders as $header)
                                <th>{{ $header }}</th>
                        @endforeach
                        <th>Attributes</th>
						<th>Action</th>	
					</tr>
				</thead>
				<tbody>
					
					@foreach($objects as $object)
						<tr>
						<td>{{ $object->f_code }} / {{ $object->f_name }} </td>
						<td>{{ Helpers::niceDateTime($object->creation) }} </td>
						<td>{{ $object->estatus }} </td>
						<td>{{ $object->qty }} </td>
						<td>{{ Helpers::money($object->price) }} </td>
                        @foreach ($companyAttributeHeaders as $_attributeId => $header)
                            @if(isset($object->attributeValues[$_attributeId]))
                            <td>{{  implode(", ", $object->attributeValues[$_attributeId]) }}</td>
                            @else
                            <td> </td>
                            @endif
                        @endforeach

                        <td class="text-center">
                            <a class="btn btn-sm btn-default" href="{{ URL::to('users/order/attributes/' . $object->row_id . '/edit?back=users/order/'. $parent->row_id) }}" data-toggle="modal" data-target="#attributeModal"> <i class="fa fa-gear fa-lg"></i> </a>
                        </td>

						<td> <a class="btn btn-sm btn-success" href="{{ URL::to('users/chart/' . $object->row_id .'/'. $parent->row_id) }}"><i class="fa fa-fw fa-list"></i> List File</a> </td>
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
				   "aaSorting": [[ 0, "asc" ]],
				}
			);
		 } );

        $("#wrapper").toggleClass("toggled");
	</script>
@stop
