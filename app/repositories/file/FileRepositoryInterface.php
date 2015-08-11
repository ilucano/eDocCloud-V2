<?php
namespace Repositories\File;
 
interface FileRepositoryInterface
{
    public function getFiles($companyId = null, $permission = null, $start = 0, $limit = 50, array $filters = array());

    public function getFile($id, $companyId = null, $permission = null);

    public function getDataUsage($companyId = null, $fromDate = '1970-01-01 00:00:00', $toDate = null);

    public function getNumberOfFiles($companyId = null, $fromDate = '1970-01-01 00:00:00', $toDate = null, $scanType = null);

    public function getMonthlyDataUsage($companyId = null, $limit = 12);

    public function getMonthlyNumberOfFiles($companyId = null, $limit = 12);

    public function setOrderBoxChartForFile($file);
}
