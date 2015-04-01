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
        <div class="col-sm-12">
            

            <h2 class="page-header">{{ Auth::User()->getCompanyName() }}
            <small>Attribute</small>
            </h2>
                
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  Company Admin
                </li>
                    
                <li class="active">
                    <i class="fa fa-tags"></i> <a href="{{ URL::to('companyadmin/metaattribute') }}">Attributes</a>
                </li>
 
                <li class="active">
                    <i class="fa fa-pencil"></i> Create New Attribute
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
        
            
            {{ Form::open(array('url' => 'companyadmin/metaattribute', 'class' => 'form')) }}
        
        
            <div class="form-group input-group @if ($errors->has('name')) has-error @endif">
            
             
                <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                
                {{ Form::text('name', Input::old('name'), array('class'=>'form-control', 'placeholder'=>'Enter attribute name')) }}
                
                
                    
            </div>
            @if ($errors->has('label')) <p class="help-block">{{ $errors->first('label') }}</p> @endif
            

            <div class="form-group  @if ($errors->has('type')) has-error @endif">
                <label>Input Type</label>
                    
                {{ Form::select('type', $attributeTypes, null, array('class'=>'form-control')) }}
                @if ($errors->has('type')) <p class="help-block">{{ $errors->first('type') }}</p> @endif
                
            </div>


            <div class="container">
                <div class="row">
                    <div class="control-group" id="fields">
                        <label class="control-label" for="field1">Options</label>
                        <div class="controls"> 
                            <form role="form" autocomplete="off">
                                <div class="entry input-group col-xs-3">
                                    <input class="form-control" name="fields[]" type="text" placeholder="Type something" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-success btn-add" type="button">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        <br>
                        <small>Press <span class="glyphicon glyphicon-plus gs"></span> to add another form field :)</small>
                        </div>
                    </div>
                </div>
            </div>

             <div class="form-group  @if ($errors->has('required')) has-error @endif">
                <label>Required</label>
                    
                {{ Form::select('type', $requiredDropdown, null, array('class'=>'form-control')) }}
                @if ($errors->has('required')) <p class="help-block">{{ $errors->first('required') }}</p> @endif
                
            </div>


            {{ Form::submit('Create', array('class' => 'btn btn-sm btn-success')) }}
    
            
            
                
            {{ Form::close() }}

        </div>      
    
@stop


@section('loadjs')
    
    <script type="text/javascript">
      

      $(function()
        {
            $(document).on('click', '.btn-add', function(e)
            {
                e.preventDefault();
                
                var controlForm = $('.controls form:first'),
                    currentEntry = $(this).parents('.entry:first'),
                    newEntry = $(currentEntry.clone()).appendTo(controlForm);
            
                console.log(currentEntry);

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



    </script>
@stop
