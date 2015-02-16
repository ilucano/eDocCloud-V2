<?php

class OrderController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$objects = Object::where('fk_obj_type', '=', 1)->orderBy('fk_status')->get();
		
	
		
		foreach($objects as $object)
		{
		    
			$object->company_name = Company::where('row_id', '=', $object->fk_company)->firstOrFail()->company_name;
		    $object->status = OrderStatus::find($object->fk_status)->status;
			
		}

		// load the view and pass the data
        return View::make('order.index')
               ->with('objects', $objects);
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
		$companies = Company::orderBy('company_name')->get();
		
		
		
		$companyDropdown = array();
		
		foreach($companies as $company)
		{
			$companyDropdown[$company->row_id] = $company->company_name;
		}
		
		
		$object = Object::find($id);
		$status = OrderStatus::find($object->fk_status)->status;
		
			// load the view and pass the data
        return View::make('order.edit')
			   ->with('companyDropdown', $companyDropdown)
               ->with('object', $object)
			   ->with('status', $status);
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
			'fk_company' => 'required|integer',
			'f_code' => 'required',
			'f_name' => 'required',
			'ppc' => 'required|numeric',
			'qty' => 'required|integer',
			
		 );
		 
		  
		 $validator = Validator::make(Input::all(), $rules);
		 
		 if ($validator->fails()) {
			 return Redirect::to('order/' . $id . '/edit')
				 ->withErrors($validator)
				 ->withInput();
		 }
		 
		 $object = Object::find($id);
		 $object->fk_company = Input::get('fk_company');
		 $object->f_code = Input::get('f_code');
		 $object->f_name = Input::get('f_name');
		 $object->ppc = Input::get('ppc');
		 $object->qty = Input::get('qty');
		 
		 $object->save();
		
		 Session::flash('message', 'Order updated');
		 return Redirect::to('order/'. $id .'/edit');
	  
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
			
			return Redirect::to('prepare')
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
			return Redirect::to('prepare');
					 
		}
		catch(Exception $e)
		{
			Session::flash('error', $e->getMessage() );
			return Redirect::to('prepare');
			
		}
		
		
	}




}
