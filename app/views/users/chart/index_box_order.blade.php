@extends('layout')

@section('content')

	<div class="row">
		<div class="col-lg-12">
		    
			<div class="pull-left"><a class="btn btn-sm btn-info" href="{{ URL::to('users/order/'. $order->row_id) }}"><i class="fa fa-caret-left fa-lg"></i> Back</a></div>
			
			<h2 class="page-header">Boxes
			<small>Chart</small>
			
			</h2>
			
			
			 <ol class="breadcrumb">
				<li>
					<i class="fa fa-fw fa-ticket"></i> <a  href="{{ URL::to('users/order') }}">Orders</a>
				</li>
				
				<li>
				<a  href="{{ URL::to('users/order/'. $order->row_id) }}"> {{ $order->f_code }} / {{ $order->f_name }} </a>
                </li>
					
				<li>
					<i class="fa fa-list" class="active"></i> {{ $box->f_code }} /  {{ $box->f_name }} 
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
			

			<table cellpadding="0" cellspacing="0" border="0" class="display table table-condensed small-font" id="datatables">
				<thead>
					<tr>
						<th>Chart</th>
						<th>Chart Date</th>
						<th>Status</th>
						<th>Pages</th>
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
						
						<td>@if($object->f_code) {{ $object->f_code }} / @endif @if($object->f_name) {{ $object->f_name }} @endif </td>
						<td>{{ Helpers::niceDateTime($object->creation) }} </td>
						<td>{{ $object->status }} </td>
						<td>{{ $object->qty }} </td>

                        @foreach ($companyAttributeHeaders as $_attributeId => $header)
                            @if(isset($object->attributeValues[$_attributeId]))
                            <td>{{  implode(", ", $object->attributeValues[$_attributeId]) }}</td>
                            @else
                            <td> </td>
                            @endif
                        @endforeach


                         <td class="text-center">
                            <a class="btn btn-sm btn-default" href="{{ URL::to('users/order/attributes/' . $object->row_id . '/edit?back=users/chart/' . $box->row_id .'/'. $order->row_id) }}" data-toggle="modal" data-target="#attributeModal"> <i class="fa fa-gear fa-lg"></i> </a>
                        </td>

						<td> <a class="btn btn-sm btn-success" href="{{ URL::to('users/chart/' . $box->row_id .'/'. $order->row_id .'/'. $object->row_id) }}"><i class="fa fa-fw fa-folder-open-o"></i> Show Files</a> </td>
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
				}
			);
		 } );

        $("#wrapper").toggleClass("toggled");
	</script>
@stop
