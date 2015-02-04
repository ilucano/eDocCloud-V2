<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}
	
	/**
	 * Login Form
	 *
	 */
	public function showLogin()
	{
		// show the form
		return View::make('home.login');
	}
	
	
	/* 
	 * process login
	 */
	
	public function doLogin()
	{
		// validate the info
		$rules = array(
			'username'    => 'required',  
			'password' => 'required'
		);
    
	
		// run the validation rules 
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('login')
				->withErrors($validator) 
				->withInput(Input::except('password')); 
			
			exit;
		}
 
		// create our user data for the authentication
		$userdata = array(
			'username'     => Input::get('username'),
			'password'  => Input::get('password')
		);
	
		// attempt to do the login
		if (Auth::attempt($userdata)) {
			// validation successful!
			// return Redirect::to('secure');
			// for now we'll just echo success (even though echoing in a controller is bad)
			echo 'SUCCESS!';
	
		} else {        
 
			// redirect
            Session::flash('error', 'Invalid Username or Password');
			
			return Redirect::to('login');
	
		}
 
	}

}
