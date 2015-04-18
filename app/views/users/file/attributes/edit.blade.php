@extends('layout')

@section('content')

    <div class="col-sm-12">
            

            <h2 class="page-header">File
            <small>Attributes</small>
            </h2>
                
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  File
                </li>
                <li>
                    Attributes
                </li> 
              
                <li class="active">
                   {{ $file->filename }} (ID: {{ $file->row_id }})
                </li>
            </ol
    </div>


    {{ Form::model($file, array('route' => array('users.file.attribute.update', $file->row_id), 'method' => 'PUT')) }}

    <div class="col-lg-12">
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif
        <h3>{{ $file->filename }}</h3>
    </div>

    <div class="col-lg-8">
        <div class="row">
            <div class="col-sm-8">
            @include('partials.file.metaattribute', array('attributeFilters' => $attributeFilters, 'fileAttributes' => $fileAttributes));
            </div>
        </div>
    </div>
    {{ Form::close() }}

@stop

