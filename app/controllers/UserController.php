<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
	    
		
		
		$users = User::orderBy('username')->get();

		//get company admin and etc
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
        return View::make('user.index')
               ->with('users', $users);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		// load the view and pass the data
        return View::make('company.create');
	
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
		$rules = array(
            'company_name'       => 'required',
            'company_email'      => 'email',
			'fk_terms' => 'integer',
			'creditlimit' => 'integer',
			'company_zip' => 'max:5',
			'group_id' => 'integer'
			
        );
		
        $validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
            return Redirect::to('company/create')
                ->withErrors($validator)
                ->withInput();
        }
		
		$company = new Company;
		
		$company->company_name       = Input::get('company_name');
		$company->company_address1      = Input::get('company_address1');
		$company->company_address2      = Input::get('company_address2');
		$company->company_zip      = Input::get('company_zip');
		$company->fk_admin      = 0; //new company
		$company->company_phone      = Input::get('company_phone');
		$company->company_fax      = Input::get('company_fax');
		$company->company_email      = Input::get('company_email');
		$company->fk_terms      = Input::get('fk_terms');
		$company->creditlimit      = Input::get('creditlimit');
		$company->save();

		// redirect
		Session::flash('message', $company->company_name . ' successfuly created');
 
		return Redirect::to('company');

		
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::find($id);
		
		
		try {
			   
			   $company = Company::where('row_id', '=', $user->fk_empresa)->first();
			   $user->company_name = $company->company_name;
			   
		}
		catch(Exception $e)
		{
		   
		   $user->company_name = '';
		   
		}
		
		try {
			   
			   $group = Group::where('row_id', '=', $user->group_id)->first();
			   $user->group_name = $group->nombre;
			   
		}
		catch(Exception $e)
		{
		   
			$user->group_name = '';
		   
		}
		
        return View::make('user.show')
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
	   
		$user = User::find($id);
	
		$userCompanies = null;
		
        try {
			
			$userCompanies = Company::orderBy('company_name')->get();
		}                       
		catch(Exception $e)
		{
		   
			$userCompanies = null;
		}
		
		$userCompaniesDropdown = array();
	
		
		if($userCompanies)
		{
			foreach($userCompanies as $company)
			{
				$userCompaniesDropdown[$company->row_id] = $company->company_name;
				
			}
		}
		
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
		
		
		
		$companyAdminDropdown = array('X' => 'Yes', '' => 'No');
		
        $activeDropDown = array('X' => 'Yes', '' => 'No');
		
		// load the view and pass the data
        return View::make('user.edit')
               ->with('user', $user)
			   ->with('userCompaniesDropdown', $userCompaniesDropdown)
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
            'fk_empresa'       => 'required|integer',
            'password' => 'min:5',
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'email|required'
			
        );
		 
        $validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
            return Redirect::to('user/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }

		 // store 
		$user = User::find($id);
		$user->first_name       = Input::get('first_name');
		$user->last_name 		= Input::get('last_name');
		$user->email 		= Input::get('email');
		$user->phone 		= Input::get('phone');
		$user->fk_empresa 		= Input::get('fk_empresa');
		//$user->is_admin 		= Input::get('fk_empresa'); //to be done
		$user->status  = Input::get('status');
		$user->group_id  = Input::get('group_id');
		$user->company_admin  = Input::get('company_admin');
		
		$user->save();

		// redirect
		Session::flash('message', 'User updated');
		return Redirect::to('user/'. $id .'/edit');
		
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
