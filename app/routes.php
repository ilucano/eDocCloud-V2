<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
//
//Route::get('/', function()
//{
//	return View::make('hello');
//});



// route to show the login form
Route::get('login', array('uses' => 'HomeController@showLogin'));

// route to process the form
Route::post('login', array('uses' => 'HomeController@doLogin'));

// route to show the login form
Route::get('logout', array('uses' => 'HomeController@doLogout'));

Route::group(array('before'=>'auth'), function() {
	
	Route::get('/', array(
						  'uses' => 'HomeController@showIndex',
						  'as' => 'home.index'
	));
	
	Route::get('/home/', array(
								'uses' => 'HomeController@showIndex',
								'as' => 'home.index'
	));
	
	/* begin super admin section */
	
	Route::resource('pickup', 'PickupController');
	 
	
	Route::resource('prepare', 'PrepareController');
	
	Route::post('prepare/status', array('uses' => 'PrepareController@doUpdateStatus'));
	
	Route::resource('scan', 'ScanController');
	
	Route::post('scan/status', array('uses' => 'ScanController@doUpdateStatus'));
	
	Route::resource('qa', 'QAController');
	
	Route::post('qa/status', array('uses' => 'QAController@doUpdateStatus'));
	
	Route::resource('ocr', 'OCRController');
	
	Route::post('ocr/status', array('uses' => 'OCRController@doUpdateStatus'));
	
	Route::get('reports/allboxes', array(
								'uses' => 'ReportsController@showAllBoxes',
								'as' => 'reports.allboxes'
	));
	
	Route::get('reports/groupbystatus', array(
								'uses' => 'ReportsController@showGroupByStatus',
								'as' => 'reports.groupbystatus'
	));
	
	Route::resource('company', 'CompanyController');
	
	Route::resource('user', 'UserController');
	
	Route::get('user/create/company/{fk_empresa}', array(
								'uses' => 'UserController@createStep2',
								'as' => 'user.create.step2'
	));
	
	Route::post('user/create/company/{fk_empresa}', array(
								'uses' => 'UserController@storeStep2',
								'as' => 'user.create.storestep2'
	));
	
	Route::resource('administrator', 'AdministratorController');
	
	Route::post('administrator/isadmin', array('uses' => 'AdministratorController@doUpdateIsAdmin'));
	
	Route::resource('order', 'OrderController');
	
	Route::post('order/status', array('uses' => 'OrderController@doUpdateStatus'));
	
	Route::group(
		array('prefix' => 'admin'), 
		function() {
			
			Route::resource('pickup', 'AdminPickupController');
			Route::resource('box', 'AdminBoxController');
            Route::post('box/status', array('uses' => 'AdminBoxController@doUpdateStatus'));
		}
	);
	
	
	/* end super admin section */
	
	
	/* begin company admin section */
	
		
	Route::group(
		array('prefix' => 'companyadmin'), 
		function() {
			
			Route::resource('user', 'CompanyAdminUserController');
			Route::resource('filemark', 'CompanyAdminFilemarkController');
			Route::resource('role', 'CompanyAdminRoleController');
			
		}
	);
	

	/* end company admin section */
	
	
	/* begin users section */
	
		
	Route::group(
		array('prefix' => 'users'), 
		function() {
			
			Route::resource('order', 'UsersOrderController');
			
			Route::resource('chart', 'UsersChartController');
			
			Route::get('chart/{boxid}/{orderid}', array(
				'as' => 'chart.box.order',
				'uses' => 'UsersChartController@indexBoxOrder'
			));
			
			Route::get('chart/{boxid}/{orderid}/{chartId}', array(
				'as' => 'chart.box.order.chart',
				'uses' => 'UsersChartController@indexBoxOrderChart'
			));
			
			Route::get('file/mark', array('uses' => 'UsersFileController@doUpdateMark'));
			
			Route::get('file/search', array('uses' => 'UsersFileController@showSearch'));
			
			Route::post('file/search', array('uses' => 'UsersFileController@doSearch'));
						
			Route::resource('file', 'UsersFileController');
			
			Route::get('profile/password', array('uses' => 'UsersProfileController@showChangePassword'));
			
			Route::post('profile/password', array('uses' => 'UsersProfileController@doChangePassword'));
			
			Route::resource('profile', 'UsersProfileController');
			
			
			
		}
	);
	
	/* end users section */
	
	/* Attachment */
	Route::get('attachment/file/{id}', array(
				'as' => 'attachment.file.id',
				'uses' => 'AttachmentController@downloadFile'
	 ));
	
	Route::get('attachment/download/{id}', array(
				'as' => 'attachment.download.id',
				'uses' => 'AttachmentController@downloadAttachment'
	 ));
	
	Route::resource('attachment', 'AttachmentController');
	
    /* end Attachment */
	
});


	
	
/** test entrust **/
Route::get('/start', function()
{
    $subscriber = new Role();
    $subscriber->name = 'Subscriber';
    $subscriber->save();
 
    $author = new Role();
    $author->name = 'Author';
    $author->save();
 
    $read = new Permission();
    $read->name = 'can_read';
    $read->display_name = 'Can Read Posts';
    $read->save();
 
    $edit = new Permission();
    $edit->name = 'can_edit';
    $edit->display_name = 'Can Edit Posts';
    $edit->save();
 
    $subscriber->attachPermission($read);
    $author->attachPermission($read);
    $author->attachPermission($edit);
 
    $user1 = Login::find(3);
    $user2 = Login::find(4);
 
    $user1->attachRole($subscriber);
    $user2->attachRole($author);
 
    return 'Woohoo!';
});

