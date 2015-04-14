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
}
