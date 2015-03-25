@extends('layout')

@section('content')

        <div class="col-sm-12">
            

            <h2 class="page-header"> System Admin
            <small>Filemark</small>
            </h2>
                
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  System Admin
                </li>
                    
                <li class="active">
                    <i class="fa fa-tags"></i> <a href="{{ URL::to('companyadmin/filemark') }}">Filemarks</a>
                </li>
 
                <li class="active">
                    <i class="fa fa-tag"></i> {{ $filemark->label }}
                </li>
            </ol
        </div>
        
        <div class="col-lg-8">
 
            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
            
            @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
        </div>
            
        
        
        <div class="col-lg-8">
            
            {{ Form::model($filemark, array('route' => array('admin.filemark.update', $filemark->id), 'method' => 'PUT', 'class' => 'form-inline')) }}
            
            <div class="form-group input-group @if ($errors->has('label')) has-error @endif">

                <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                
                {{ Form::text('label', $filemark->label, array('class'=>'form-control', 'placeholder'=>'Enter label')) }}
                
                
                    
            </div>
            {{ Form::submit('Save', array('class' => 'btn btn-sm btn-success')) }}
            <a class="btn btn-sm btn-info" href="{{ URL::to('admin/filemark') }}"> Back</a>
                    
            @if ($errors->has('label')) <p class="help-block">{{ $errors->first('label') }}</p> @endif
            
            
                
            {{ Form::close() }}

        </div>      
    
@stop


