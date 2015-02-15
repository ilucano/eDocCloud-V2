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
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-home"></i> {{ Auth::user()->getCompanyName() }} <b class="caret"></b></a>
						
				    <ul class="dropdown-menu">
					
                        <li>
                            <a href="{{ URL::to('companyadmin/user') }}"><i class="fa fa-fw fa-users"></i> My Users</a>
                        </li>
							
						<li>
                            <a href="{{ URL::to('companyadmin/') }}"> System Users</a>
                        </li>
							
						<li>
                            <a href="{{ URL::to('companyadmin/filemark') }}"><i class="fa fa-fw fa-tags"></i> My Filemarks</a>
                        </li>
						 
                    </ul>
						
				 </li>
					
			</ul>
				
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
                    <ul class="dropdown-menu message-dropdown">
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>John Smith</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>John Smith</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>John Smith</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-footer">
                            <a href="#">Read All New Messages</a>
                        </li>
                    </ul>
                </li>
                
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
                            <a href="{{ URL::to('administrator') }}"> System Admins</a>
                        </li>
						 
                    </ul>
						
				 </li>
					
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Auth::user()->getUserData()->first_name }} {{ Auth::user()->getUserData()->last_name }} <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ URL::to('logout') }}"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li class="active">
                        <a href="index.html"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="charts.html"><i class="fa fa-fw fa-bar-chart-o"></i> Charts</a>
                    </li>
                    <li>
                        <a href="tables.html"><i class="fa fa-fw fa-table"></i> Tables</a>
                    </li>
                    <li>
                        <a href="forms.html"><i class="fa fa-fw fa-edit"></i> Forms</a>
                    </li>
                    <li>
                        <a href="bootstrap-elements.html"><i class="fa fa-fw fa-desktop"></i> Bootstrap Elements</a>
                    </li>
                    <li>
                        <a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Bootstrap Grid</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Dropdown <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
                            <li>
                                <a href="#">Dropdown Item</a>
                            </li>
                            <li>
                                <a href="#">Dropdown Item</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="blank-page.html"><i class="fa fa-fw fa-file"></i> Blank Page</a>
                    </li>
                    <li>
                        <a href="index-rtl.html"><i class="fa fa-fw fa-dashboard"></i> RTL Dashboard</a>
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


	<!-- jQuery Data Table -->
	{{ HTML::script('js/plugins/datatable/jquery.dataTables.min.js') }}
    {{ HTML::script('js/plugins/datatable/dataTables.bootstrap.js') }}
	
	<!-- jQuery Multiselect for bootstrap -->
	{{ HTML::script('js/plugins/multiselect/bootstrap-multiselect.js') }}
	@yield('loadjs') 
</body>

</html>