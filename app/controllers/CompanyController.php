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
			   
			   print_r($e->getMessage());
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
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
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
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function doUpdateStatus()
	{
		print_r(Input::get());
		
		$rules = array(
			'wfid' => 'required',  
			'status' => 'required'
		);
		
		// run the validation rules 
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			
			return Redirect::to('qa')
				->withErrors($validator);
			
		}
		
		$wfid = Input::get('wfid');

		$status = Input::get('status');
		
		$newStatus = $status + 1;

		try {
			$workflow = Workflow::where('row_id', '=', $wfid)->first();
		}
		catch(Exception $e) {
			// redirect with error
            Session::flash('error', 'Cannot find workflow with ID:  '. $wfid );
			return Redirect::to('prepare');
		}
		
		//insert to wf history
		$workflowHistory = new WorkflowHistory;
		
		$workflowHistory->wf_id = $workflow->wf_id;
		$workflowHistory->fk_status = $workflow->fk_status;
		$workflowHistory->created = $workflow->created;
		$workflowHistory->modify = $workflow->modify;
		$workflowHistory->created_by = $workflow->created_by;
		$workflowHistory->modify_by = $workflow->modify_by;
						
		$workflowHistory->save();
		
		
		//Auth::user()->getUserData()->row_id
		//update workflow
		try {
			
			$workflow = Workflow::where('row_id', '=', $wfid)->first();
			
			$workflow->fk_status = $newStatus;
			$workflow->created = date("Y-m-d H:i:s");
			$workflow->modify = date("Y-m-d H:i:s");
			$workflow->created_by = Auth::user()->getUserData()->row_id;
			$workflow->modify_by = Auth::user()->getUserData()->row_id;
					 
			$workflow->save();
					 
			Session::flash('message', 'Workflow successfully updated');
			return Redirect::to('qa');
					 
		}
		catch(Exception $e)
		{
			Session::flash('error', $e->getMessage() );
			return Redirect::to('qa');
			
		}
		
		
	}




}
