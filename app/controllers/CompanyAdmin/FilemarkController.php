<?php

class CompanyAdminFilemarkController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		$filemarks = Filemark::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)
								->orWhere('global', '=', 1)
								->orderBy('global', 'desc')
								->orderBy('label')->get();
         
	
		//get company user of that company only
		foreach($filemarks as $filemark) 
		{
			//get number of files 
			try {
			   
				$fileCount = FileTable::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)
			                      ->where('file_mark_id', '=', $filemark->id)
			                      ->count();
								  
				$filemark->file_count = $fileCount;

			   
			}
			catch(Exception $e)
			{
			   
			   $filemark->file_count = 0;
			   
			}
			
		}
		

		// load the view and pass the data
        return View::make('companyadmin.filemark.index')
               ->with('filemarks', $filemarks);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		
		return View::make('companyadmin.filemark.create');
	
	}
  
   
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		
		Input::merge(array_map('trim', Input::except('password')));
 
		$rules = array(
			'label' => 'required',			
        );
		 
        $validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
            return Redirect::to('companyadmin/filemark/create')
                ->withErrors($validator)
                ->withInput();
        }
		
		//check if exist
		$markCount = Filemark::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)
							 ->where('label', '=', Input::get('label'))
							 ->count();
		
		if($markCount >= 1)
		{
			Session::flash('error', '<strong>'.Input::get('label') .'</strong> already exists');
			return Redirect::to('companyadmin/filemark/create');
			exit;
		}
		
		
		//create login
		$filemark = new Filemark;
		$filemark->label = Input::get('label');
		$filemark->global = 0;
		$filemark->fk_empresa = Auth::User()->getUserData()->fk_empresa;
		$filemark->create_date = date("Y-m-d H:i:s");
		$filemark->save();
	    
		
		$logDetails = json_encode(['row_id' => $filemark->id]);
		
		Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'companyadmin_filemark_store',
				'action'      => 'Created',
				'description' => 'New Filemark Created',
				'details'     => $logDetails,
				'updated'     => false,
			]);
		
	         
		Session::flash('message', '<strong>'.Input::get('label') .'</strong> successfully created');
			
		return Redirect::to('companyadmin/filemark');
	
		 
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
	   
	   	$filemark = Filemark::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)
								->find($id);
         
		 
	
        return View::make('companyadmin.filemark.edit')
               ->with('filemark', $filemark);

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
	
		Input::merge(array_map('trim', Input::except('password')));
 
		$rules = array(
			'label' => 'required',			
        );
		 
        $validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
            return Redirect::to('companyadmin/filemark/create')
                ->withErrors($validator)
                ->withInput();
        }
		
		//check if exist of same name 
		$markCount = Filemark::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)
							 ->where('label', '=', Input::get('label'))
							 ->where('id', '<>', $id)
							 ->count();
		
		if($markCount >= 1)
		{
			Session::flash('error', '<strong>'.Input::get('label') .'</strong> already exists');
			return Redirect::to('companyadmin/filemark/'.$id.'/edit');
			exit;
		}
		
		
		//create login
		$filemark = Filemark::where('fk_empresa', '=', Auth::User()->getUserData()->fk_empresa)->find($id);
		$filemark->label = Input::get('label');
		$filemark->save();
	
	    
		$logDetails = json_encode(['row_id' => $id]);
		
		Activity::log([
				'contentId'   => Auth::User()->id,
				'contentType' => 'companyadmin_filemark_update',
				'action'      => 'Updated',
				'description' => 'Filemark Updated',
				'details'     => $logDetails,
				'updated'     => true,
			]);
		
		Session::flash('message', 'Filemark successfully updated');
			
		return Redirect::to('companyadmin/filemark');
	
		
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
