<?php

class Object extends Eloquent {

	protected $table = 'objects';

    public $timestamps = false;
	
	public $primaryKey  = 'row_id';
}
