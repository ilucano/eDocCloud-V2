<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Welcome to eDocCloud by ImagingXperts</title>

    <!-- Bootstrap Core CSS -->
	{{ HTML::style('css/bootstrap.min.css') }}
    
    <!-- Custom CSS -->
	{{ HTML::style('css/sb-admin.css') }}

    <!-- Morris Charts CSS -->
	{{ HTML::style('css/plugins/morris.css') }}

    <!-- Custom Fonts -->
	{{ HTML::style('font-awesome/css/font-awesome.min.css') }}
	
	<!-- jQuery Data Table CSS -->
	{{ HTML::style('css/plugins/datatable.css') }}
    {{ HTML::style('css/plugins/dataTables.bootstrap.css') }}
	
		
	<!-- jQuery Multiselect for bootstrap -->
	 {{ HTML::style('css/plugins/bootstrap-multiselect.css') }}

     {{ HTML::style('css/plugins/bootstrap-datepicker.css') }}
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
	
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ URL::to('home') }}">eDocCloud</a>
            </div>
				
            <!-- Top Menu Items -->
			<ul class="nav navbar-left top-nav">
				
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-home"></i> {{ Auth::user()->getCompanyName() }}
					@if(Auth::User()->isCompanyAdmin())
						<b class="caret"></b>
				    @endif
					</a>
					
					@if(Auth::User()->isCompanyAdmin())
				    <ul class="dropdown-menu">
					    @if(Auth::User()->can('admin_user') || Auth::User()->isAdmin())
                        <li>
                            <a href="{{ URL::to('companyadmin/user') }}"><i class="fa fa-fw fa-user"></i> My Users</a>
                        </li>
						@endif
						 @if(Auth::User()->can('admin_role') || Auth::User()->isAdmin())
						<li>
                            <a href="{{ URL::to('companyadmin/role') }}"><i class="fa fa-fw fa-users"></i> User Roles</a>
                        </li>
						@endif
						@if(Auth::User()->can('admin_filemark') || Auth::User()->isAdmin())
						<li>
                            <a href="{{ URL::to('companyadmin/filemark') }}"><i class="fa fa-fw fa-tags"></i> My Filemarks</a>
                        </li>
						@endif

                        <li>
                            <a href="{{ URL::to('companyadmin/metaattribute') }}"><i class="fa fa-fw fa-pencil-square-o"></i> Metadata Setup</a>
                        </li>
						
                         <li>
                            <a href="{{ URL::to('companyadmin/reports/usagechart') }}"><i class="fa fa-fw fa-bar-chart"></i> Data Usage</a>
                        </li>
                    </ul>
					@endif
						
				 </li>
					
			</ul>
				
            <ul class="nav navbar-right top-nav">
                
                
				@if(Auth::User()->isAdmin()) 
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-edit"></i> Workflow <b class="caret"></b></a>
						
				    <ul class="dropdown-menu">
					   
                        <li>
                            <a href="{{ URL::to('pickup') }}"> Pickup</a>
                        </li>
						 
						 
						<li>
                            <a href="{{ URL::to('prepare') }}"> Preparation</a>
                        </li>
					 
						<li>
                            <a href="{{ URL::to('scan') }}"> Scan</a>
                        </li>
					 
						<li>
                            <a href="{{ URL::to('qa') }}"> QA</a>
                        </li>
						<li>
                            <a href="{{ URL::to('ocr') }}"> OCR</a>
                        </li>
                    </ul>
						
				 </li>
				
				
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart-o"></i> Reports <b class="caret"></b></a>
						
				    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ URL::to('reports/allboxes') }}"> All Boxes</a>
                        </li>
						<li>
                            <a href="{{ URL::to('reports/groupbystatus') }}"> Group By Status</a>
                        </li>

                        <li>
                            <a href="{{ URL::to('reports/datausage') }}"> Data Usage</a>
                        </li>
						 
                    </ul>
						
				 </li>
				
				
				
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gears"></i> System Admin <b class="caret"></b></a>
						
				    <ul class="dropdown-menu">
					
                        <li>
                            <a href="{{ URL::to('company') }}"> Companies</a>
                        </li>
							
						<li>
                            <a href="{{ URL::to('user') }}"> Users</a>
                        </li>

                        <li>
                            <a href="{{ URL::to('role') }}"> Roles (Groups)</a>
                        </li>
							
						<li>
                            <a href="{{ URL::to('administrator') }}"> Manage Admins</a>
                        </li>

                        <li>
                            <a href="{{ URL::to('admin/filemark') }}"> Manage Filemarks</a>
                        </li>
						
						<li>
                            <a href="{{ URL::to('order') }}"> Orders</a>
                        </li>
						
						<li>
                            <a href="{{ URL::to('admin/pickup') }}"> Pickup</a>
                        </li>
						
						<li>
                            <a href="{{ URL::to('admin/box') }}"> Box</a>
                        </li>

                        <li>
                            <a href="{{ URL::to('admin/activity') }}"> All Users Activities</a>
                        </li>

                         <li>
                            <a href="{{ URL::to('passwordpolicy') }}"> Password Policy</a>
                        </li>


                    </ul>
						
				 </li>
				@endif
				
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Auth::user()->getUserData()->first_name }} {{ Auth::user()->getUserData()->last_name }} <b class="caret"></b></a>
                    <ul class="dropdown-menu">
					    @if(Auth::User()->can('user_changepassword'))
                        <li>
                            <a href="{{ URL::to('users/profile/password') }} "> Change Password</a>
                        </li>
                        @endif
                         <li>
                            <a href="{{ URL::to('users/activity') }} "> Activity History</a>
                        </li>
                        <li>
                            <a href="{{ URL::to('logout') }}"><i class="fa fa-fw fa-sign-out"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
           <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
					 @if(Auth::User()->can('user_order'))
                    <li>
                         <a href="{{ URL::to('users/order') }}"><i class="fa fa-fw fa-ticket"></i> Orders</a>
					</li>
					@endif
					
					@if(Auth::User()->can('user_file') || Auth::User()->can('user_search'))
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-folder"></i> Files <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
							@if(Auth::User()->can('user_search'))
                            <li>
                                <a href="{{ URL::to('users/file/search') }}"><i class="fa fa-fw fa-search"></i> Search</a>
                            </li>
							@endif
							@if(Auth::User()->can('user_file'))
                            <li>
                                <a href="{{ URL::to('users/file') }}"><i class="fa fa-fw fa-list"></i> Browse</a>
                            </li>
							@endif
                        </ul>
                    </li>
					@endif
                    <li>
                         <a href="{{ URL::to('users/storage') }}"><i class="fa fa-fw fa-inbox"></i> My Folder</a>
                    </li>
                </ul>
            </div>  
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">
				<!-- Content --> 
                @yield('content') 

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
	{{ HTML::script('js/jquery.js') }}

    <!-- Bootstrap Core JavaScript -->
	{{ HTML::script('js/bootstrap.min.js') }}

    {{ HTML::script('js/plugins/morris/raphael.min.js') }}
    {{ HTML::script('js/plugins/morris/morris.min.js') }}

	<!-- jQuery Data Table -->
	{{ HTML::script('js/plugins/datatable/jquery.dataTables.min.js') }}
    {{ HTML::script('js/plugins/datatable/dataTables.bootstrap.js') }}
	
	<!-- jQuery Multiselect for bootstrap -->
	{{ HTML::script('js/plugins/multiselect/bootstrap-multiselect.js') }}
	
	<!-- Toggle checkbox for bootstrap -->
	{{ HTML::script('js/plugins/bootstrap-checkbox/bootstrap-checkbox.js') }}

    {{ HTML::script('js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}
	
	
	@yield('loadjs') 
</body>

</html>