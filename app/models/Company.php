<?php

class Company extends Eloquent {

	protected $table = 'companies';

    public $timestamps = false;
 
    public $primaryKey  = 'row_id';
}
