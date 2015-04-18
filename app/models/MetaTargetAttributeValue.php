<?php

class MetaTargetAttributeValue extends Eloquent {

	protected $table = 'meta_target_attribute_values';
	public $timestamps = true;

	public function attributeId()
	{
		return $this->belongsTo('MetaAttribute');
	}

}