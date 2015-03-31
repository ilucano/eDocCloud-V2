<?php namespace Repositories\MetaAttribute;

use MetaAttribute;

class MetaAttributeRepository implements MetaAttributeRepositoryInterface
{
    public function getCompanyAttributes($companyId)
    {
        if (!$companyId) {
            return null;
        }

        $metaAttributes = MetaAttribute::where('fk_empresa', '=', $companyId)->get();

        return $metaAttributes;
    }
}
