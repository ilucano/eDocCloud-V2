<?php

class CompanyAdminRoleController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		
		$roles = Role::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)
					   ->orderBy('name')->get();
		

		// load the view and pass the data
        return View::make('companyadmin.role.index')
               ->with('roles', $roles);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		
		$permissions = Permission::where('name', 'NOT LIKE', 'system_admin%')->orderBy('display_name')->get();
		
		return View::make('companyadmin.role.create')
					->with('permissions', $permissions);
	
	}
  
   
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		
		Input::merge(array_map('trim', Input::except('password','permission_list')));
 
	
		$rules = array(
			'name' => 'required',
			'permission_list' => 'required'
        );
		 
		$messages = array(
			'permission_list.required' => 'Please select at least one permission',
		);
		
        $validator = Validator::make(Input::all(), $rules, $messages);
		
		if ($validator->fails()) {
            return Redirect::to('companyadmin/role/create')
                ->withErrors($validator)
                ->withInput();
        }
		
		
		$existCount = Role::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)
							 ->where('name', '=', Input::get('name'))
							 ->count();
		
		if($existCount >= 1)
		{
			Session::flash('error', '<strong>'.Input::get('name') .'</strong> already exists');
			return Redirect::to('companyadmin/role/create');
			exit;
		}
		
		$role = new Role;
		$role->name = Input::get('name');
		$role->fk_empresa = Auth::User()->getUserData()->fk_empresa;
		$role->save();
		
		$role->perms()->sync(Input::get('permission_list'));
	         
		Session::flash('message', '<strong>'.Input::get('name') .'</strong> successfully created');
			
		return Redirect::to('companyadmin/role');
	
		 
	}
    
	

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
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
		$role = Role::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)->find($id);
	
		
		$arraySelectedPermission = array();
			
		$rolePermissions = $role->perms;
		
		foreach($rolePermissions as $rolePermission)
		{
			$arraySelectedPermission[] = $rolePermission->id;
		}

		$permissionList = Permission::where('name', 'NOT LIKE', 'system_admin%')->orderBy('display_name')->get();
		
		return View::make('companyadmin.role.edit')
					->with('permissionList', $permissionList)
					->with('arraySelectedPermission',$arraySelectedPermission)
					->with('role', $role);
					
	 

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		
		
		Input::merge(array_map('trim', Input::except('password','permission_list')));
 
	
		$rules = array(
			'name' => 'required',
			'permission_list' => 'required'
        );
		 
		$messages = array(
			'permission_list.required' => 'Please select at least one permission',
		);
		
        $validator = Validator::make(Input::all(), $rules, $messages);
		
		if ($validator->fails()) {
            return Redirect::to('companyadmin/role/'.$id. '/edit')
                ->withErrors($validator)
                ->withInput();
        }
		
		
		$existCount = Role::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)
							 ->where('name', '=', Input::get('name'))
							 ->where('id', '<>', $id)
							 ->count();
		
		if($existCount >= 1)
		{
			Session::flash('error', '<strong>'.Input::get('name') .'</strong> already exists');
			return Redirect::to('companyadmin/role/'.$id. '/edit');
			exit;
		}
		
		$role = Role::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)->find($id);
		$role->name = Input::get('name');
		$role->save();
		
		$role->perms()->sync(Input::get('permission_list'));
	         
		Session::flash('message', '<strong>'.Input::get('name') .'</strong> successfully updated');
			
		return Redirect::to('companyadmin/role');
	
		
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
