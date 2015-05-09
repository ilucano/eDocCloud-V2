@extends('layout')

@section('content')

<link rel="stylesheet" type="text/css" href="{{ URL::asset('packages/andrewelkins/cabinet/css/basic.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('packages/andrewelkins/cabinet/css/dropzone.css') }}" />

 <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">My Folder
                 <div class="pull-right"><a class="btn btn-sm btn-success" href="{{ URL::to('users/storage') }}"><i class="fa fa-caret-left fa-lg"></i> Back to My Folder</a></div>
            </h2>


             <ol class="breadcrumb">
                <li>
                    <i class="fa fa-fw fa-folder"></i> My Folder
                </li>
                <li class="active">
                    <i class="fa fa-upload"></i> Upload File
                </li>
            </ol>
        </div>
    </div>
<div class="alert alert-warning">
                    Files Allowed - <strong>{{ implode(", ", $typesAllowed) }}</strong><br/>
                    Max File Size - {{ Helpers::bytesToMegaBytes($maxFileSize) }}
</div>
<form action="{{ URL::to('users/storage') }}" class="dropzone" id="cabinet-dropzone" accept-charset="UTF-8">
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    <div class="fallback">
        <input name="file" type="file" multiple />
    </div>
</form>

@stop

@section('loadjs')
<script src="{{ URL::asset('packages/andrewelkins/cabinet/js/dropzone.min.js') }}"></script>
@stop