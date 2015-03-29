@extends('layout')

@section('content')

        <div class="col-sm-12">
            

            <h2 class="page-header">{{ $role->company_name }}
            <small>User Roles</small>
            </h2>
            
            <div class="alert alert-info alert-dismissable">
                    <i class="fa fa-info-circle"></i>  <strong>Note:</strong>You are viewing roles of client company. Changes will affect the company's users of this role.
            </div>  
 
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  Systems
                </li>
                    
                <li class="active">
                    <i class="fa fa-users"></i> <a href="{{ URL::to('role') }}">User Roles</a>
                </li>
 
                <li class="active">
                    <i class="fa fa-users"></i> {{ $role->company_name }} - {{ $role->name }}
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
            
        <div class="clearfix"></div>
            
            
        {{ Form::model($role, array('route' => array('role.update', $role->id), 'method' => 'PUT')) }}

                
            <div class="col-lg-6">
                
                
            
                
                <div class="form-group @if ($errors->has('name')) has-error @endif">
                    <label>Role Name</label>
                    {{ Form::text('name', $role->name, array('class'=>'form-control','placeholder'=>'e.g. Sales Dept')) }}
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                </div>
                    
            
                <div class="form-group @if ($errors->has('permission_list')) has-error @endif">
                    <label>Role Permission</label>
                        
                        @if ($errors->has('permission_list')) <p class="help-block">{{ $errors->first('permission_list') }}</p> @endif
                            
                        <div class="table-responsive">
                            <table class="table table-hover" id="permission-table">
                                <tbody>
                                    
                                    @foreach($permissionList as $permission)
                                    <tr>
                                        <td>{{ $permission->display_name }}</td>
                                        <td>
                                            <?php
                                              $checked = in_array($permission->id, $arraySelectedPermission) ? 1 : 0;
                                            ?>
                                            {{ Form::checkbox('permission_list[]', $permission->id, $checked, ['data-style'=>'btn-group-xs']) }}
                                            
                                        </td>
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                        

                 <div class="form-group pull-left">
                    <a class="btn btn-sm btn-info" href="{{ URL::to('role') }}"> Back</a>
                </div>
                            
                <div class="form-group pull-right"> 
                    {{ Form::submit('Save', array('class' => 'btn btn-sm btn-success')) }}
                </div>  
         

            </div>      
        
        {{ Form::close() }}
@stop




@section('loadjs')
    
    <script type="text/javascript">

        $(function() {
                $('#permission-table input[type="checkbox"]').checkboxpicker({
                });
        });

    </script>
@stop

