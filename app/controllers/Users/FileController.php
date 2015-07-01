<?php

use Repositories\File\FileRepositoryInterface;
use Repositories\MetaAttribute\MetaAttributeRepositoryInterface;

class UsersFileController extends \BaseController
{
    public function __construct(FileRepositoryInterface $repository, MetaAttributeRepositoryInterface $metaAttribute)
    {
        $this->repo = $repository;
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
        $permission = json_decode(Auth::User()->getUserData()->file_permission, true);

        if ($permission == null) {
            $permission = array();
        }

        $limit = 50;

        $searchFilters = Input::except('limit');

        if (Input::get('limit')) {
            $limit =  Input::get('limit');
        }
       
        $filterExpand =  (count($searchFilters) >= 1) ? true: false;

        $files = $this->repo->getFiles($companyId, $permission, 0, $limit, $searchFilters);
       
        foreach ($files as $file) {
            $metaAttributeValues = $this->meta_attribute->getTargetAttributeValues($file->row_id, 'file');

            if (count($metaAttributeValues) >= 1) {
                foreach ($metaAttributeValues as $item) {
                    $options = $this->meta_attribute->getAttributeOptions($item->attribute_id);
                    if (count($options) >=1 ) {
                            $file->attributeValues[$item->attribute_id][] = $this->meta_attribute->getAttributeOptionLabel($item->value);
                    } else {
                        $file->attributeValues[$item->attribute_id][] = $item->value;
                    }
                }
            }
        }
 

        $filemarkDropdown = $this->getFileMarkDropdown($permission);

        $attributeFilters = $this->meta_attribute->getCompanyFilterableAttributes($companyId);
        
        $companyAttributeHeaders  = $this->meta_attribute->getCompanyAttributeHeaders($companyId);
 
        return View::make('users.file.index')
                    ->with('files', $files)
                    ->with('filemarkDropdown', $filemarkDropdown)
                    ->with('attributeFilters', $attributeFilters)
                    ->with('filterExpand', $filterExpand)
                    ->with('companyAttributeHeaders', $companyAttributeHeaders);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function doUpdateMark()
    {
        $file = FileTable::where('fk_empresa', '=', Auth::User()->getCompanyId())
                            ->where('row_id', '=', Input::get('id'))->first();

        $file->file_mark_id = Input::get('file_mark_id');

        $file->save();

        $logDetails = json_encode(['row_id' => Input::get('id'), 'file_mark_id' => Input::get('file_mark_id')]);

        Activity::log([
                'contentId'   => Auth::User()->id,
                'contentType' => 'user_file_updatemark',
                'action'      => 'Updated',
                'description' => 'Updated a file label',
                'details'     => $logDetails,
                'updated'     => true,
            ]);

        echo 'success';
    }

    /**
     * Display search form.
     */
    public function showSearch()
    {   
        $companyId = Auth::User()->getCompanyId();

        $limit = 50;

        $searchFilters = Input::except('limit');

        if (Input::get('limit')) {
            $limit =  Input::get('limit');
        }
       
        $filterExpand =  (count($searchFilters) >= 1) ? true: false;

        $attributeFilters = $this->meta_attribute->getCompanyFilterableAttributes($companyId);

        return View::make('users.file.search')
                    ->with('attributeFilters', $attributeFilters)
                    ->with('filterExpand', $filterExpand);
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


        $companyId = Auth::User()->getCompanyId();

        $query = Input::get('query');

        //attribute filter
        $filter_attribute_files = '';

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
                $attributeSql .= ' WHERE 1 ';
                $attributeSql .= implode(' ', $andString);

                $filteredFiles = DB::select(DB::raw($attributeSql));

                $arrayFilteredFile = array();

                foreach ($filteredFiles as $filteredFile) {
                    $arrayFilteredFile[] = $filteredFile->target_id;
                }

                if (count($arrayFilteredFile) >= 1) {
                    $filter_attribute_files = ' AND row_id IN ('.implode(', ', $arrayFilteredFile).')';
                }
            }
        }

        $matchExactAllTerms = addslashes($query);

        $arrayText = explode(' ', $query);

        foreach ($arrayText as $singleWord) {
            if ($singleWord) {
                $arrayTextMatchAll[] = '+'.$singleWord;
            }
        }

        $filePermission = Auth::User()->getUserData()->file_permission;

        $array_file_permission = json_decode($filePermission, true);

        if (count($array_file_permission) >= 1) {
            $file_in_string = implode(', ', $array_file_permission);

            $user_file_mark_id_allowed = " OR file_mark_id IN ($file_in_string) ";
        }

        $filter_file_permission = " AND (file_mark_id IS NULL OR file_mark_id = '' $user_file_mark_id_allowed ) ";

        $matchAndAllTerms = addslashes(implode(' ', $arrayTextMatchAll));

        $mainMatchQuery = " MATCH(texto) AGAINST('".$matchAndAllTerms."' IN BOOLEAN MODE) AS Score1, MATCH(texto) AGAINST('".
        $matchExactAllTerms."' IN BOOLEAN MODE) AS Score2 FROM files WHERE MATCH(texto) AGAINST ('".$matchAndAllTerms."' IN BOOLEAN MODE) AND fk_empresa = ".Auth::User()->getCompanyId().$filter_file_permission . $filter_attribute_files;

        $sqlQuery = 'SELECT row_id, creadate, pages, filesize, moddate, filename, file_mark_id, '.$mainMatchQuery.' ORDER BY Score2 Desc, Score1 desc;';

        $files = DB::select(DB::raw($sqlQuery));


        foreach ($files as $file) {
            $metaAttributeValues = $this->meta_attribute->getTargetAttributeValues($file->row_id, 'file');

            if (count($metaAttributeValues) >= 1) {
                foreach ($metaAttributeValues as $item) {
                    $options = $this->meta_attribute->getAttributeOptions($item->attribute_id);
                    if (count($options) >=1) {
                            $file->attributeValues[$item->attribute_id][] = $this->meta_attribute->getAttributeOptionLabel($item->value);
                    } else {
                            $file->attributeValues[$item->attribute_id][] = $item->value;
                    }
                }
            }
        }

        $filemarkDropdown = array('' => '(No Label)');

        try {
            $filemarks = Filemark::whereIn('id', json_decode($filePermission, true))
                                ->orderBy('global', 'desc')
                                ->orderBy('label')->get();

            foreach ($filemarks as $filemark) {
                $filemarkDropdown[$filemark->id] = $filemark->label;
            }
        } catch (Exception $e) {
        }



        $filterExpand =  ($filter_attribute_files) ? true: false;
      
        $attributeFilters = $this->meta_attribute->getCompanyFilterableAttributes($companyId);
        
        $companyAttributeHeaders  = $this->meta_attribute->getCompanyAttributeHeaders($companyId);

        $logDetails = json_encode(['query' => Input::get('query')]);

        Activity::log([
                'contentId'   => Auth::User()->id,
                'contentType' => 'user_file_search',
                'action'      => 'Created',
                'description' => 'Performed file search',
                'details'     => $logDetails,
                'updated'     => false,
            ]);

        return View::make('users.file.searchresult')
                    ->with('files', $files)
                    ->with('filemarkDropdown', $filemarkDropdown)
                    ->with('query', $query)
                    ->with('attributeFilters', $attributeFilters)
                    ->with('filterExpand', $filterExpand)
                    ->with('companyAttributeHeaders', $companyAttributeHeaders);
    }

    private function getFileMarkDropdown(array $permission)
    {
        $filemarkDropdown = array('' => '(No Label)');

        try {
            $filemarks = Filemark::whereIn('id', $permission)
                                ->orderBy('global', 'desc')
                                ->orderBy('label')->get();

            foreach ($filemarks as $filemark) {
                $filemarkDropdown[$filemark->id] = $filemark->label;
            }
        } catch (Exception $e) {
        }

        return $filemarkDropdown;
    }


     /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param string $source
     * @param string $query
     * @return Response
     */
    public function editAttributes($id, $source = null, $query = null)
    {
        //$source = Input::get('source');
        //$query = Input::get('query');
        
        $companyId = Auth::User()->getCompanyId();

        $permission = json_decode(Auth::User()->getUserData()->file_permission, true);

        $file = $this->repo->getFile($id, $companyId, $permission);

        $attributeSets = $this->meta_attribute->getCompanyAttributes($companyId);
        
        foreach ($attributeSets as $attribute) {
            $attribute->user_value  = $this->meta_attribute->getTargetAttributeValues($id, 'file', $attribute->id);
        }
        
        return View::make('users.file.attributes.edit')
                    ->with('file', $file)
                    ->with('attributeSets', $attributeSets)
                    ->with('source', $source)
                    ->with('query', $query);

    }


    public function updateAttributes($id)
    {
        //make sure is owner of file
        try {
            $companyId = Auth::User()->getCompanyId();
            $permission = json_decode(Auth::User()->getUserData()->file_permission, true);
            $file = $this->repo->getFile($id, $companyId, $permission);
        } catch (Exception $e) {
            exit('cannot retrieve file');
        }
        
        $id = $file->row_id;

        $input = Input::except('_method', '_token');

        $source = $input['source'];
        $query = $input['query'];

        if ($source == 'search') {
            $redirectRoute = 'users/file/query?query='.$input['query'];
        } else {
            $redirectRoute = 'users/file';
        }

        try {
            $this->meta_attribute->updateTargetAttributeValues($id, 'file', $input);

            Session::flash('message', 'File attributes successfully updated');


            return Redirect::to($redirectRoute);

        } catch (Exception $e) {
             Session::flash('error', 'Error updating file attributes');
            return Redirect::to($redirectRoute);
        }
    }
}
