
    {{ Form::model($upload, array('route' => array('users.storage.attribute.update', $upload->id), 'method' => 'PUT')) }}


    <div class="modal-header">
        <span>{{ $upload->filename }}</span>
    </div>

    <div class="modal-body">
       
            <div class="row">
                <div class="col-sm-8">
                   @include('partials.storage.metaattribute', array('attributeSets' => $attributeSets))
                </div>
            </div>

       
    </div>


    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}
    </div>
    
    {{ Form::close() }}

