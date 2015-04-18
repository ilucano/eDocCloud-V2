<?php

class AdminPickupController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$pickups = Pickup::all();
		
		foreach($pickups as $pickup)
		{
			$pickup->company_name = Company::where('row_id', '=', $pickup->fk_company)->firstOrFail()->company_name;
			
			try {
				$object = Object::where('row_id', '=', $pickup->fk_order)->firstOrFail();
				$pickup->order = $object->f_code . ' ' . $object->f_name;
			}
			catch(Exception $e)
			{
				$pickup->order = '';
			}
			
			try {
				$object = Object::where('row_id', '=', $pickup->fk_box)->firstOrFail();
				$pickup->box = $object->f_code . ' ' . $object->f_name;
			}
			catch(Exception $e)
			{   
				$pickup->box = '';
			}
		}
		
	  

		// load the view and pass the data
        return View::make('adminpickup.index')
               ->with('pickups', $pickups);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		$users = User::orderBy('first_name')->get();
		
		$userDropdown = array();
		
		foreach($users as $user)
		{
			$userDropdown[$user->row_id] = $user->first_name . ' ' . $user->last_name . ' ('.$user->username.')';
		}
		
		$companies = Company::orderBy('company_name')->get();

		
		$companyDropdown = array();
		
		foreach($companies as $company)
		{
			$companyDropdown[$company->row_id] = $company->company_name;
		}
		
		
		$orderDropdown = array();
		
		$orders = Object::where('fk_obj_type', '=', 1)->get();
		foreach ($orders as $order)
		{
			try {
				$order->company_name = Company::where('row_id', '=', $order->fk_company)->firstOrFail()->company_name;
			}
			catch(Exception $e) {
				$order->company_name = '';
			}
			
			$orderDropdown[$order->row_id] = $order->f_code . ' ' . $order->f_name . ' (' . $order->company_name. ')';
		}
		
 
		 return View::make('adminpickup.create')
				->with('orderDropdown', $orderDropdown)
				->with('userDropdown', $userDropdown)
                ->with('companyDropdown', $companyDropdown);
		
		
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
			'fk_company' => 'required|integer',
			'fk_user' => 'required|integer',
			'fk_order' => 'required|integer',
			'fk_barcode' => 'required',	
		 );
		 
		  
		 $validator = Validator::make(Input::all(), $rules);
		 
		 if ($validator->fails()) {
			 return Redirect::to('admin/pickup/create')
				 ->withErrors($validator)
				 ->withInput();
		 }
		
		 
		$pickup = new Pickup;
		$pickup->fk_user = Input::get('fk_user');
		$pickup->fk_company = Input::get('fk_company');
		$pickup->fk_order =  Input::get('fk_order');
		$pickup->fk_barcode = Input::get('fk_barcode');
		$pickup->timestamp = date("Y-m-d H:i:s");
		$pickup->save();
		
		
		$logDetails = json_encode(['row_id' => $pickup->row_id]);
				
		Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'admin_pickup_create',
				'action'      => 'Created',
				'description' => 'New Pickup Created',
				'details'     => $logDetails,
				'updated'     => false,
			]);
		
		 Session::flash('message', 'Pickup created');
		 return Redirect::to('admin/pickup');

	  
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
		
		$pickup = Pickup::find($id);
	
	
		
		$users = User::orderBy('first_name')->get();
		
		$userDropdown = array();
		
		foreach($users as $user)
		{
			$userDropdown[$user->row_id] = $user->first_name . ' ' . $user->last_name . ' ('.$user->username.')';
		}
		
		
		$companies = Company::orderBy('company_name')->get();

		
		$companyDropdown = array();
		
		foreach($companies as $company)
		{
			$companyDropdown[$company->row_id] = $company->company_name;
		}
		
		
		$orderDropdown = array(''=>'Please select');
		
		$orders = Object::where('fk_obj_type', '=', 1)->get();
		foreach ($orders as $order)
		{
			try {
				$order->company_name = Company::where('row_id', '=', $order->fk_company)->firstOrFail()->company_name;
			}
			catch(Exception $e) {
				$order->company_name = '';
			}
			
			$orderDropdown[$order->row_id] = $order->f_code . ' ' . $order->f_name . ' (' . $order->company_name. ')';
		}
		
		
		$boxDropdown = array(''=>'Please select');
		
		$boxes = Object::where('fk_obj_type', '=', 2)
		                 ->where('fk_parent', '=', $pickup->fk_order)->get();
	    
		
		foreach ($boxes as $box)
		{
			try {
				$box->company_name = Company::where('row_id', '=', $box->fk_company)->firstOrFail()->company_name;
			}
			catch(Exception $e) {
				$box->company_name = '';
			}
			
			$boxDropdown[$box->row_id] = $box->f_code . ' ' . $box->f_name . ' (' . $box->company_name. ')';
		}
	
			// load the view and pass the data
        return	View::make('adminpickup.edit')
				->with('companyDropdown', $companyDropdown)
			    ->with('userDropdown', $userDropdown)
				->with('orderDropdown', $orderDropdown)
				->with('boxDropdown', $boxDropdown)
				->with('pickup', $pickup);
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
			'fk_user'   => 'required',
			'fk_company' => 'required|integer',
			'fk_order' => 'required',
		    'fk_barcode' => 'required',
		 );
		 
		  
		 $validator = Validator::make(Input::all(), $rules);
		 
		 if ($validator->fails()) {
			 return Redirect::to('admin/pickup/' . $id . '/edit')
				 ->withErrors($validator)
				 ->withInput();
		 }
		 
 
		 
		$pickup = Pickup::find($id);
		$pickup->fk_user = Input::get('fk_user');
		$pickup->fk_company = Input::get('fk_company');
		$pickup->fk_order = Input::get('fk_order');
		$pickup->fk_barcode = Input::get('fk_barcode');
		$pickup->fk_box = Input::get('fk_box');
 
		 
		$pickup->save();
		
		$logDetails = json_encode(['row_id' => $pickup->row_id]);
		
		Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'admin_pickup_update',
				'action'      => 'Updated',
				'description' => 'Pickup Updated',
				'details'     =>  $logDetails,
				'updated'     => true,
			]);
		
		
		Session::flash('message', 'Pickup updated');
		return Redirect::to('admin/pickup/'. $id .'/edit');
	  
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
			'row_id' => 'required',  
			'status' => 'required'
		);
		
		// run the validation rules 
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			
			return Redirect::to('order')
				->withErrors($validator);
			
		}
		
		try {
		 
			$object = Object::find(Input::get('row_id'));
			$object->fk_status = Input::get('status');
			$object->save();
			
            $logDetails = json_encode(['row_id' => Input::get('row_id'),
									   'status' => Input::get('status')]);
					
			Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'admin_pickup_status',
				'action'      => 'Updated',
				'description' => 'Pickup Status Updated',
				'details'     => $logDetails,
				'updated'     => true,
			]);
			
			 Session::flash('message', 'Order #' . Input::get('row_id') . ' updated');
			return Redirect::to('order');
		}
		catch (Exception $e)
		{
		 
			Session::flash('error', $e->getMessage() );
			return Redirect::to('order');
		}
		
		
	}




}
