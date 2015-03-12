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
		 $companies = Company::orderBy('company_name')->get();

		 $companyDropdown = array();
		 
		 foreach($companies as $company)
		 {
			 $companyDropdown[$company->row_id] = $company->company_name;
		 }
		
		 return View::make('order.create')
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
			'f_code' => 'required',
			'f_name' => 'required',
			'ppc' => 'required|numeric',
			
		 );
		 
		  
		 $validator = Validator::make(Input::all(), $rules);
		 
		 if ($validator->fails()) {
			 return Redirect::to('order/create')
				 ->withErrors($validator)
				 ->withInput();
		 }
		 
		 $object = new Object;
		 $object->fk_company = Input::get('fk_company');
		 $object->f_code = Input::get('f_code');
		 $object->f_name = Input::get('f_name');
		 $object->ppc = Input::get('ppc');
		 //default value for new order
		 $object->fk_obj_type =  1;
		 $object->fk_status = 1;
		 $object->creation = date("Y-m-d H:i:s");
		 
		 $object->save();
		 
		 $orderId = $object->id;
		 
		 Session::flash('message', 'Order created');
		 return Redirect::to('order');

	  
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
