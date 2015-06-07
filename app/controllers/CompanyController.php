<?php

class CompanyController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
	  
		$companies = Company::orderBy('company_name')->get();
		
		//get company admin and etc
		foreach($companies as $company)
		{ 
			try {
			   
			   $user = User::where('row_id', '=', $company->fk_admin)->first();
			   $company->admin_name = $user->first_name. ' ' . $user->last_name;
			   $company->admin_username = $user->username;
			}
			catch(Exception $e)
			{
			   
			   $company->admin_name = '';
			   $company->admin_username = '';
			}
		 
		}
	 
		// load the view and pass the data
        return View::make('company.index')
               ->with('companies', $companies);
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
			'app_domain'       => 'required',
			
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
		$company->app_domain      = Input::get('app_domain');
		$company->uuid = Uuid::generate()->string;
		$company->save();
        
		$logDetails = json_encode(['row_id' => $company->row_id]);
		
		
		Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'admin_company_store',
				'action'      => 'Created',
				'description' => 'Created New Company',
				'details'     => $logDetails,
				'updated'     => false,
		]);
		
		$apiData = [
					'uuid' => $company->uuid,
					'company_id' => $company->row_id,
					'company_name' => $company->company_name,
					'app_domain'	=> $company->app_domain
					];

		$apiUrl = Config::get('app.login_app_domain') .'/api/v1/logincompany/sync';

		$response = Curl::post($apiUrl, $apiData);
 
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
		$company = Company::find($id);
		
        try {
			$user = User::where('row_id', '=', $company->fk_admin)->first();
			$company->admin_name = $user->first_name. ' ' . $user->last_name;
			$company->admin_username = $user->username;
		}
		catch(Exception $e)
		{
		   
		   $company->admin_name = '';
		   $company->admin_username = '';
		}
		// load the view and pass the data
        return View::make('company.show')
               ->with('company', $company);
		
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
		
		$company = Company::find($id);
		
		
		
		$companyUsers = null;
		
        try {
			$companyUsers = User::where('fk_empresa', '=', $company->row_id)
			                      ->where('status', '=', 'X')->get();
		}
		catch(Exception $e)
		{
		   
			$companyUsers = null;
		}
		
		$companyUsersDropdown = array();
	
		
		if($companyUsers)
		{
			foreach($companyUsers as $user)
			{
				$companyUsersDropdown[$user->row_id] = $user->first_name . ' ' . $user->last_name . ' (' .$user->username. ')';
				
			}
		}

		// load the view and pass the data
        return View::make('company.edit')
               ->with('company', $company)
			   ->with('companyUsersDropdown', $companyUsersDropdown);
			   
		
		
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
            'company_name'       => 'required',
            'company_email'      => 'email',
            'fk_admin' => 'integer',
			'fk_terms' => 'integer',
			'creditlimit' => 'integer',
			'company_zip' => 'max:5',
			'app_domain'       => 'required',
			
        );
        $validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
            return Redirect::to('company/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }
		
		 // store
		$company = Company::find($id);
		$company->company_name       = Input::get('company_name');
		$company->company_address1      = Input::get('company_address1');
		$company->company_address2      = Input::get('company_address2');
		$company->company_zip      = Input::get('company_zip');
		$company->fk_admin      = Input::get('fk_admin');
		$company->company_phone      = Input::get('company_phone');
		$company->company_fax      = Input::get('company_fax');
		$company->company_email      = Input::get('company_email');
		$company->fk_terms      = Input::get('fk_terms');
		$company->creditlimit      = Input::get('creditlimit');
		$company->app_domain      = Input::get('app_domain');
		$company->uuid =  $company->uuid ? $company->uuid : Uuid::generate()->string;
		$company->save();
     
	 
	    $logDetails = json_encode(['row_id' => $id ]);
		
		Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'admin_company_update',
				'action'      => 'Updated',
				'description' => 'Updated Company Details',
				'details'     => $logDetails,
				'updated'     => true,
		]);
		 
		 $apiData = [
					'uuid' => $company->uuid,
					'company_id' => $company->row_id,
					'company_name' => $company->company_name,
					'app_domain'	=> $company->app_domain
					];

		$apiUrl = Config::get('app.login_app_domain') .'/api/v1/logincompany/sync';

		Curl::post($apiUrl, $apiData);

		// redirect
		Session::flash('message', 'Company info updated');
		return Redirect::to('company/'. $id .'/edit');
		
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
