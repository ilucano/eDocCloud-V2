<?php namespace App\Services\Validation;

use Illuminate\Validation\Factory as IlluminateValidator;
use Repositories\MetaAttribute\MetaAttributeRepositoryInterface;

class MetaAttributeValidator extends AbstractValidator
{

    public function __construct(IlluminateValidator $validator, MetaAttributeRepositoryInterface $metaAttribute)
    {
        $this->_validator = $validator;
        $this->_repo = $metaAttribute;
    }

      
    public function validateOnStore(array $input, array $rules = array(), array $custom_errors = array())
    {
        if (empty($rules)) {
            $rules = [
                      'name' => 'required',
                      'type' => 'required'
                      ];

        }

        if (in_array($input['type'], $this->_repo->getTypesRequiredOptions())) {
            $_options = $input['options'];

            for ($i = 0; $i < count($_options); $i++) {
                $rules['options.' . $i] = 'required';
            }

        }

        return $this->validate($input, $rules, $custom_errors);

    }

    public function validateOnUpdate(array $input, array $rules = array(), array $custom_errors = array())
    {
        
        if (empty($rules)) {
            $rules = [
                      'name' => 'required',
                      'type' => 'required'
                      ];

        }

        if (in_array($input['type'], $this->_repo->getTypesRequiredOptions())) {
            $_options = $input['options'];

            foreach ($_options as $key => $_option) {
                $rules['options.' . $key] = 'required';
            }
            
        }

        return $this->validate($input, $rules, $custom_errors);

    }

    /**
     * [validateUniqueName description]
     * @return [boolean] [description]
     */
    public function validateUniqueName($id, $name, $companyId = null)
    {
        return $this->_repo->getIsUniqueName($id, $name, $companyId);

    }
    
}
