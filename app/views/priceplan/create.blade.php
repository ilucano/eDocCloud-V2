@extends('layout')

@section('content')

        <div class="col-sm-12">
            

            <h2 class="page-header">Site Admin 
            <small>Price Plan Template</small>
            </h2>
                
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  Site Admin 
                </li>
                    
                <li class="active">
                    <i class="fa fa-users"></i> <a href="{{ URL::route('priceplan.index') }}">Price Plan Template</a>
                </li>
 
                <li class="active">
                    <i class="fa fa-user"></i> New Tempalte
                </li>
            </ol
        </div>
        
         {{ $details }}
         {{ print_r($var2) }}

@stop


