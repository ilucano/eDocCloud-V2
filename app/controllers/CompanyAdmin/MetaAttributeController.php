<?php

use Repositories\MetaAttribute\MetaAttributeRepositoryInterface;

class CompanyAdminMetaAttributeController extends BaseController
{
    public function __construct(MetaAttributeRepositoryInterface $metaAttribute)
    {
        $this->meta_attribute = $metaAttribute;
    }

    public function index()
    {
        $companyId = Auth::User()->getCompanyId();
        
        $metaAttributes = $this->meta_attribute->getCompanyAttributes($companyId);

           // load the view and pass the data
        return View::make('companyadmin.metaattribute.index')
                     ->with('attributes', $metaAttributes);
        

    }
}
