<?php

use Teepluss\Up2\Up2Trait;

class PersonalFolder extends Eloquent {

    use Up2Trait;
    
	protected $table = 'personal_folder';
	public $timestamps = true;

}