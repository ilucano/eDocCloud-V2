<?php

class AdministratorController extends \BaseController {


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $systemAdminCompanyId = Config::get('app.system_admin_company_id');
		
		$adminCompanyName =  Company::where('row_id', '=', $systemAdminCompanyId)->first()->company_name;
		
		$users = User::where('fk_empresa', '=', $systemAdminCompanyId)->orderBy('is_admin')->orderBy('username', 'ASC')->get();
    
 
		// load the view and pass the data
        return View::make('administrator.index')
			   ->with('adminCompanyName', $adminCompanyName)
               ->with('users', $users);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
	 
	}
	
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
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
		
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		 
		
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
	
	
	
	/**
	 * Update the specified users in user is_admin field
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function doUpdateIsAdmin()
	{
	  
		$rules = array(
			'row_id' => 'required'
		);
		
		// run the validation rules 
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			
			return Redirect::to('administrator')
				->withErrors($validator);
			
		}
 
		//update user
		try {
			
			$user = User::where('row_id', '=', Input::get('row_id'))
			              ->where('fk_empresa', '=',  Config::get('app.system_admin_company_id'))
						  ->first();
			$user->is_admin = Input::get('is_admin');
			 	 
			$user->save();
			
			$stringResult = (Input::get('is_admin') == 'X') ? 'added' : 'removed';
			
			
				Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'admin_update_admin',
				'action'      => 'Updated',
				'description' => 'Adminstrator Status Updated',
				'details'     => 'User ID: '.Input::get('row_id')  .', Is admin: '. Input::get('is_admin') ,
				'updated'     => true,
			]);
			
			
			Session::flash('message', 'Administrator successfully '.$stringResult);
			return Redirect::to('administrator');
		}
		catch(Exception $e)
		{
			Session::flash('error', $e->getMessage() );
			return Redirect::to('administrator');
			
		}
		
		
	}

	




}
