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

    public function getAttributeOptions($attributeId);

    public function getFilterableTypes();

    public function getCompanyFilterableAttributes($companyId);

    public function getTargetAttributeValues($targetId, $targetType = 'file', $attributeId = null);

    public function updateTargetAttributeValues($targetId, $targetType = 'file', array $values = array());

    public function updateMetaAttribute($id, $input, $companyId = null);

    public function getCompanyAttributeHeaders($companyId);


}
