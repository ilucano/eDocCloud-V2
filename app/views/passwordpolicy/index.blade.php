@extends('layout')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">Password Policy
            <small></small>
            </h2>
        </div>
        
        <div class="col-lg-5">
        
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif
        
         @if($errors->has())
                <div class="alert alert-danger">
               @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
              @endforeach
                </div>
        @endif

       
        {{ Form::model($policy, array('route' => array('passwordpolicy.update', $policy->id), 'method' => 'PUT', 'class' => 'form-horizontal')) }}

                <div class="form-group">
                   <label for="" class="col-sm-8 control-label">Must contain UPPERCASE</label>

                   <div class="col-sm-4">
                        {{ Form::select('uppercase', $booleanDropdown, $policy->uppercase, array('class'=>'form-control')) }}
                    </div>
                   
                </div>

                <div class="form-group">
                   <label for="" class="col-sm-8 control-label">Must contain lowercase</label>

                   <div class="col-sm-4">
                        {{ Form::select('lowercase', $booleanDropdown, $policy->lowercase, array('class'=>'form-control')) }}
                    </div>
                   
                </div>

                <div class="form-group">
                   <label for="" class="col-sm-8 control-label">Minimum Length</label>

                   <div class="col-sm-4">
                        {{ Form::text('min_length',$policy->min_length, array('class'=>'form-control')) }}
                    </div>
                   
                </div>

                <div class="form-group">
                   <label for="" class="col-sm-8 control-label">Must contain special character</label>

                   <div class="col-sm-4">
                     {{ Form::select('special_character', $booleanDropdown, $policy->special_character, array('class'=>'form-control')) }}
                         
                    </div>
                   
                </div>

                <div class="form-group">
                   <label for="" class="col-sm-8 control-label">Password expires in days</label>

                   <div class="col-sm-4">
                        {{ Form::text('expire_days',$policy->expire_days, array('class'=>'form-control')) }}
                    </div>
                   
                </div>
  
            
            {{ Form::submit('Update Policy', array('class' => 'btn btn-sm btn-info')) }}
        </div>
            
        {{ Form::close() }}
    </div>
        
  

@stop
 
