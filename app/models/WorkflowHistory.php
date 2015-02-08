<?php

class WorkflowHistory extends Eloquent {

	protected $table = 'wf_history';

    public $timestamps = false;
	
	public $primaryKey  = 'row_id';
	

}
