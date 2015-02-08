<?php

class User extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    public $timestamps = false;
    
	public $primaryKey  = 'row_id';
	
}
