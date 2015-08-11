<?php

use Repositories\MetaAttribute\MetaAttributeRepositoryInterface;

class UsersStorageController extends \BaseController
{
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
        
        $folders = $this->getUserFoldersDropdown();

        $currentFolder =  Input::get('folder');

        $uploads = Upload::where('user_id', '=', Auth::User()->id)
                       ->where('parent_id', '=', 0);


        $searhQuery = Input::get('query');

        //attribute filter
        $arrayFilteredFile = array();
        
        $filters = Input::except('limit','query');

        if ($filters) {
            $joinTables = array();
            $andString = array();

            foreach ($filters as $attribute_id => $value) {
                if (!$value) {
                    continue;
                }
                $joinTables[] = " JOIN meta_target_attribute_values AS table_{$attribute_id} ON `master`.target_id  =  table_{$attribute_id}.target_id ";
                $andString[]  = " AND table_{$attribute_id}.attribute_id = '".addslashes($attribute_id)."' AND table_{$attribute_id}.value = '".addslashes($value)."' ";
            }

            if (count($joinTables) >= 1) {
                $attributeSql = 'SELECT DISTINCT(`master`.target_id) FROM  `meta_target_attribute_values`  as `master` ';
                $attributeSql .= implode(' ', $joinTables);
                $attributeSql .= " WHERE `master`.target_type = 'upload' ";
                $attributeSql .= implode(' ', $andString);

                $filteredFiles = DB::select(DB::raw($attributeSql));
                $arrayFilteredFile[] = 0;
                foreach ($filteredFiles as $filteredFile) {
                    $arrayFilteredFile[] = $filteredFile->target_id;
                }
            }
        }


        if ($currentFolder) {
            $uploads = $uploads->where('user_folder', $currentFolder);
        }

        if ($searhQuery) {
            $folderIds= $this->getFolderIdByQuery($searhQuery);

            $uploads = $uploads->where(function ($query) use ($searhQuery, $folderIds) {
                             $query->where('user_filename', 'like', '%'.$searhQuery.'%')
                                   ->orWhereIn('user_folder', $folderIds);
                       });
        }
        
        if (count($arrayFilteredFile) >= 1) {
            $uploads =  $uploads->whereIn('id', $arrayFilteredFile);
        }

        $uploads = $uploads->get();

        foreach ($uploads as $upload) {
            $metaAttributeValues = $this->meta_attribute->getTargetAttributeValues($upload->id, 'upload');
            if (count($metaAttributeValues) >= 1) {
                foreach ($metaAttributeValues as $item) {
                    $options = $this->meta_attribute->getAttributeOptions($item->attribute_id);

                    if (count($options) >= 1) {
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
                      ->with('attributeFilters', $attributeFilters)
                      ->with('folders', $folders)
                      ->with('currentFolder', $currentFolder)
                      ->with('query', $searhQuery)
                      ->with('filterExpand', (count($filters) >= 1 || $searhQuery) ? true: false);
    }


    public function getFolderIdByQuery($string)
    {
        $results = array(); //invalid folder name by default

        if ($string) {
            $results = UploadFolder::where('folder_name', 'like', '%'.$string.'%')
                                    ->where('user_id', '=', Auth::User()->id)
                                    ->get(['id'])->toArray();
        }

        return $results;
    }
    /**
     * Displays the form for account creation.
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

        $upload = new Upload();

        try {
            $upload->process($file);
           

        } catch (Exception $exception) {
            // Something went wrong. Log it.
            Log::error($exception);
            // Return error
            return Response::json($exception->getMessage(), 400);
        }

        // If it now has an id, it should have been successful.
        if ($upload->id) {
            $upload = Upload::find($upload->id);
            $upload->user_filename = $upload->filename;
            $upload->save();
            return Response::json(array('status' => 'success', 'file' => $upload->toArray()), 200);
        } else {
            return Response::json('Error', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $idArray = explode(',', Input::get('deletelist'));

        try {
            $upload = Upload::where('user_id', '=', Auth::User()->id)
                              ->whereIn('id', $idArray)->delete();

            Session::flash('message', 'File deleted');

            return Redirect::to('users/storage');
        } catch (Exception $e) {
            echo 'Error find file';
        }
    }

    public function doDownload($id)
    {
        try {
            $upload = Upload::where('user_id', '=', Auth::User()->id)
                              ->where('id', '=', $id)->first();
            //echo "<pre>";
            //print_r($upload);
            $file = base_path().$upload->path.$upload->filename;

            return Response::download($file);
        } catch (Exception $e) {
            echo 'Error '.$e->getMessage();
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
            echo 'Error '.$e->getMessage();
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

    public function updateUserFilename($id)
    {
        //check if id owner

        try {
            $upload = Upload::where('user_id', '=', Auth::User()->id)
                           ->where('parent_id', '=', 0)
                           ->whereId($id)
                           ->first();
        } catch (Exception $e) {
            exit('cannot retrieve upload');
        }

        if ($upload) {
            $upload->user_filename = Input::get('value');
            $upload->save();
        }
    }

    public function getUserFolders()
    {

        $folders = UploadFolder::where('user_id', '=', Auth::User()->id)
                                 ->orderBy('folder_name')
                                 ->get();
        return $folders;
    }

    public function getUserFoldersDropdown()
    {

        $resultFolders = $this->getUserFolders();

        $folders = ['' => ''];

        foreach ($resultFolders as $folder) {
            $folders[$folder->id] = $folder->folder_name;
        }


        return $folders;
    }

    public function storeFolder()
    {

        $rules = array(
            'folder_name' => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('users/storage')
                ->withErrors($validator)
                ->withInput();
        }

        $folderName = Input::get('folder_name');

        $folder = UploadFolder::where('user_id', '=', Auth::User()->id)
                               ->where('folder_name', '=', $folderName)
                               ->first();
        if ($folder) {
            Session::flash('error', 'Folder already exists');
            return Redirect::to('users/storage');
        }

        $folder = new UploadFolder;
        $folder->folder_name = $folderName;
        $folder->user_id = Auth::User()->id;
        $folder->save();
        return Redirect::to('users/storage');
    }


    public function setFileFolder($id)
    {
        //check if id owner

        try {
            $upload = Upload::where('user_id', '=', Auth::User()->id)
                           ->where('parent_id', '=', 0)
                           ->whereId($id)
                           ->first();
        } catch (Exception $e) {
            exit('cannot retrieve upload');
        }

        if ($upload) {
            $upload->user_folder = Input::get('value');
            $upload->save();
        }
    }

}
