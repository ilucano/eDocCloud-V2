<?php

class AdminBoxController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$objects = Object::where('fk_obj_type', '=', 2)->get();
		
		foreach($objects as $object)
		{
			try {
				$object->company_name = Company::where('row_id', '=', $object->fk_company)->firstOrFail()->company_name;
			}
			catch (Exception $e)
			{
				$object->company_name = '';
					
			}
			
			try {
				
				$object->status = OrderStatus::find($object->fk_status)->status;
			}
			catch (Exception $e)
			{
				$object->status = '';
					
			}
			
		}
		
	 

		// load the view and pass the data
        return View::make('adminbox.index')
               ->with('objects', $objects);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		$companies = Company::orderBy('company_name')->get();

		 $companyDropdown = array();
		 
		 foreach($companies as $company)
		 {
			 $companyDropdown[$company->row_id] = $company->company_name;
		 }
		
		 return View::make('adminbox.create')
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
		 $object->fk_obj_type =  2;
		 $object->fk_status = 1;
		 $object->creation = date("Y-m-d H:i:s");
		 
		 $object->save();
		 
		 $orderId = $object->id;
		 
		 
		 Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'admin_create_box_success',
				'action'      => 'Create',
				'description' => 'New Box Created',
				'details'     => 'Box Id: '.$object->row_id .', Name: '. Input::get('f_name') .', Code: '.Input::get('f_code'),
				'updated'     => false,
		]);
		 
		 Session::flash('message', 'Box created');
		 return Redirect::to('admin/box');

	  
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
        return View::make('adminbox.edit')
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
		 
		 
		Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'admin_update_box_success',
				'action'      => 'Updated',
				'description' => 'Box Updated',
				'details'     => 'Box Id: '.$id .', Name: '. Input::get('f_name') .', Code: '.Input::get('f_code'),
				'updated'     => true,
		]);
		  
		
		 Session::flash('message', 'Box updated');
		 return Redirect::to('admin/box/'. $id .'/edit');
	  
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
			
			
			Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'admin_update_box_status',
				'action'      => 'Updated',
				'description' => 'Box Status Updated',
				'details'     => 'Box Id: '.Input::get('row_id')  .', Status: '. Input::get('status') ,
				'updated'     => true,
			]);
			
			 Session::flash('message', 'Box #' . Input::get('row_id') . ' updated');
			return Redirect::to('admin/box');
		}
		catch (Exception $e)
		{
		 
			Session::flash('error', $e->getMessage() );
			return Redirect::to('order');
		}
		
		
	}




}
