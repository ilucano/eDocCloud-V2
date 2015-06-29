<?php

namespace repositories\file;

use FileTable;
use DB;

class FileRepository implements FileRepositoryInterface
{
    public function getFiles($companyId = null, $permission = null, $start = 0, $limit = 50, array $filters = array())
    {
        if ($permission == null) {
            $permission = array();
        }

        $file_in_string = '';
        $user_file_mark_id_allowed = '';
        $match_company = '';
        $filter_attribute_files = '';

        if ($companyId) {
            $match_company = "AND fk_empresa = '".addslashes($companyId)."'";
        }

        if (count($permission) >= 1) {
            $file_in_string = implode(', ', $permission);
            $user_file_mark_id_allowed = " OR file_mark_id IN ($file_in_string) ";
        }

        if ($filters) {
            $joinTables = array();
            $andString = array();

            foreach ($filters as $attribute_id => $value) {
                if (!$value) {
                    continue;
                }
                $joinTables[] = " JOIN meta_target_attribute_values AS table_{$attribute_id} ON `master`.target_id  =  table_{$attribute_id}.target_id ";
                $andString[]  = " AND table_{$attribute_id}.attribute_id = '".addslashes($attribute_id)."' AND table_{$attribute_id}.value = '".addslashes($value)."' ";
            }

            if (count($joinTables) >= 1) {
                $attributeSql = 'SELECT DISTINCT(`master`.target_id) FROM  `meta_target_attribute_values`  as `master` ';
                $attributeSql .= implode(' ', $joinTables);
                $attributeSql .= ' WHERE 1 ';
                $attributeSql .= implode(' ', $andString);

                $filteredFiles = DB::select(DB::raw($attributeSql));

                $arrayFilteredFile = array();

                foreach ($filteredFiles as $filteredFile) {
                    $arrayFilteredFile[] = $filteredFile->target_id;
                }

                if (count($arrayFilteredFile) >= 1) {
                    $filter_attribute_files = ' AND row_id IN ('.implode(', ', $arrayFilteredFile).')';
                }
            }
        }

        $filter_file_permission = " AND (file_mark_id IS NULL OR file_mark_id = '' $user_file_mark_id_allowed ) ";

        $mainMatchQuery = "WHERE 1 $match_company $filter_file_permission $filter_attribute_files";

        $sqlQuery = 'SELECT row_id, creadate, pages, filesize, moddate, filename, file_mark_id FROM files '.$mainMatchQuery.' ORDER BY creadate DESC LIMIT '.$start.', '.$limit;

        $files = DB::select(DB::raw($sqlQuery));

        return $files;
    }


    public function getFile($id, $companyId = null, $permission = null)
    {
        if (!$id) {
            return;
        }

        if ($permission == null) {
            $permission = array();
        }

        $file = FileTable::where('row_id', '=', $id)
                            ->where(function ($query) use ($permission) {
                                    if (count($permission) >= 1) {
                                        $query->whereIn('file_mark_id', $permission)
                                              ->orWhere('file_mark_id', '=', '')
                                              ->orWhereRaw('file_mark_id is null');
                                    } else {
                                        $query->where('file_mark_id', '=', '')
                                              ->orWhereRaw('file_mark_id is null');
                                    }
                                }
                              );

        if ($companyId) {
            $file = $file->where('fk_empresa', '=', $companyId);
        }

        $file = $file->first();

        return $file;
    }

    public function getDataUsage($companyId = null, $fromDate = '1970-01-01 00:00:00', $toDate = null)
    {
        if ($toDate == '') {
            $toDate = date('Y-m-d H:i:s');
        }

        $total = Db::table('files')->where('creadate', '>=', $fromDate)
                                    ->where('creadate', '<=', $toDate);
        if ($companyId) {
            $total = $total->where('fk_empresa', '=', $companyId);
        }

        $total = $total->sum('filesize');

        return $total;
    }

    public function getNumberOfFiles($companyId = null, $fromDate = '1970-01-01 00:00:00', $toDate = null)
    {
        if ($toDate == '') {
            $toDate = date('Y-m-d H:i:s');
        }

        $total = Db::table('files')->where('creadate', '>=', $fromDate)
                                    ->where('creadate', '<=', $toDate);

        if ($companyId) {
            $total = $total->where('fk_empresa', '=', $companyId);
        }

        $total = $total->count('row_id');

        return $total;
    }

    public function getMonthlyDataUsage($companyId = null, $limit = 12)
    {
        $data = array();

        for ($i = $limit - 1; $i >= 0; $i--) {
            $startTimeStamp = strtotime(date('Y-m-d')." -$i months");
            $startDate = date('Y-m-01', $startTimeStamp).' 00:00:00';
            $toDate = date('Y-m-t', $startTimeStamp).' 23:59:59';
            $yearMonth = date('Y-m', $startTimeStamp);
            $data[$yearMonth] = $this->getDataUsage($companyId, $startDate, $toDate);
        }

        return $data;
    }

    public function getMonthlyNumberOfFiles($companyId = null, $limit = 12)
    {
        $data = array();

        for ($i = $limit - 1; $i >= 0; $i--) {
            $startTimeStamp = strtotime(date('Y-m-d')." -$i months");
            $startDate = date('Y-m-01', $startTimeStamp).' 00:00:00';
            $toDate = date('Y-m-t', $startTimeStamp).' 23:59:59';
            $yearMonth = date('Y-m', $startTimeStamp);
            $data[$yearMonth] = $this->getNumberOfFiles($companyId, $startDate, $toDate);
        }

        return $data;
    }
}
