<?php

class AttachmentController extends BaseController {

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
	
	
	public function showIndex()
	{
		
		if(Auth::User()->isAdmin()) {
			return View::make('home.index');
		}
		else {
			return Redirect::to('users/order');
		}
	
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
			'password'  => Input::get('password'),
			'active'	=> 1
		);
	 
		if (Auth::attempt($userdata)) {
			// validation successful!
			return Redirect::to('home');
		 
	
		} else {        
 
			// redirect
            Session::flash('error', 'Invalid Username or Password');
			
			return Redirect::to('login');
	
		}
 
	}
	
	
	public function doLogout()
	{
		Auth::logout(); // log the user out of our application
		return Redirect::to('login'); // redirect the user to the login screen
	}

    
	public function downloadFile($id)
	{
		if(Auth::User()->getCompanyId() ==  Config::get('app.system_admin_company_id'))
		{
			try {
				//imagingXperts staff can download all
				$file = FileTable::where('row_id', '=', $id)
									->first(array('path'));
			}
			catch (Exception $e)
			{
				echo "File not found";
				exit;
			} 
								
		}
        else {
			
			try {
				$file = FileTable::where('fk_empresa', '=', Auth::User()->getCompanyId() )
									->where('row_id', '=', $id)
									->where(function($query)
										{
											$query->whereIn('file_mark_id', json_decode(Auth::User()->getUserData()->file_permission, true))
												  ->orWhere('file_mark_id','=', '')
												  ->orWhereRaw('file_mark_id is null');
										}
									  )
									->first(array('path'));
									
			}
			catch (Exception $e)
			{
				echo "Invalid file id or permission denied.";
				exit;
			}
			
		}
	 
		$attachment = Config::get('app.archive_path') . $file->path;
		return Response::download($attachment);
		
	}
	
	
	
	public function downloadAttachment($id)
	{
		if( Auth::User()->getCompanyId() ==  Config::get('app.system_admin_company_id') || Auth::User()->isAdmin() )
		{
			try {
				//imagingXperts staff can download all
				$file = Attach::where('row_id', '=', $id)
									->first(array('attach_path'));
			}
			catch (Exception $e)
			{
				echo "File not found";
				exit;
			} 
								
		}
        else
		{
			echo "Invalid attachment id or permission denied.";
			exit;
			
		}
 
		return Response::download($file->attach_path);
		
	}
	
}