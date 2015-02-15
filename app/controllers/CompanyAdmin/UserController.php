<?php

class CompanyAdminUserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		$users = User::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)
		            ->orderBy('username')->get();

		//get company user of that company only
		foreach($users as $user)
		{ 
			try {
			   
			   $company = Company::where('row_id', '=', $user->fk_empresa)->first();
			   $user->company_name = $company->company_name;
			   
			}
			catch(Exception $e)
			{
			   
			   $user->company_name = '';
			   
			}
			
		}
		
 
		// load the view and pass the data
        return View::make('companyadmin.user.index')
               ->with('users', $users);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$fkEmpresa =  Auth::User()->getUserData()->fk_empresa;
	
		$userGroups = null;

		try {
			
			$userGroups = Group::where('fk_empresa', '=', $fkEmpresa)->orderBy('nombre')->get();
		}                       
		catch(Exception $e)
		{
		   
			$userGroups = null;
		}
	 
		
		$userGroupsDropdown= array();
		
		if($userGroups)
		{
			foreach($userGroups as $group)
			{
				$userGroupsDropdown[$group->row_id] = $group->nombre;
				
			}
		}
		
		
		
		$companyAdminDropdown = array('X' => 'Yes', '' => 'No');
		
		$activeDropDown = array('X' => 'Yes', '' => 'No');
		
		
		//show step 2
		return View::make('companyadmin.user.create')
					->with('fkEmpresa', $fkEmpresa)
					->with('userGroupsDropdown', $userGroupsDropdown)
					->with('companyAdminDropdown', $companyAdminDropdown)
					->with('activeDropDown', $activeDropDown);
	
	}
  
  
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		
		Input::merge(array_map('trim', Input::except('password')));
 
		$rules = array(
			'username' => 'required|unique:users|unique:logins|min:3',
            'password' => 'required|min:5',
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'email|required'
			
        );
		 
        $validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
            return Redirect::to('companyadmin/user/create')
                ->withErrors($validator)
                ->withInput();
        }
      
		//create login
		$login = new Login;
		$login->username = Input::get('username');
		$login->password = Hash::make(Input::get('password'));
		$login->active = (Input::get('status') == 'X') ? 1: 0;
		$login->save();
		
		//create new user profile
		$user = new User;
		$user->username = Input::get('username');
		$user->password = Input::get('password');
		$user->first_name = Input::get('first_name');
		$user->last_name = Input::get('last_name');
		$user->email = Input::get('email');
		$user->phone = Input::get('phone');
		$user->fk_empresa = Auth::User()->getUserData()->fk_empresa;
		$user->status = Input::get('status');
		$user->company_admin = Input::get('company_admin');
		$user->group_id = Input::get('group_id');
        
		$user->save();
		
 
		$mailJobData = array('to' => Input::get('email'),
							 'username' => Input::get('username'),
							 'password' => Input::get('password'),
							 'first_name' => Input::get('first_name'),
							 'last_name' => Input::get('last_name'),
							 'fk_empresa' => Input::get('fk_empresa'),
							);

		//setup new job queue instead of email user immediately
		$jobQueue = new Jobqueue;
		$jobQueue->job_type = 'mail_create_user';
		$jobQueue->job_data = json_encode($mailJobData);
		$jobQueue->save();
		
	    
		Session::flash('message', 'User successfully created');
			
		return Redirect::to('companyadmin/user');
	
		 
	}
    
	

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)->find($id);
		
		
		try {
			   
			   $group = Group::where('row_id', '=', $user->group_id)->first();
			   $user->group_name = $group->nombre;
			   
		}
		catch(Exception $e)
		{
		   
			$user->group_name = '';
		   
		}
		
        return View::make('companyadmin.user.show')
               ->with('user', $user);
		
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	   
		$user = User::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)->find($id);
	
		$userCompanies = null;
		
		$userGroups = null;
		
		try {
			
			$userGroups = Group::where('fk_empresa', '=', $user->fk_empresa)->orderBy('nombre')->get();
		}                       
		catch(Exception $e)
		{
		   
			$userGroups = null;
		}
	 
		
		$userGroupsDropdown= array();
		
		if($userGroups)
		{
			foreach($userGroups as $group)
			{
				$userGroupsDropdown[$group->row_id] = $group->nombre;
				
			}
		}
		
		
		
		$companyAdminDropdown = array('' => 'No', 'X' => 'Yes');
		
        $activeDropDown = array('X' => 'Yes', '' => 'No');
		
		// load the view and pass the data
        return View::make('companyadmin.user.edit')
               ->with('user', $user)
			   ->with('companyAdminDropdown', $companyAdminDropdown)
			   ->with('activeDropDown', $activeDropDown)
			   ->with('userGroupsDropdown', $userGroupsDropdown);

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
		
		 $rules = array(
            'password' => 'min:5',
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'email|required'
			
        );
		 
        $validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
            return Redirect::to('companyadmin/user/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }

		 // store 
		$user = User::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)->find($id);
		
		$user->first_name       = Input::get('first_name');
		$user->last_name 		= Input::get('last_name');
		$user->email 		= Input::get('email');
		$user->phone 		= Input::get('phone');
	  
	    if(Input::get('password')) {
			$user->password = Input::get('password');
		}
		
		$user->status  = Input::get('status');
		$user->group_id  = Input::get('group_id');
		$user->company_admin  = Input::get('company_admin');
		
		$user->save();
		
		
		try  {
			$login = Login::where('username', '=', $user->username)->first();
			
			if($login) {
				
				if(Input::get('password')) {
					$login->password = Hash::make(Input::get('password'));
				}
				
				$login->active = (Input::get('status') == 'X') ? 1: 0;
				
				$login->save();
				
			}
		
		}
		catch (Exception $e)
		{
			Log::error($exception);
		}

		// redirect
		Session::flash('message', 'User updated');
		return Redirect::to('companyadmin/user/'. $id .'/edit');
		
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	
	




}
