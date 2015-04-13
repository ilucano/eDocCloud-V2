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


    public function getAttributeTypes()
    {

        return array(
                        'string'  => 'Text Box',
                        'boolean' => 'Yes/No',
                        'radio'  => 'Radio Button',
                        'select' => 'Drop Down',
                        'checkbox' => 'Check Boxes',
                        'multiselect' => 'Multiselect',
                        'datetime'     => 'Date Time',
                    );
    }

    public function getRequiredDropdown()
    {
        return ['1' => 'Yes',
                '0' => 'No'];
    }

    public function getTypesRequiredOptions()
    {
        return ['radio', 'select', 'checkbox', 'multiselect'];
    }
}
