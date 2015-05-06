<?php
namespace Repositories\File;
 
interface FileRepositoryInterface
{
    public function getFiles($companyId = null, array $permission = array(), $start = 0, $limit = 50, array $filters = array());

    public function getFile($id, $companyId = null, array $permission = array());

    public function getDataUsage($companyId = null, $fromDate = '1970-01-01 00:00:00', $toDate = null);

    public function getNumberOfFiles($companyId = null, $fromDate = '1970-01-01 00:00:00', $toDate = null);

    public function getMonthlyDataUsage($companyId = null, $limit = 12);

    public function getMonthlyNumberOfFiles($companyId = null, $limit = 12);
}
