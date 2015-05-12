<?php

class UsersStorageController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {   
 
        $uploads = Upload::where('user_id', '=', Auth::User()->id)
                       ->where('parent_id', '=', 0)
                       ->get();

    
        return  View::make('users.storage.index')
                      ->with('uploads', $uploads);

    }

    /**
     * Displays the form for account creation
     *
     */
    public function create()
    {
        //return View::make(Config::get('cabinet::upload_form'));
        $typesAllowed = Config::get('cabinet::upload_file_extensions');
        $maxFileSize = Config::get('cabinet::max_upload_file_size');
        
        return View::make('users.storage.create')
                     ->with('typesAllowed', $typesAllowed)
                     ->with('maxFileSize', $maxFileSize);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
         
        $file = Input::file('file');

        $upload = new Upload;

        try {
            $upload->process($file);
        } catch(Exception $exception){
            // Something went wrong. Log it.
            Log::error($exception);
            // Return error
            return Response::json($exception->getMessage(), 400);
        }

        // If it now has an id, it should have been successful.
        if ( $upload->id ) {
            return Response::json(array('status' => 'success', 'file' => $upload->toArray()), 200);
        } else {
            return Response::json('Error', 400);
        }
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
       
    
        $idArray = explode(",", Input::get('deletelist'));
        
        try {
            $upload = Upload::where('user_id', '=', Auth::User()->id)
                              ->whereIn('id', $idArray)->delete();
            
            Session::flash('message', 'File deleted');
            
            return Redirect::to('users/storage');

        } catch (Exception $e) {
            echo "Error find file";
        }

    }


    public function doDownload($id)
    {  
         try {
            $upload = Upload::where('user_id', '=', Auth::User()->id)
                              ->where('id', '=', $id)->first();
            //echo "<pre>";
            //print_r($upload);
            $file = base_path() . $upload->path.$upload->filename;
            
            return Response::download($file);

        } catch (Exception $e) {
            echo "Error ". $e->getMessage();
        }


    }

    public function doSwitchFavourite($id)
    {
        try {
            $upload = Upload::where('user_id', '=', Auth::User()->id)
                              ->where('id', '=', $id)->first();
            
            $upload->favourite = ($upload->favourite == 1) ? 0 : 1;
            $upload->save();

            return Redirect::to('users/storage');

        } catch (Exception $e) {
            echo "Error ". $e->getMessage();
        }

    }
    
   
}
