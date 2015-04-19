<?php

namespace repositories\metaattribute;

use MetaAttribute;
use MetaAttributeOption;
use MetaTargetAttributeValue;

class MetaAttributeRepository implements MetaAttributeRepositoryInterface
{
    public function getCompanyAttributes($companyId)
    {
        if (!$companyId) {
            return;
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
                        //'datetime'     => 'Date Time',
                    );
    }

    public function getFilterableTypes()
    {
        return ['string', 'boolean', 'radio', 'select'];
    }

    public function getRequiredDropdown()
    {
        return ['1' => 'Yes',
                '0' => 'No', ];
    }

    public function getTypesRequiredOptions()
    {
        return ['radio', 'select', 'checkbox', 'multiselect'];
    }

    /**
     * [createMetaAttribute description].
     *
     * @param int   $companyId [description]
     * @param array $input     [description]
     *
     * @return [type] [description]
     */
    public function createMetaAttribute($companyId = 0, $input)
    {
        try {
            $meta = new MetaAttribute();

            $meta->fk_empresa = $companyId;
            $meta->type = $input['type'];
            $meta->name = $input['name'];
            $meta->required = $input['required'];
            $meta->save();

            if (in_array($input['type'], $this->getTypesRequiredOptions())) {
                $attribute_id = $meta->id;

                foreach ($input['options'] as $option) {
                    $metaAttributeOption = new MetaAttributeOption();
                    $metaAttributeOption->attribute_id = $attribute_id;
                    $metaAttributeOption->options = $option;
                    $metaAttributeOption->save();
                }
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateMetaAttribute($id, $input, $companyId = null)
    {
        try {
            $meta = MetaAttribute::where('id', '=', $id);

            //make sure it's own by that company
            if ($companyId) {
                $meta = $meta->where('fk_empresa', '=', $companyId);
            }
            $meta = $meta->first();
        } catch (Exception $e) {
            return false;
        }

        try {
            $meta->name = $input['name'];
            $meta->required = $input['required'];
            $meta->save();

            if (in_array($input['type'], $this->getTypesRequiredOptions())) {
                //remove old attribute set
                //$removeOld = MetaAttributeOption::where('attribute_id', '=', $id)->delete();

                foreach ($input['options'] as $key => $option) {
                    $metaAttributeOption = MetaAttributeOption::where('id', '=', $key)->first();
                    $metaAttributeOption->options = $option;
                    $metaAttributeOption->save();
                }

                foreach ($input['newoptions'] as $newoption) {
                    if($newoption != '') {
                        $metaAttributeOption = new MetaAttributeOption();
                        $metaAttributeOption->attribute_id = $id;
                        $metaAttributeOption->options = $newoption;
                        $metaAttributeOption->save();
                    }
                }
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * [getAttributeDetails description].
     *
     * @param [integer] $id [attribute id]
     *
     * @return [object] [Details of attribute]
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
            $metaOptions = MetaAttributeOption::where('attribute_id', '=', $attributeId)->get();

            return $metaOptions;
        } catch (Exception $e) {
            return;
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
            return;
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

    public function getTargetAttributeValues($targetId, $targetType = 'file', $attributeId = null)
    {
        if (!$targetId) {
            return;
        }
        try {
            $records = MetaTargetAttributeValue::where('target_id', '=', $targetId)
                                                ->where('target_type', '=', $targetType);
            if ($attributeId) {
                $records = $records->where('attribute_id', '=', $attributeId);
            }

            $records = $records->get();
        } catch (Exception $e) {
            return;
        }

        return $records;
    }


    public function updateTargetAttributeValues($targetId, $targetType = 'file', array $values = array())
    {

        foreach ($values as $attribute_id => $value) {
            //if multiple options
            if (is_array($value)) {
                try {
                    //remove existing records
                    $remove = MetaTargetAttributeValue::where('target_id', '=', $targetId)
                                                      ->where('target_type', '=', $targetType)
                                                      ->where('attribute_id', '=', $attribute_id)
                                                      ->delete();
                    foreach ($value as $optionId) {
                        if ($optionId != '') {
                            $record = new MetaTargetAttributeValue();
                            $record->target_id = $targetId;
                            $record->target_type = $targetType;
                            $record->attribute_id = $attribute_id;
                            $record->value = $optionId;
                            $record->save();
                        }
                    }

                } catch (Exception $e) {
                    continue;
                }


            } else {
                //single option
                try {
                    $exist = MetaTargetAttributeValue::where('target_id', '=', $targetId)
                                                     ->where('target_type', '=', $targetType)
                                                     ->where('attribute_id', '=', $attribute_id)->first();


                } catch (Exception $e) {
                    $exist = null;
                }
                

                if (count($exist) >=1) {
                    $record = $exist;
                } else {
                    $record = new MetaTargetAttributeValue();
                }
     
                $record->target_id = $targetId;
                $record->target_type = $targetType;
                $record->attribute_id = $attribute_id;
                $record->value = $value;
                $record->save();
            }
        }

    }



    public function getCompanyAttributeHeaders($companyId)
    {
        $attributes = $this->getCompanyAttributes(($companyId));
       
        $headers = array();
        foreach ($attributes as $attribute) {
            $headers[$attribute->id] = $attribute->name;
        }

        return $headers;
    }
}
