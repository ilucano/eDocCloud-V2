<?php
namespace Repositories\File;
 
interface FileRepositoryInterface
{
    public function getFiles($companyId = null, array $permission = array(), $start = 0, $limit = 50);

    public function getFile($id, $companyId = null, array $permission = array());
}
