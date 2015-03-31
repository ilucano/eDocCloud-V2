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
        $companyId = 5;

        $metaAttributes = $this->meta_attribute->getCompanyAttributes($companyId);

        dd($metaAttributes);
    }
}
