<?php

class ScanController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$workflows = Workflow::where('fk_status', '=', 6)
		                       ->orWhere(
										 function ($query)
										 {
											$query->where('fk_status', '=', 7)
											      ->where('created_by', '=', Auth::user()->getUserData()->row_id);
											
										 }
								)->get();
							   
		
		
		foreach($workflows as $workflow)
		{
			//get status
			$workflow->status = WorkflowStatus::where('row_id', '=', $workflow->fk_status)->firstOrFail()->status;
			$_pickup = Pickup::where('fk_barcode', '=', $workflow->wf_id)->firstOrFail();
			$_object = Object::where('row_id', '=', $_pickup->fk_box)->firstOrFail();
			
			$workflow->boxid = $_object->row_id;
			$workflow->company_name = Company::where('row_id', '=', $_object->fk_company)->firstOrFail()->company_name;
            
			try {
			   $workflow->attach = Attach::where('fk_obj_id', '=' , $workflow->boxid)->select('row_id', 'attach_name')->firstOrFail();
			}
			catch (Exception $e)
			{
			   $workflow->attach = null;
			}
			
			
		}
		
		
		// load the view and pass the data
        return View::make('scan.index')
               ->with('workflows', $workflows);
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
	  
		$rules = array(
			'wfid' => 'required',  
			'status' => 'required'
		);
		
		// run the validation rules 
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			
			return Redirect::to('scan')
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
			return Redirect::to('scan');
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
			
			$logDetails = json_encode(['row_id' => $wfid,
									   'fk_status' => $newStatus]);
				
			Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'admin_scan_update',
				'action'      => 'Updated',
				'description' => 'Scan Status Updated',
				'details'     => $logDetails,
				'updated'     => true,
			]);
						
			Session::flash('message', 'Workflow successfully updated');
			return Redirect::to('scan');
					 
		}
		catch(Exception $e)
		{
			Session::flash('error', $e->getMessage() );
			return Redirect::to('scan');
			
		}
		
		
	}




}
