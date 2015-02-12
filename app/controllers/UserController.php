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
		// load the view and pass the data
        return View::make('user.create')
			  ->with('userCompaniesDropdown', $userCompaniesDropdown);
	
	}
  
  
    /**
	 *
	 *
	 */
	
	public function createStep2($fk_empresa)
	{
		
        $fkEmpresa = $fk_empresa;
		
		$companyName = Company::where('row_id', '=', $fkEmpresa)->first()->company_name;
		
		
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
		return View::make('user.create_step2')
					->with('fkEmpresa', $fkEmpresa)
					->with('companyName', $companyName)
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
		
		//post step1
	 
		$rules = array(
				'fk_empresa'       => 'required|integer',
			);
		   
		
		 $validator = Validator::make(Input::all(), $rules);
	
		if ($validator->fails()) {
			return Redirect::to('user/create')
				->withErrors($validator)
				->withInput();
		}
		
		//Redirect to form 2
		return Redirect::to('user/create/company/'.Input::get('fk_empresa'));
		 
	}
    
	
		/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function storeStep2()
	{
		
		//post step2
	 
		$rules = array(
            'fk_empresa'       => 'required|integer',
			'username' => 'required|unique:users|min:3',
            'password' => 'required|min:5',
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'email|required'
			
        );
		 
        $validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
            return Redirect::to('user/create/company/'. Input::get('fk_empresa'))
                ->withErrors($validator)
                ->withInput();
        }
		
		

		 
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
		
		
		
		$companyAdminDropdown = array('' => 'No', 'X' => 'Yes');
		
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
