@extends('layout')

@section('content')

<style>

.entry:not(:first-of-type)
{
    margin-top: 10px;
}

.glyphicon
{
    font-size: 12px;
}

</style>

        <div class="col-lg-12">
            
            <h2 class="page-header">{{ Auth::User()->getCompanyName() }}
                <small>Attribute</small>
            </h2>
                
                
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  Company Admin
                </li>
                    
                <li class="active">
                    <i class="fa fa-tags"></i> <a href="{{ URL::to('companyadmin/metaattribute/') }}">{{ $metaAttribute->name }}</a>
                </li>
            </ol
        </div>
        
        <div class="col-lg-4">
 
            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
            
            @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif

            @if($errors->has())
                <div class="alert alert-danger">
               @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
              @endforeach
                </div>
            @endif


                {{ Form::model($metaAttribute, array('route' => array('companyadmin.metaattribute.update', $metaAttribute->id), 'method' => 'PUT')) }}

            <div class="form-group @if ($errors->has('name')) has-error @endif">
            
                <label>Attribute Name</label>
                 
                {{ Form::text('name', Input::old('name'), array('class'=>'form-control', 'placeholder'=>'Enter attribute name')) }}

            </div>

            <div class="form-group  @if ($errors->has('type')) has-error @endif">
                <label>Attribute Type</label>
                

                {{ Form::select('disable_type', $attributeTypes, $metaAttribute->type, array('class'=>'form-control', 'disabled' => 'disabled', 'id'=>'select-type')) }}
                
                {{ Form::hidden('type', $metaAttribute->type) }}
            </div>

            
            <div class="container" id="container-options-form">
                <div class="row">
                    <div class="control-group @if ($errors->has('options*')) has-error @endif" id="fields" >
                        <label class="control-label" for="field1" id="options-label">Options</label>
                        <div class="controls"> 
                            <div id="controls-form" role="form" autocomplete="off">
                                @if (!empty($metaAttribute->attribute_options->options)) 
                                    <?php
                                        $arrayOptions = json_decode($metaAttribute->attribute_options->options, true);
                                    ?> 
                                    @for ( $i = 0; $i < count($arrayOptions); $i++)
                                        <div class="entry input-group col-xs-3">
                                            <input class="form-control" name="options[]" type="text" value="{{ $arrayOptions[$i] }}"/>
                                            <span class="input-group-btn">
                                                @if ($i == (count($arrayOptions) - 1))
                                                    <button class="btn btn-success btn-add" type="button">
                                                        <span class="glyphicon glyphicon-plus"></span>
                                                    </button>
                                                @elseif
                                                    <button class="btn btn-danger btn-remove" type="button">
                                                        <span class="glyphicon glyphicon-minus"></span>
                                                    </button>
                                                @endif
                                            </span>
                                        </div>
                                    @endfor

                                @endif
                            </div>
                        <br>
                        <small>Press <span class="glyphicon glyphicon-plus gs"></span> to add another optoin</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group  @if ($errors->has('required')) has-error @endif">
                <label>Required</label>
                    
                {{ Form::select('required', $requiredDropdown, $metaAttribute->required, array('class'=>'form-control')) }}
               
            </div>

            <div class="form-group pull-left">
                    
                    <a class="btn btn-sm btn-info" href="{{ URL::to('companyadmin/metaattribute') }}"> Back</a>
            </div>
                        
            <div class="form-group pull-right">
                            
                    {{ Form::submit('Save', array('class' => 'btn btn-sm btn-success')) }}
            </div>



            </div>      
        
        {{ Form::close() }}
@stop




@section('loadjs')
    
    
    <script type="text/javascript">
      

        $(function()
        {
            $(document).on('click', '.btn-add', function(e)
            {
                e.preventDefault();

                var controlForm = $('#controls-form'),
                    currentEntry = $(this).parents('.entry:first'),
                    newEntry = $(currentEntry.clone()).appendTo(controlForm);

                newEntry.find('input').val('');
                controlForm.find('.entry:not(:last) .btn-add')
                    .removeClass('btn-add').addClass('btn-remove')
                    .removeClass('btn-success').addClass('btn-danger')
                    .html('<span class="glyphicon glyphicon-minus"></span>');
            }).on('click', '.btn-remove', function(e)
            {
                $(this).parents('.entry:first').remove();

                e.preventDefault();
                return false;
            });
        });

        var showHideOptions = function()
        {
            var selected = $('#select-type').val();
            if ( $.inArray(selected, ['radio', 'select', 'checkbox', 'multiselect']) >= 0 ) {
                    $('#container-options-form').show();
            }
            else {
                $('#container-options-form').hide();
            }

        }

        $(function()
        {
            $(document).on('change', '#select-type', function(e)
            {
                e.preventDefault();

                showHideOptions();

            });

            showHideOptions();

        });

    </script>

@stop

