<?php

class UsersChartController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
	}

   
    
	/**
	 * Display a listing of the object with type = 3
	 *
	 * @param boxid
	 * @param orderid
	 * @return Response
	 */
	public function indexBoxOrder($boxId = '', $orderId = '')
	{
		$objects = Object::where('fk_obj_type', '=', 3)
					->where('fk_parent', '=', $boxId)
					->where('fk_company', '=', Auth::User()->getCompanyId())
					->orderBy('f_code')
					->orderBy('f_name')
					->get();
					
 
	
		foreach($objects as $object)
		{
		    
		    $object->status = OrderStatus::find($object->fk_status)->status;
		}
       
       	$box = Object::where('row_id', '=', $boxId)->first();
		
		$order = Object::where('row_id', '=', $orderId)->first();
		
        return View::make('users.chart.index_box_order')
	           ->with('box', $box)
			   ->with('order', $order)
               ->with('objects', $objects);
			   
		 
		
	}

	
	/**
	 * Display a listing of the object with type = 3
	 *
	 * @param boxid
	 * @param orderid
	 * @return Response
	 */
	public function indexBoxOrderChart($boxId = '', $orderId = '', $chartId = '')
	{
		$filePermission = Auth::User()->getUserData()->file_permission;
        $permissionArray = json_decode(Auth::User()->getUserData()->file_permission, true);
        $permissionArray[] = '';
        $files = FileTable::where('fk_empresa', '=', Auth::User()->getCompanyId() )
                                                ->where('parent_id', '=', $chartId)
                                                ->where(function($query) use ($permissionArray)
                                                        {
                                                                $query->whereIn('file_mark_id', $permissionArray)
                                                                      ->orWhereRaw('file_mark_id is null');
                                                         }

                                                  )
                                            ->get(array('row_id', 'filename', 'creadate', 'moddate', 'pages', 'filesize', 'file_mark_id'));

       	$box = Object::where('row_id', '=', $boxId)->first();
		
		$order = Object::where('row_id', '=', $orderId)->first();
		
		$chart = Object::where('row_id', '=', $chartId)->first();
		
		$filemarkDropdown = array('' => '(No Label)');
		
		try {
			$filemarks = Filemark::whereIn('id',  json_decode($filePermission, true))
								->orderBy('global', 'desc')
								->orderBy('label')->get();
            
			
			foreach($filemarks as $filemark)
			{
				$filemarkDropdown[$filemark->id] = $filemark->label;
			}
			
		}
		catch (Exception $e)
		{
			
		}
		
		return View::make('users.chart.index_box_order_chart')
	           ->with('box', $box)
			   ->with('order', $order)
			   ->with('chart', $chart)
			   ->with('filemarkDropdown', $filemarkDropdown)
               ->with('files', $files);
			   
		
	}

	
	
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		 
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
