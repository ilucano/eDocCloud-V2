@extends('layout_nomenu')

@section('content')


    {{ Form::model($file, array('route' => array('users.file.attribute.update', $file->row_id), 'method' => 'PUT')) }}


    <div class="modal-header">
        <h3 class="modal-title">{{ $file->filename }}</h3>
    </div>

    <div class="modal-body">
       
            <div class="row">
                <div class="col-sm-8">
                @include('partials.file.metaattribute', array('attributeSets' => $attributeSets))
                </div>
            </div>

       
    </div>


    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {{ Form::submit('Save Changes', array('class' => 'btn btn-primary')) }}
    </div>
    
    {{ Form::close() }}

@stop

