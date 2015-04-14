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

           // load the view and pass the data
        return View::make('companyadmin.metaattribute.index')
                     ->with('attributes', $metaAttributes);
         
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
}
