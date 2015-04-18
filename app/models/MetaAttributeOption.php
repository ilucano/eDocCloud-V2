<?php

class MetaAttributeOption extends Eloquent {

	protected $table = 'meta_attribute_options';
	public $timestamps = true;

	public function attributeId()
	{
		return $this->belongsTo('MetaAttribute', 'id');
	}

}