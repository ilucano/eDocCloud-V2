
    {{ Form::model($object, array('route' => array('users.order.attribute.update', $object->row_id), 'method' => 'PUT')) }}


    <div class="modal-header">
        <h3 class="modal-title">{{ $object->f_code }} / {{ $object->f_name }}</h3>
    </div>

    <div class="modal-body">
       
            <div class="row">
                <div class="col-sm-8">
                @include('partials.file.metaattribute', array('attributeSets' => $attributeSets))
                </div>
            </div>

       
    </div>


    <div class="modal-footer">
        {{ Form::hidden('back', $back) }}
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {{ Form::submit('Save Changes', array('class' => 'btn btn-primary')) }}
    </div>
    
    {{ Form::close() }}

 