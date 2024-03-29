@extends('layout')

@section('content')

 
<div class="col-lg-12">
    <h2 class="page-header">
    Price Plan Templates
    <div class="pull-right"><a class="btn btn-sm btn-info" href="{{ URL::route('priceplan.create') }}"><i class="fa fa-plus-circle fa-lg"></i> Create New Plan Template</a></div>
    </h2>
     
</div>




<div class="col-lg-12 well">
    @if (Session::has('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif
    
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    
     @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-striped" id="datatables">
        <thead>
            <tr>
                <th> Select</th>
                <th> Plan Code</th>
                <th> Plan Name</th>
                <th> Base Price</th>
                <th> Free Users</th>
                <th> Free Storage</th>
                <th> Free Own Scans</th>
                <th> Free Scans</th>
                <th> Created Date</th>
                <th> Action</th>
            </tr>
        </thead>
        <tbody>
            
            @foreach($pricePlans as $plan)
            
            <tr> 
                <td>
                    {{ Form::radio('toassign', $plan->id, null, ['class' => 'radio-assign']) }}
                </td>   
                <td>
                    <strong>{{ $plan->plan_code }}</strong>
                </td>
                <td> {{ $plan->plan_name }}
                </td>
                
                <td>
                    {{ $plan->base_price }}
                </td>

                <td>
                    {{ $plan->free_users }} Users
                </td>
                <td>
                    {{ $plan->free_gb }} GB
                </td>

                <td>
                    {{ $plan->free_own_scans }}
                </td>

                 <td>
                    {{ $plan->free_plan_scans }}
                </td>   
                 <td>
                    {{ Helpers::niceDateTime($plan->created_at) }}
                </td>             
                <td style="white-space: nowrap;">
                    <div class="pull-left">
                        <a class="btn btn-sm btn-info" href="{{ URL::route('priceplan.edit', ['priceplan' => $plan->id]) }}"><i class="fa fa-edit fa-lg"></i> Edit </a>
                    </div>
                </td>
             
            </tr>
                
            @endforeach

        </tbody>
    </table>

       {{ Form::open(['route' => ['priceplan.assignplan'], 'class' => 'form-inline']) }}
        {{ Form::hidden('assignplan', '',  array('id' => 'assignplan')) }}

         <label>Select plan and assign to company: </label>
         {{ Form::select('company_id', $companyDropdown, null, ['class'=> 'form-control']) }}

          {{ Form::submit('Assign and Customize &raquo;', array('class' => 'btn btn-success btn-sm', 'id' => 'assign-button', 'disabled' => 'disabled')) }}
        {{ Form::close() }}

</div>


<div class="col-lg-12">
    <h2 class="page-header"> 
     Companies Price Plan 
    </h2>
     
</div>

<!-- company plans -->
<div class="col-lg-12">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-striped" id="company-plans">
        <thead>
            <tr>
                <th> Company Name</th>
                <th> Base Price</th>
                <th> Free Users</th>
                <th> Free Storage</th>
                <th> Free Own Scans</th>
                <th> Free Scans</th>
                <th> Created Date</th>
                <th> Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- company plans -->
            @foreach($companyPlans as $plan)
            
            <tr> 
                <td>
                    {{ $plan->company_name }}
                </td>
                <td>
                    {{ $plan->base_price }}
                </td>

                <td>
                    {{ $plan->free_users }} Users
                </td>
                <td>
                    {{ $plan->free_gb }} GB
                </td>

                <td>
                    {{ $plan->free_own_scans }}
                </td>

                 <td>
                    {{ $plan->free_plan_scans }}
                </td>   
                 <td>
                    {{ Helpers::niceDateTime($plan->created_at) }}
                </td>             
                <td style="white-space: nowrap;">
                    <div class="pull-left">
                        <a class="btn btn-sm btn-info" href="{{ URL::route('priceplan.edit', ['priceplan' => $plan->id]) }}"><i class="fa fa-edit fa-lg"></i> Edit </a>
                    </div>
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
            
            );

            $('#company-plans').DataTable();

         } );



         $(document).ready(function() {

            $(document).on('click', '.radio-assign', function(){
                var id =  $(this).val();
                

                $("#assignplan").val(id);
                $("#assign-button").removeAttr("disabled");
                
            });
        });


    </script>
@stop
