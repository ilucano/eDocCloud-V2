<?php

class FileTable extends Eloquent {

	protected $table = 'files';

    public $timestamps = false;
	
	public $primaryKey  = 'row_id';


}
