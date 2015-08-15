@extends('layout')

@section('content')

 
        <div class="col-lg-12">
            <h2 class="page-header">Site Admin 
            <small>Billing</small>
            </h2>
            
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  Site Admin 
                </li>
                <li class="active">
                    <i class="fa fa-group"></i> Daily Usage Billing
                </li>
            </ol
            
        </div>

        <div class="col-lg-12" style="overflow: auto;">
            @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
            
            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
            <h3> {{ $companyName }}</h3>

            <table cellpadding="0" cellspacing="0" border="0" class="table table-hover small-font table-striped" id="datatables">
                <thead>
                    <tr>
                        <th> Bill Date</th>
                        <th> Base Price ($)</th>
                        <th> Number of Active Users</th>
                        <th> User Charges</th>
                        <th> Storage Usage (GB)</th>
                        <th> Storage Charges</th>
                        <th> Own Scan Pages</th>
                        <th> Own Scan Charges</th>
                        <th> Plan Scan Pages</th>
                        <th> Plan Scan Charges</th>
                        <th> To Date Amount($)</th> 
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach($reports as $report)
                        
                    <tr>
                        <td>
                            <strong>{{ $report->report_date }}</strong>
                        </td>            
                        <td>
                            {{ $report->base_price }}
                        </td>
                        <td>
                            {{ $report->current_number_of_users }}
                        </td>
                        <td>
                            {{ $report->user_charges }}
                        </td>
                        <td>
                            {{ $report->current_storage_usage }}
                        </td>
                        <td>
                            {{ $report->storage_charges }}
                        </td>
                        <td>
                            {{ $report->current_number_of_own_scans }}
                        </td>
                        <td>
                            {{ $report->own_scan_charges }}
                        </td>
                        <td>
                            {{ $report->current_number_of_plan_scans }}
                        </td>
                        <td>
                            {{ $report->plan_scan_charges }}
                        </td>
                        <td>
                            <strong>{{ $report->current_charges }}</strong>
                        </td>
                    </tr>
                        
                    @endforeach
 
                </tbody>
            </table>

        </div>
            
 

@stop


@section('loadjs')
    
    <script type="text/javascript">
        $(document).ready(function() {
        
            $('#datatables').DataTable( 
                {
                    "iDisplayLength": 50,
                    "order": [[ 0, "desc" ]]
                }
            );
         } );

        $("#wrapper").toggleClass("toggled");
    </script>
@stop
