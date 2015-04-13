<?php

use Repositories\MetaAttribute\MetaAttributeRepositoryInterface;

class CompanyAdminMetaAttributeController extends BaseController
{
    public function __construct(MetaAttributeRepositoryInterface $metaAttribute)
    {
        $this->repo = $metaAttribute;
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
        $rules = [
            'name' => 'required',
            'type' => 'required',
        ];
        

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('companyadmin/metaattribute/create')
                ->withErrors($validator)
                ->withInput();
        }
        

        if (in_array(Input::get('type'), $this->repo->getTypesRequiredOptions())) {
            $_options = Input::get('options');

            for ($i = 0; $i < count($_options); $i++) {
                $rules['options.' . $i] = 'required';
            }

            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {
                return Redirect::to('companyadmin/metaattribute/create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

    }
}
