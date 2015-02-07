<?php

class PickupController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		$orderDropdown = array();
		
		
		
		$orderLists = Object::where('fk_status', '<>', 5)
						  ->where('fk_obj_type', '=', 1)
						  ->get();
		
         
		foreach($orderLists as $orderList)
		{
			$companyName = Company::where('row_id', '=', $orderList->fk_company)->first()->company_name;
			
			$orderList->company_name = $companyName;
			
			$niceLabel = $orderList->f_code. '- ' . $orderList->f_name . ' ' . $orderList->company_name;
			$orderDropdown[$orderList->row_id] =  $niceLabel;
							 
		}
		
		
        $barcodeLists = Barcode::whereIn('fk_user', array('0', Auth::user()->getUserData()->row_id))->orderBy('barcode', 'asc')->get();
	 
		 // load the view and pass the data
        return View::make('pickup.index')
               ->with('orderLists', $orderDropdown)
			   ->with('barcodeLists', $barcodeLists);
			
	 
		
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
        
		
		// validate the info
		$rules = array(
			'object_id' => 'required',  
			'barcode' => 'required'
		);
        
		// run the validation rules 
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			
			return Redirect::to('pickup')
				->withErrors($validator);
			
		}
		
	    //prepare capture data for PDF mailing later
		$mailJobData = array();

	    $orderId = Input::get('object_id');
		
		$mailJobData['order_id'] = $orderId;
		
		
		//get fk_company from order (objects table)
		$object = Object::where('row_id', '=', $orderId)->first();
		
		if(!$object) {
			
			// redirect with error
            Session::flash('error', 'Cannot find order id '. $orderId );
			
			return Redirect::to('pickup');
		
			
		}
		
		$fkCompany = $object->fk_company;

		 
		$barcodeChecked = Input::get('barcode');
		
		$mailJobData['barcodes'] = $barcodeChecked;
		
		if(is_array($barcodeChecked))
		{
			
			foreach($barcodeChecked as $barcode)
			{ 
			    $arrayBoxInfo = array();
				
			    $boxId = $this->createNewObject($barcode, $fkCompany, $orderId);
				
			
				
                if($boxId)
				{
					$arrayBoxInfo['box_id'] = $boxId;
					$arrayBoxInfo['box_name'] = substr($barcode,0,6).substr($barcode,9);
					
					$mailJobData['boxes'][] = $arrayBoxInfo;
					
					$pickupId = $this->createNewPickup(Auth::user()->getUserData()->row_id, $fkCompany, $orderId, $barcode, $boxId);
					
					if($pickupId)
					{
					    $mailJobData['pickups'][] = $pickupId;
						
						$workflowId = $this->createNewWorkflow($barcode, Auth::user()->getUserData()->row_id);
						
						$mailJobData['workflows'][] = $workflowId;
						
						//delete the barcode
						
						Barcode::where('barcode', '=', $barcode)->delete();
						
					}
				}
				
			}
				
		}
		
		
		        
		$company = Company::where('row_id', '=', $fkCompany)->first();
		
		$mailJobData['company_email'] = $company->company_email;
		$mailJobData['company_name'] = $company->company_name;
		$mailJobData['company_address1'] = $company->company_address1;
		$mailJobData['company_address2'] = $company->company_address2;
		$mailJobData['company_zip'] = $company->company_zip;
		
		
		//setup new job queue instead of email pdf immediately
		$jobQueue = new Jobqueue;
		$jobQueue->job_type = 'mail_pickup_pdf';
		$jobQueue->job_data = json_encode($mailJobData);
		$jobQueue->save();
		
	    
		Session::flash('message', 'Pickup(s) successfully created');
			
		return Redirect::to('pickup');
		
		

	}
    
	/**
	 * Create new workflow record into workflow table
	 *
	 * @return integer $row_id Newly insert record id
	 * 
	 */
	private function createNewWorkflow($barcode, $user_id)
	{
		if($barcode == '') {
			return false;
		}
	
        $workflow = new Workflow;
		$workflow->wf_id = $barcode;
		$workflow->fk_status = 3;
		$workflow->modify = date("Y-m-d H:i:s");
		$workflow->created = date("Y-m-d H:i:s");
		$workflow->modify_by = $user_id;
		$workflow->created_by = $user_id;
		
		$workflow->save();
		
		return $workflow->id;
	
		
	}
    
	/**
	 * Create a new record into pickup table
	 *
	 * return integer $row_id Newly inserted record id
	 * 
	 */
	private function createNewPickup($fk_user, $fk_company, $order_id, $barcode, $box_id)
	{
		
		$pickup = new Pickup;
		$pickup->fk_user = $fk_user;
		$pickup->fk_company = $fk_company;
		$pickup->fk_order = $order_id;
		$pickup->fk_barcode = $barcode;
		$pickup->fk_box = $box_id;
		$pickup->timestamp = date("Y-m-d H:i:s");
		$pickup->save();
		
		return $pickup->id;
	
	}
	
	
	 /**
	 *  Insert into object table with parameters
	 *
	 *  return last inserted id (row_id)
	 */
	 
	private function createNewObject($barcode, $fkCompany, $orderId)
	{
		$newObject = new Object;
		$newObject->fk_obj_type = 2;
		$newObject->fk_company  = $fkCompany;
		$newObject->f_code = substr($barcode, 0, 6).substr($barcode, 9);
		$newObject->fk_parent = $orderId;
		$newObject->creation = date("Y-m-d H:i:s");
		$newObject->pickup = date("Y-m-d H:i:s");
		$newObject->fk_status = 1;
 
		$newObject->save();
		
		return $newObject->id;
	
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


}
