<?php

use Repositories\MetaAttribute\MetaAttributeRepositoryInterface;

class UsersStorageController extends \BaseController {


    public function __construct(MetaAttributeRepositoryInterface $metaAttribute)
    {
        $this->meta_attribute = $metaAttribute;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {   
        
        $companyId = Auth::User()->getCompanyId();

        $uploads = Upload::where('user_id', '=', Auth::User()->id)
                       ->where('parent_id', '=', 0)
                       ->get();

        foreach ($uploads as $upload) {

            $metaAttributeValues = $this->meta_attribute->getTargetAttributeValues($upload->id, 'upload');
            if (count($metaAttributeValues) >= 1) {

                foreach ($metaAttributeValues as $item) {
                    $options = $this->meta_attribute->getAttributeOptions($item->attribute_id);
                   
                    if (count($options) >=1 ) {
                            $upload->attributeValues[$item->attribute_id][] = $this->meta_attribute->getAttributeOptionLabel($item->value);
                          
                       
                        } else {

                             $upload->attributeValues[$item->attribute_id][] = $item->value;
            
                        }
                    }
            }

        }  

        $attributeFilters = $this->meta_attribute->getCompanyFilterableAttributes($companyId);
        
        $companyAttributeHeaders  = $this->meta_attribute->getCompanyAttributeHeaders($companyId);

        return  View::make('users.storage.index')
                      ->with('uploads', $uploads)
                      ->with('companyAttributeHeaders', $companyAttributeHeaders)
                      ->with('attributeFilters', $attributeFilters);

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


      /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function editAttributes($id)
    {   

        $companyId = Auth::User()->getCompanyId();
        
        $upload = Upload::where('user_id', '=', Auth::User()->id)
                       ->where('parent_id', '=', 0)
                       ->whereId($id)
                       ->first();


        $attributeSets = $this->meta_attribute->getCompanyAttributes($companyId);
        
        foreach ($attributeSets as $attribute) {
            $attribute->user_value  = $this->meta_attribute->getTargetAttributeValues($id, 'upload', $attribute->id);
        }
        
        return View::make('users.storage.attributes.edit')
                    ->with('upload', $upload)
                    ->with('attributeSets', $attributeSets);

    }
    

    public function updateAttributes($id)
    {
        //make sure is owner of file
        try {
            
            $companyId = Auth::User()->getCompanyId();
    
            $upload = Upload::where('user_id', '=', Auth::User()->id)
                           ->where('parent_id', '=', 0)
                           ->whereId($id)
                           ->first();

        } catch (Exception $e) {
            exit('cannot retrieve upload');
        }
        
        $id = $upload->id;

        $input = Input::except('_method', '_token');

        try {
            $this->meta_attribute->updateTargetAttributeValues($id, 'upload', $input);

            Session::flash('message', 'Storage file attributes successfully updated');

            return Redirect::to('users/storage');

        } catch (Exception $e) {
             Session::flash('error', 'Error updating storage attributes');
            return Redirect::to('users/storage');
        }
    }
   
}
