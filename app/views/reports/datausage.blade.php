@extends('layout')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">Reports 
            <small>Data Usages</small>
            </h2>
                
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  Reports
                </li>
                <li class="active">
                    <i class="fa fa-table"></i> Data Usages
                </li>
            </ol>
                            
        </div>
    </div>
        
 
    <div class="row">
    
        <div class="col-lg-10">

            @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
            
            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
            

            <table cellpadding="0" cellspacing="0" border="0" class="display" id="reports">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Number of Files</th>
                        <th>Current Month Usage</th>
                        <th>Total Usage</th>                 
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $company)
                        <tr>
                            <td>{{ $company->company_name }}</td>
                            <td>{{ $company->number_of_files }}</td>
                            <td>{{ Helpers::bytesToGigabytes($company->monthly_data_usage) }}</td>
                            <td>{{ Helpers::bytesToGigabytes($company->todate_data_usage) }}</td>
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
        
            $('#reports').DataTable(
                {
                }
            );
         } );


    </script>
    
@stop
