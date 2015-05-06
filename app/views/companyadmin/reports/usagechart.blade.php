@extends('layout')

@section('content')

    <div class="modal-header">
        <h3 class="modal-title">{{ $company->company_name }} ({{ Helpers::bytesToGigabytes($company->todate_data_usage) }})</h3> 
    </div>

    <div class="modal-body">
       
            <div class="row">
                <div class="col-sm-10">
               
                    <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Year Month</th>
                                        <th>Number of Files</th>
                                        <th>Data Usage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($company->monthly_data_usage as $key => $dataUsage)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ $company->monthly_number_of_files[$key] }}</td>
                                        <td class="text-right">{{ Helpers::bytesToMegabytes($dataUsage) }}</td>
                                    </tr>
                                  @endforeach
                                </tbody>
                            </table>
                    </div>



                </div>

                <div class="col-lg-10">
                     <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"> Data Usage by Month</h3>
                        </div>
                        <div class="panel-body">
                            <div id="morris-bar-chart"></div>
                            
                        </div>
                    </div>
                </div>

        
    </div>

@stop

<?php 
    
    $morrisData = array();

    foreach ($company->monthly_data_usage as $key => $dataUsage) {
        $morrisData[] = array('yearmonth' => $key, 'usage' => round($dataUsage / 1024 / 1024, 2));
    }
    
?>



@section('loadjs')
    
    <script type="text/javascript">
        
        // Morris.js Charts sample data for SB Admin template

$(function() {

    // Bar Chart
    Morris.Bar({
        element: 'morris-bar-chart',
        data: {{ json_encode($morrisData) }},
        xkey: 'yearmonth',
        ykeys: ['usage'],
        labels: ['Data Usage (MB)'],
        barRatio: 0.4,
        xLabelAngle: 35,
        hideHover: 'auto',
        resize: true
    });


});

    </script>
    
@stop
