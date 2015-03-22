<?php
namespace Repositories\Filemark;

use Filemark;

class FilemarkRepository implements FilemarkRepositoryInterface {
	
	public function selectAll()
	{
		return Filemark::all();
	}
	
	public function find($id)
	{
		return Filemark::find($id);
	}
}