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
        
        $attributeTypes = $this->getAttributeTypes();
        $requiredOptions = $this->getRequiredDropdown();

        foreach ($metaAttributes as $attribute) {
            $attribute->type_name =  $attributeTypes[$attribute->type];
            $attribute->required_type = $requiredOptions[$attribute->required];
            $attribute->attribute_options = $this->getAttributeOptions($attribute->id);
        }

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

    public function getFilterableTypes()
    {
        return ['string', 'boolean', 'radio', 'select'];
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

    public function updateMetaAttribute($id, $input)
    {

        try {
            $meta = MetaAttribute::find($id);
            $meta->name = $input['name'];
            $meta->required = $input['required'];
            $meta->save();

            if (in_array($input['type'], $this->getTypesRequiredOptions())) {
                $metaAttributeOption = MetaAttributeOption::where('attribute_id', '=', $id)->first();
                $options = json_encode($input['options']);
                $metaAttributeOption->options = $options;
                $metaAttributeOption->save();
            }

            return true;

        } catch (Exception $e) {
            return false;

        }

    }

    /**
     * [getAttributeDetails description]
     * @param  [integer] $id [attribute id]
     * @return [object]     [Details of attribute]
     */
    public function getAttributeDetails($id)
    {
        $meta = MetaAttribute::find($id);
        $metaOptions = $this->getAttributeOptions($id);
        $meta->attribute_options = $metaOptions;

        return $meta;
    }


    public function getAttributeOptions($attributeId)
    {
        try {
            $metaOptions = MetaAttributeOption::where('attribute_id', '=', $attributeId)->first();

            return $metaOptions;

        } catch (Exception $e) {
            return null;
        }

    }


    public function getIsUniqueName($id, $name, $companyId)
    {
         ///check if exist of same name
        $check = MetaAttribute::where('name', '=', $name)
                             ->where('id', '<>', $id);

        if ($companyId) {
            $check = $check->where('fk_empresa', '=', $companyId);
        }

        $count = $check->count();
        
        return ($count >= 1) ? false : true;
    }


    public function getCompanyFilterableAttributes($companyId)
    {
        if (!$companyId) {
            return null;
        }

        $metaAttributes = MetaAttribute::where('fk_empresa', '=', $companyId)
                                         ->whereIn('type', $this->getFilterableTypes())
                                         ->get();
        
        $attributeTypes = $this->getAttributeTypes();
        $requiredOptions = $this->getRequiredDropdown();

        foreach ($metaAttributes as $attribute) {
            $attribute->type_name =  $attributeTypes[$attribute->type];
            $attribute->required_type = $requiredOptions[$attribute->required];
            $attribute->attribute_options = $this->getAttributeOptions($attribute->id);
        }

        return $metaAttributes;


    }
}
