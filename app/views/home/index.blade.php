@extends('layout')

@section('content')

<!-- Page Heading -->
  <div class="row">
	  <div class="col-lg-12">
		  <h1 class="page-header">
			  Dashboard <small>Statistics Overview</small>
		  </h1>
		  <ol class="breadcrumb">
			  <li class="active">
				  <i class="fa fa-dashboard"></i> Dashboard
			  </li>
		  </ol>
	  </div>
  </div>
  <!-- /.row -->

  <!-- /.row -->

  <div class="row">
	  <div class="col-lg-3 col-md-6">
		  <div class="panel panel-primary">
			  <div class="panel-heading">
				  <div class="row">
					  <div class="col-xs-3">
						  <i class="fa fa-comments fa-5x"></i>
					  </div>
					  <div class="col-xs-9 text-right">
						  <div class="huge">{{ $statistics->number_of_preparations }}</div>
						  <div>Preparations</div>
					  </div>
				  </div>
			  </div>
			  <a href="{{ URL::to('prepare') }}">
				  <div class="panel-footer">
					  <span class="pull-left">View Details</span>
					  <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					  <div class="clearfix"></div>
				  </div>
			  </a>
		  </div>
	  </div>
	  <div class="col-lg-3 col-md-6">
		  <div class="panel panel-green">
			  <div class="panel-heading">
				  <div class="row">
					  <div class="col-xs-3">
						  <i class="fa fa-tasks fa-5x"></i>
					  </div>
					  <div class="col-xs-9 text-right">
						  <div class="huge">{{ $statistics->number_of_scans }}</div>
						  <div>Scans</div>
					  </div>
				  </div>
			  </div>
			  <a href="{{ URL::to('scan') }}">
				  <div class="panel-footer">
					  <span class="pull-left">View Details</span>
					  <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					  <div class="clearfix"></div>
				  </div>
			  </a>
		  </div>
	  </div>
	  <div class="col-lg-3 col-md-6">
		  <div class="panel panel-yellow">
			  <div class="panel-heading">
				  <div class="row">
					  <div class="col-xs-3">
						  <i class="fa fa-shopping-cart fa-5x"></i>
					  </div>
					  <div class="col-xs-9 text-right">
						  <div class="huge">{{ $statistics->number_of_qas }}</div>
						  <div>QAs</div>
					  </div>
				  </div>
			  </div>
			  <a href="{{ URL::to('qa') }}">
				  <div class="panel-footer">
					  <span class="pull-left">View Details</span>
					  <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					  <div class="clearfix"></div>
				  </div>
			  </a>
		  </div>
	  </div>
	  <div class="col-lg-3 col-md-6">
		  <div class="panel panel-red">
			  <div class="panel-heading">
				  <div class="row">
					  <div class="col-xs-3">
						  <i class="fa fa-support fa-5x"></i>
					  </div>
					  <div class="col-xs-9 text-right">
						  <div class="huge">{{ $statistics->number_of_ocrs }}</div>
						  <div>OCRs</div>
					  </div>
				  </div>
			  </div>
			  <a href="{{ URL::to('ocr') }}">
				  <div class="panel-footer">
					  <span class="pull-left">View Details</span>
					  <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					  <div class="clearfix"></div>
				  </div>
			  </a>
		  </div>
	  </div>
  </div>
  <!-- /.row -->

 
@stop 