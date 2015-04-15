<?php namespace Repositories\MetaAttribute;

interface MetaAttributeRepositoryInterface
{
    public function getCompanyAttributes($companyId);

    public function getAttributeTypes();

    public function getRequiredDropdown();

    public function getTypesRequiredOptions();

    public function createMetaAttribute($companyId, $input);

    public function getAttributeDetails($id);

    public function getIsUniqueName($id, $name, $companyId);
}
