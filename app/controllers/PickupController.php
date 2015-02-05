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
		
		
		//echo Auth::user();
		$orderLists = Object::where('fk_status', '<>', 5)
						  ->where('fk_obj_type', '=', 1)
						  ->get();
		
         
		foreach($orderLists as $orderList)
		{
			$companyName = Company::where('row_id', '=', $orderList->fk_company)->first()->company_name;
			
			$orderList->company_name = $companyName;
			
			$niceLabel = $orderList->row_id . '- ' . $orderList->f_name . ' ' . $orderList->company_name;
			$orderDropdown[$orderList->row_id] =  $niceLabel;
							 
		}
		
		
    
		 // load the view and pass the nerds
        return View::make('pickup.index')
               ->with('orderLists', $orderDropdown);
			
	 
		
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


}
