<?php

use Repositories\MetaAttribute\MetaAttributeRepositoryInterface;
use App\Services\Validation\MetaAttributeValidator as Validator;
use App\Exceptions\ValidationException;

class CompanyAdminMetaAttributeController extends BaseController
{
    public function __construct(MetaAttributeRepositoryInterface $metaAttribute, Validator $validator)
    {
        $this->repo = $metaAttribute;
        $this->validator = $validator;
    }

    public function index()
    {
        $companyId = Auth::User()->getCompanyId();

        $metaAttributes = $this->repo->getCompanyAttributes($companyId);


        return View::make('companyadmin.metaattribute.index')
                     ->with('metaAttributes', $metaAttributes);
         
    }

    public function edit($id)
    {
        $metaAttribute = $this->repo->getAttributeDetails($id);
 
        $attributeTypes = $this->repo->getAttributeTypes();
        $requiredDropdown = $this->repo->getRequiredDropdown();

        return View::make('companyadmin.metaattribute.edit')
                     ->with('metaAttribute', $metaAttribute)
                     ->with('attributeTypes', $attributeTypes)
                     ->with('requiredDropdown', $requiredDropdown);

    }

    public function create()
    {
        $attributeTypes = $this->repo->getAttributeTypes();
        $requiredDropdown = $this->repo->getRequiredDropdown();

        return View::make('companyadmin.metaattribute.create')
                     ->with('attributeTypes', $attributeTypes)
                     ->with('requiredDropdown', $requiredDropdown);
    }


    public function store()
    {
       
        try {
            $validate_data = $this->validator->validateOnStore(Input::all());

        } catch (ValidationException $e) {
            return Redirect::to('companyadmin/metaattribute/create')
                    ->withErrors($e->get_errors())
                    ->withInput();

        }

        $companyId = Auth::User()->getCompanyId();

        try {
            $this->repo->createMetaAttribute($companyId, Input::all());

            Session::flash('message', 'Attribute successfully created');
            
            return Redirect::to('companyadmin/metaattribute');


        } catch (Exception $e) {
            Session::flash('error', 'Error creating attribute');
            return Redirect::to('companyadmin/metaattribute/create');

        }
 
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $input = Input::all();

        try {
            $validate_data = $this->validator->validateOnUpdate($input);

        } catch (ValidationException $e) {
            return Redirect::to('companyadmin/metaattribute/'.$id.'/edit')
                    ->withErrors($e->get_errors())
                    ->withInput();

        }

        $companyId = Auth::User()->getCompanyId();
        
        $name = Input::get('name');

        $unique = $this->validator->validateUniqueName($id, $name, $companyId);
        
        if ($unique == false) {
            Session::flash('error', 'Attribute Name <strong>'.$name.'</strong> already exists');
            return Redirect::to('companyadmin/metaattribute/'.$id.'/edit');
            exit;
        }

        //all ok, update it
        try {
            $this->repo->updateMetaAttribute($id, $input, $companyId);
            Session::flash('message', 'Attribute successfully updated');

            return Redirect::to('companyadmin/metaattribute/'.$id.'/edit');
        } catch (Exception $e) {
            Session::flash('error', 'Error updating attribute');

            return Redirect::to('companyadmin/metaattribute/'.$id.'/edit');
        }


    }
}
