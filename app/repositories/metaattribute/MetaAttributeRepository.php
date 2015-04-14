<?php namespace Repositories\MetaAttribute;

use MetaAttribute;
use MetaAttributeOption;

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

    /**
     * [createMetaAttribute description]
     * @param  integer $companyId [description]
     * @param  array   $input      [description]
     * @return [type]             [description]
     */
    public function createMetaAttribute($companyId = 0, $input)
    {

        try {
            $meta = new MetaAttribute;
            
            $meta->fk_empresa = $companyId;
            $meta->type = $input['type'];
            $meta->name = $input['name'];
            $meta->required = $input['required'];
            $meta->save();

            if (in_array($input['type'], $this->getTypesRequiredOptions())) {
                $attribute_id = $meta->id;
                $options = json_encode($input['options']);
                
                $metaAttributeOption = new MetaAttributeOption;
                $metaAttributeOption->attribute_id = $attribute_id;
                $metaAttributeOption->options = $options;
                $metaAttributeOption->save();
            }

            return true;

        } catch (Exception $e) {
            return false;

        }



    }
}
