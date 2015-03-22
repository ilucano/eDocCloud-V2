<?php
namespace Repositories\Filemark;
 
interface FilemarkRepositoryInterface {
	
	public function selectAll();
	
	public function find($id);
	
}