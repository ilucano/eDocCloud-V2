<?php
namespace Repositories\Filemark;
 
interface FilemarkRepositoryInterface {
	
	public function getAll();
	
	public function find($id);
	
	public function getGlobalFilemark();
	
	public function getFilemarkByCompany($companyId, $global);
}