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
	 
		 // load the view and pass the nerds
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
		
	
	    $orderId = Input::get('object_id');
		 
		//get fk_company from order (objects table)
		$object = Object::where('row_id', '=', $orderId)->first();
		
		if(!$object) {
			
			// redirect with error
            Session::flash('error', 'Cannot find order id '. $orderId );
			
			return Redirect::to('pickup');
		
			
		}
		
		$fkCompany = $object->fk_company;

		$barcodeChecked = Input::get('barcode');
		
		
		if(is_array($barcodeChecked))
		{
			
			foreach($barcodeChecked as $barcode)
			{
			    echo "haha";
			    $boxId = $this->createNewObject($barcode, $fkCompany, $orderId);
                echo $boxId;
				
			}
				
		}

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
