@extends('layout')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">System 
            <small>Users Activity Logs</small>
            </h2>
            
            
             <ol class="breadcrumb">
                <li>
                    <i class="fa fa-fw fa-user"></i> System
                </li>
                <li class="active">
                    <i class="fa fa-clock-o"></i>  Activity History
                </li>
            </ol>

                <div class="alert alert-info alert-dismissable">
                    <i class="fa fa-info-circle"></i>  <strong>Note:</strong>You are viewing all users activities for past 90 days.
                </div>  
 
         
        </div>
    </div>
        
 
    <div class="row">
    
        <div class="col-lg-12">

            @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
            
            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
            

            <table cellpadding="0" cellspacing="0" border="0" class="display table table-condensed" id="datatables">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event</th>
                        <th>Details</th>
                        <th>IP Address</th>
                        <th>Date Time</th>
                        <th>Past</th>
                        <th>By Username</th>
                    </tr>
                </thead>
                
                <tbody>
                    
                    @foreach($activityLogs as $activityLog)
                    
                    <tr>
                        <td>
                             {{ $activityLog->id }} 
                        </td>
                        
                        <td>
                             {{ $activityLog->description }}
                        </td>
                        <td>
                             {{ $activityLog->detailsText }} 
                        </td>
                        <td>
                             {{ $activityLog->ip_address }} 
                        </td>
                        <td>
                            {{ Helpers::niceDateTime($activityLog->created_at)}}
                        </td>
                        
                        <td>
                            {{ Helpers::timeAgo(strtotime($activityLog->created_at)) }} ago
                        </td>
                        <td>
                             {{ $activityLog->username }} 
                        </td>
                    </tr>
                        
                    @endforeach
 
                </tbody>
                    
            </table>

        </div>
            
    </div>

@stop


@section('loadjs')
    
    <script type="text/javascript">
        $(document).ready(function() {
        
            $('#datatables').DataTable(
                {
                  "order": [[ 0, "desc" ]],
                  "stateSave": true
                }
            );
         } );
    </script>
@stop
