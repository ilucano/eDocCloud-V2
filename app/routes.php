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
	
    Route::resource('pickup', 'PickupController');
	
	Route::resource('prepare', 'PrepareController');
	
	Route::post('prepare/status', array('uses' => 'PrepareController@doUpdateStatus'));
	
	Route::resource('scan', 'ScanController');
	
	Route::post('scan/status', array('uses' => 'ScanController@doUpdateStatus'));
	
	Route::resource('qa', 'QAController');
	
	Route::post('qa/status', array('uses' => 'QAController@doUpdateStatus'));
	
	Route::resource('ocr', 'OCRController');
	
	Route::post('ocr/status', array('uses' => 'OCRController@doUpdateStatus'));
	
});


