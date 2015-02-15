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
		
		
		Input::merge(array_map('trim', Input::except('password')));
 
		$rules = array(
			'label' => 'required',			
        );
		 
        $validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
            return Redirect::to('companyadmin/filemark/create')
                ->withErrors($validator)
                ->withInput();
        }
		
		//check if exist
		$markCount = Filemark::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)
							 ->where('label', '=', Input::get('label'))
							 ->count();
		
		if($markCount >= 1)
		{
			Session::flash('error', '<strong>'.Input::get('label') .'</strong> already exists');
			return Redirect::to('companyadmin/filemark/create');
			exit;
		}
		
		
		//create login
		$filemark = new Filemark;
		$filemark->label = Input::get('label');
		$filemark->global = 0;
		$filemark->fk_empresa = Auth::User()->getUserData()->fk_empresa;
		$filemark->create_date = date("Y-m-d H:i:s");
		$filemark->save();
	
	         
		Session::flash('message', '<strong>'.Input::get('label') .'</strong> successfully created');
			
		return Redirect::to('companyadmin/filemark');
	
		 
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
	   
	   	$filemark = Filemark::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)
								->find($id);
         
		 
	
        return View::make('companyadmin.filemark.edit')
               ->with('filemark', $filemark);

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
	
		Input::merge(array_map('trim', Input::except('password')));
 
		$rules = array(
			'label' => 'required',			
        );
		 
        $validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
            return Redirect::to('companyadmin/filemark/create')
                ->withErrors($validator)
                ->withInput();
        }
		
		//check if exist of same name 
		$markCount = Filemark::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)
							 ->where('label', '=', Input::get('label'))
							 ->where('id', '<>', $id)
							 ->count();
		
		if($markCount >= 1)
		{
			Session::flash('error', '<strong>'.Input::get('label') .'</strong> already exists');
			return Redirect::to('companyadmin/filemark/'.$id.'/edit');
			exit;
		}
		
		
		//create login
		$filemark = Filemark::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)->find($id);
		$filemark->label = Input::get('label');
		$filemark->save();
	
	         
		Session::flash('message', 'Filemark successfully updated');
			
		return Redirect::to('companyadmin/filemark');
	
		
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
