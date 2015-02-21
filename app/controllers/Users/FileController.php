<?php

class UsersFileController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		 
         return View::make('users.file.index');
		
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
		
	}
   

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
	
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
	public function doUpdateMark()
	{
		 
	    
		$file = FileTable::where('fk_empresa', '=', Auth::User()->getCompanyId())
							->where('row_id', '=', Input::get('id'))->first();
		
		
		$file->file_mark_id = Input::get('file_mark_id');
		
		$file->save();
		
		echo "success";
		
	}
	
	/**
	 * Display search form
	 */
	
	public function showSearch()
	{
		
		return View::make('users.file.search');
		
	}

	
	public function doSearch()
	{
		
		$rules = array(
			'query' => 'required|min:4',			
        );
		
		   $validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
            return Redirect::to('users/file/search')
                ->withErrors($validator)
                ->withInput();
        }
		
		$query = Input::get('query');
		
		$matchExactAllTerms = addslashes($query);
	
		$arrayText = explode(" ", $query);
		
		foreach($arrayText as $singleWord)
		{
			if($singleWord)
			{
				$arrayTextMatchAll[] = "+".$singleWord;
				
			}
		}
		
		$filePermission = Auth::User()->getUserData()->file_permission;
		
		
		$array_file_permission = json_decode($filePermission, true);
		
		if(count($array_file_permission) >=1)
		{
			$file_in_string = join(", ", $array_file_permission);
			
			$user_file_mark_id_allowed = " OR file_mark_id IN ($file_in_string) ";
		}
		
		
		$filter_file_permission = " AND (file_mark_id IS NULL OR file_mark_id = '' $user_file_mark_id_allowed ) ";
		
		$matchAndAllTerms = addslashes(implode(" ", $arrayTextMatchAll));
		
		
		$mainMatchQuery = " MATCH(texto) AGAINST('".$matchAndAllTerms."' IN BOOLEAN MODE) AS Score1, MATCH(texto) AGAINST('" .
		$matchExactAllTerms ."' IN BOOLEAN MODE) AS Score2 FROM files WHERE MATCH(texto) AGAINST ('".$matchAndAllTerms."' IN BOOLEAN MODE) AND fk_empresa = " . Auth::User()->getCompanyId(). $filter_file_permission ;
		
	    $sqlQuery = "SELECT row_id, creadate, pages, filesize, moddate, filename, file_mark_id, " . $mainMatchQuery ." ORDER BY Score2 Desc, Score1 desc;";
		
		
        
		
		$files = DB::select( DB::raw($sqlQuery) );
       
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
		
		
		return View::make('users.file.searchresult')
					->with('files', $files)
					->with('filemarkDropdown', $filemarkDropdown)
					->with('query', $query);
					
		
	}


}
