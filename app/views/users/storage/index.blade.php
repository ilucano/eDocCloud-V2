@extends('layout')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">My Folder
                 <div class="pull-right"><a class="btn btn-sm btn-success" href="{{ URL::to('users/storage/create') }}"><i class="fa fa-plus-circle fa-lg"></i> Upload File</a></div>
            </h2>


             <ol class="breadcrumb">
                <li>
                    <i class="fa fa-fw fa-folder"></i> Storage
                </li>
                <li class="active">
                    <i class="fa fa-file"></i> Uploaded files
                </li>
            </ol>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-6">
             <div class="alert alert-warning">This is your personal folder. You can upload documents, images and zip files here.</div>
            <!-- message div -->
            @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif

            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
        </div>
    </div>

     <div class="row">
        <div class="col-sm-12" style="overflow: auto;">

            <table id="datatables" class="table table-bordered table-hover small-font">
                <thead>
                <tr>
                    <th><i class="fa fa-times" title="Select to delete"></i> </th>
                    <th><i class="fa fa-star fa-lg"></i></th>
                    <th class="span2">Filename</th>
                    <th class="span2">Size</th>
                    @foreach ($companyAttributeHeaders as $header)
                                <th>{{ $header }}</th>
                    @endforeach
                  
                    <th class="span2" nowrap>Created Date</th>
                  
                    <th class="span2" colspan="2" nowrap>Action</th>
                </tr>
                </thead>
                <tbody>
                  
                        @foreach ($uploads as $upload)
                            <tr>
                                <td>
                                    {{ Form::checkbox('todelete', $upload->id, null, ['class' => 'checkbox-delete']) }}
                                </td>
                                <?php
                                    $starIcon = ($upload->favourite == 1) ? 'fa-star' : 'fa-star-o';
                                ?>
                                <td><span style="color: #fff;font-size: 0px;">{{ $upload->favourite }}</span> <a title="Add / Remove Favourite" href="{{ URL::to('users/storage/switchfav/' . $upload->id ) }}"><i class="fa {{ $starIcon }} fa-lg"></i></a></td>
                                <td>{{ $upload->filename }} </td>
                                <td>{{ Helpers::bytesToMegaBytes($upload->size) }}</td>

                                @foreach ($companyAttributeHeaders as $_attributeId => $header)
                                    @if(isset($upload->attributeValues[$_attributeId]))
                                    <td>{{  implode(", ", $upload->attributeValues[$_attributeId]) }}</td>
                                    @else
                                    <td> </td>
                                    @endif
                                @endforeach

                                <td>{{ Helpers::niceDateTime($upload->created_at) }}</td>
                                <td><a class="btn btn-sm btn-info" href="{{ URL::to('users/storage/attributes/' . $upload->id . '/edit') }}" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit fa-lg"></i> Attributes </a> </td>
                                <td><a class="btn btn-sm btn-primary" href="{{ URL::to('users/storage/download/' . $upload->id ) }}"> <i class="fa fa-download fa-lg"></i> Download</a></td>
                            </tr>
                        @endforeach
             
                </tbody>
            </table>
                {{ Form::open(['route' => ['users.storage.destroy'], 'method' => 'delete']) }}
                {{ Form::hidden('deletelist', '',  array('id' => 'deletelist')) }}
                {{ Form::submit('Delete Selected File', array('class' => 'btn btn-danger btn-sm', 'id' => 'delete-button', 'disabled' => 'disabled')) }}
                {{ Form::close() }}
        </div>
        </div>
    </div>


        <!-- Default bootstrap modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
          </div>
          <div class="modal-body">
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

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



        $(document).ready(function() {

            $(document).on('click', '.checkbox-delete', function(){
                var id =  $(this).val();
                var isChecked = $(this).prop('checked');

                if ($("#deletelist").val() != '') {
                    var selectedArray = $("#deletelist").val().split(',');
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

                $("#deletelist").val(selectedArray.join(','));
                if(selectedArray.length >= 1) {
                    $("#delete-button").val('Delete Selected ' + selectedArray.length  + ' File(s)');
                    $("#delete-button").removeAttr("disabled");
                }
                else {
                    $("#delete-button").val('Delete Selected File');
                    $("#delete-button").attr("disabled", true);
                }
            });
        });


        $("#myModal").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("href"));
        });

        $("#wrapper").toggleClass("toggled");
    </script>
@stop