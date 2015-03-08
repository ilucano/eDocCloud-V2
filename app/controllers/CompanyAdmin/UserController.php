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

		
		$filemarkDropdown = array();
		
		try {
			
			$filemarks = Filemark::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)
								->orWhere('global', '=', 1)
								->orderBy('global', 'desc')
								->orderBy('label')->get();
			
			foreach($filemarks as $filemark)
			{
				$filemarkDropdown[$filemark->id] = $filemark->label;
			}
		}
		catch(Exception $e)
		{
			$filemarkDropdown = array();
			
		}
		
		
		$roleDropdown = array();
		
		try {
			
			$companyRoles = Role::where('fk_empresa', '=', $fkEmpresa)->orderBy('name')->get();
			foreach($companyRoles as $role)
			{
				$roleDropdown[$role->id] = $role->name;
			}
			
		}                       
		catch(Exception $e)
		{
		   
			$userRoles = null;
		}
		
		
		$companyAdminDropdown = array('X' => 'Yes', '' => 'No');
		
		$activeDropDown = array('X' => 'Yes', '' => 'No');
		
		
		//show step 2
		return View::make('companyadmin.user.create')
					->with('fkEmpresa', $fkEmpresa)
					->with('companyAdminDropdown', $companyAdminDropdown)
					->with('activeDropDown', $activeDropDown)
					->with('roleDropdown', $roleDropdown)
					->with('filemarkDropdown', $filemarkDropdown);
	
	}
  
  
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		
		Input::merge(array_map('trim', Input::except('password','file_permission', 'assigned_roles')));
 
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
		
		$login->attachRoles(Input::get('assigned_roles'));
		
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
        $user->file_permission = is_array(Input::get('file_permission')) ? json_encode(Input::get('file_permission')) : null;
		
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
		
	    
		$logDetails = json_encode(['row_id' => $user->row_id]);
		
		Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'companyadmin_user_store',
				'action'      => 'Created',
				'description' => 'New User Created',
				'details'     => $logDetails,
				'updated'     => false,
			]);
		
		
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
 
		$filemarkDropdown = array();
		
		try {
			
			$filemarks = Filemark::where('fk_empresa', '=', $user->fk_empresa)
								->orWhere('global', '=', 1)
								->orderBy('global', 'desc')
								->orderBy('label')->get();
			
			foreach($filemarks as $filemark)
			{
				$filemarkDropdown[$filemark->id] = $filemark->label;
			}
		}
		catch(Exception $e)
		{
			$filemarkDropdown = array();
			
		}
		
		$roleDropdown = array();
		
		try {
			
			$companyRoles = Role::where('fk_empresa', '=', $user->fk_empresa)->orderBy('name')->get();
			foreach($companyRoles as $role)
			{
				$roleDropdown[$role->id] = $role->name;
			}
			
		}                       
		catch(Exception $e)
		{
		   
			$userRoles = null;
		}
		
		 
		$assignedRoles = array();
		
		try {

			$roles = Login::where('username', '=', $user->username)->first()->roles;
			
			foreach($roles as $role)
			{
				$assignedRoles[] = $role->id;
			}
			 
		}
		catch(Exception $e)
		{
			$assignedRoles = array();
		}
	
	    
		
		
        return View::make('companyadmin.user.show')
               ->with('user', $user)
			   	->with('roleDropdown', $roleDropdown)
			   ->with('assignedRoles', $assignedRoles)
			   ->with('filemarkDropdown', $filemarkDropdown);
		
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
		
		$companyAdminDropdown = array('' => 'No', 'X' => 'Yes');
		
        $activeDropDown = array('X' => 'Yes', '' => 'No');
		
		$filemarkDropdown = array();
		
		try {
			
			$filemarks = Filemark::where('fk_empresa', '=', $user->fk_empresa)
								->orWhere('global', '=', 1)
								->orderBy('global', 'desc')
								->orderBy('label')->get();
			
			foreach($filemarks as $filemark)
			{
				$filemarkDropdown[$filemark->id] = $filemark->label;
			}
		}
		catch(Exception $e)
		{
			$filemarkDropdown = array();
			
		}
		
		$roleDropdown = array();
		
		try {
			
			$companyRoles = Role::where('fk_empresa', '=', $user->fk_empresa)->orderBy('name')->get();
			foreach($companyRoles as $role)
			{
				$roleDropdown[$role->id] = $role->name;
			}
			
		}                       
		catch(Exception $e)
		{
		   
			$userRoles = null;
		}
		
		 
		$assignedRoles = array();
		try {

			$roles = Login::where('username', '=', $user->username)->first()->roles;
			
			foreach($roles as $role)
			{
				$assignedRoles[] = $role->id;
			}
			 
		}
		catch(Exception $e)
		{
			$assignedRoles = array();
		}
	
	    
		// load the view and pass the data
        return View::make('companyadmin.user.edit')
               ->with('user', $user)
			   ->with('companyAdminDropdown', $companyAdminDropdown)
			   ->with('activeDropDown', $activeDropDown)
			   ->with('roleDropdown', $roleDropdown)
			   ->with('assignedRoles', $assignedRoles)
			   ->with('filemarkDropdown', $filemarkDropdown);

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
		Input::merge(array_map('trim', Input::except('password','file_permission', 'assigned_roles')));
		
		 $rules = array(
            'password' => 'min:5',
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'email|required',
			'assigned_roles' => 'required',

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
		
		$user->file_permission = is_array(Input::get('file_permission')) ? json_encode(Input::get('file_permission')) : null;
		
		$user->save();
		
		
		try  {
			$login = Login::where('username', '=', $user->username)->first();
			
			if($login) {
				
				if(Input::get('password')) {
					$login->password = Hash::make(Input::get('password'));
				}
				
				$login->active = (Input::get('status') == 'X') ? 1: 0;
				
				$login->save();
				
				//detach existing roles
				$oldRoles = $login->roles;
				$login->detachRoles($oldRoles);
				$login->attachRoles(Input::get('assigned_roles'));
				
				
			}
		
		}
		catch (Exception $e)
		{
			Log::error($exception);
		}
        
		
		$logDetails = json_encode(['row_id' => $id]);
		
		Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'companyadmin_user_updated',
				'action'      => 'Updated',
				'description' => 'User Updated',
				'details'     => $logDetails,
				'updated'     => true,
			]);
		
		
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
