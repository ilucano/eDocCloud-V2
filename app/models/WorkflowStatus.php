<?php

class WorkflowStatus extends Eloquent {

	protected $table = 'wf_status';

    public $timestamps = false;
	
	public $primaryKey  = 'row_id';
	

}
