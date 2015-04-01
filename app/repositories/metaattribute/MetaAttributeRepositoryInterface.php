<?php namespace Repositories\MetaAttribute;

interface MetaAttributeRepositoryInterface
{
    public function getCompanyAttributes($companyId);

    public function getAttributeTypes();

    public function getRequiredDropdown();
}
