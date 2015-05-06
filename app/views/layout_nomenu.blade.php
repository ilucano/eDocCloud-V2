<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>Welcome to eDocCloud</title>

    <!-- Bootstrap Core CSS -->
	{{ HTML::style('css/bootstrap.min.css') }}
    
    <!-- Custom CSS -->
	{{ HTML::style('css/sb-admin.css') }}

    <!-- Morris Charts CSS -->
	{{ HTML::style('css/plugins/morris.css') }}

    <!-- Custom Fonts -->
	{{ HTML::style('font-awesome/css/font-awesome.min.css') }}
   
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper" class="left-empty">

        <div id="page-wrapper" class="background-empty">

            <div class="container-fluid">

                <!-- Container Fluid Here -->
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

    <!-- Morris Charts JavaScript -->
	{{ HTML::script('js/plugins/morris/raphael.min.js') }}
	{{ HTML::script('js/plugins/morris/morris.min.js') }}
    @yield('loadjs') 
</body>

</html>