@extends('layout')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">My Folder
            </h2>


             <ol class="breadcrumb">
                <li>
                    <i class="fa fa-fw fa-folder"></i> Storage
                </li>
                <li class="active">
                    <i class="fa fa-search"></i> Uploaded files
                </li>
            </ol>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-6">
            <!-- message div -->
            @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif

            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
        </div>

        <div class='clearfix'></div>
        {{ Form::open(array('url' => 'users/storage', 'files' => true)) }}
        {{ Form::file('userfile') }}
        {{ Form::submit('Upload', array('class' => 'btn btn-primary')) }}
        
@stop
