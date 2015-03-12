<?php

class UsersOrderController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	    $objects = Object::where('fk_obj_type', '=', 1)
		                 ->where('fk_company', '=', Auth::User()->getCompanyId())
						 ->orderBy('row_id')->get();
						 
		
		foreach($objects as $object)
		{
		    
		    $object->status = OrderStatus::find($object->fk_status)->status;
			$object->price = $object->qty * $object->ppc;
		}
       
		// load the view and pass the data
        return View::make('users.order.index')
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
		$objects = Object::where('fk_obj_type', '=', 2)
						   ->where('fk_parent', '=', $id)
						   ->where('fk_company', '=', Auth::User()->getCompanyId())
						   ->orderBy('f_code')
						   ->orderBy('f_name')
						   ->get();
		
	   foreach($objects as $object)
	   {
		
			try {
				$_object = Object::where('row_id', '=', $object->fk_parent)->first();
				$object->price = $_object->ppc * $object->qty;
				
			}
			catch(Exception $e)
			{
			   $object->price = '';
			}
			
			try {
				
				$object->status = OrderStatus::find($object->fk_status)->status;
				
			}
			catch (Exception $e)
			{
				$object->status = '';
			}
				
			
			try {
				
				$pickup = Pickup::where('fk_box', '=', $object->row_id)->firstOrFail();

				$workflow = Workflow::where('wf_id', '=', $pickup->fk_barcode)->first();
				
				$object->estatus = WorkflowStatus::where('row_id', '=', $workflow->fk_status)->first()->status;
				
			}
			catch (Exception $e)
			{
				$object->estatus = "FINISH"; //default
			}
		
	   }
	     
		$parent = Object::where('fk_obj_type', '=', 1)
		                 ->where('fk_company', '=', Auth::User()->getCompanyId())
						 ->find($id);
						 
		// load the view and pass the data
		return View::make('users.order.show')
	           ->with('parent', $parent)
               ->with('objects', $objects);
			   

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
