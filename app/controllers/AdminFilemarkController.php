<?php

use Repositories\Filemark\FilemarkRepositoryInterface;

class AdminFilemarkController extends \BaseController {
	
	
	public function __construct(FilemarkRepositoryInterface $filemark)
	{
		$this->filemark = $filemark;
	}
 
 

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$filemarks = $this->filemark->getFilemarkByCompany(3, false);
		//$filemarks = $this->filemark->getAll();
		echo "<pre>";
		print_r($filemarks);
	}

 
}
