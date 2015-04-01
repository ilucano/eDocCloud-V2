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
}
