<?php namespace Repositories\File;

use FileTable;
use Company;
use DB;

class FileRepository implements FileRepositoryInterface
{

    public function getFiles($companyId = null, array $permission = array(), $start = 0, $limit = 50)
    {

        $file_in_string = '';
        $user_file_mark_id_allowed = '';
        $match_company = '';

        if ($companyId) {
            $match_company = "AND fk_empresa = '" . addslashes($companyId) . "'";
        }

        if (count($permission) >= 1) {
            $file_in_string = implode(', ', $permission);
            $user_file_mark_id_allowed = " OR file_mark_id IN ($file_in_string) ";
        }

        $filter_file_permission = " AND (file_mark_id IS NULL OR file_mark_id = '' $user_file_mark_id_allowed ) ";


        $mainMatchQuery = "WHERE 1 $match_company $filter_file_permission";

        $sqlQuery = 'SELECT row_id, creadate, pages, filesize, moddate, filename, file_mark_id FROM files '.$mainMatchQuery.' ORDER BY creadate DESC LIMIT '. $start . ', ' . $limit;


        $files = DB::select(DB::raw($sqlQuery));

        return $files;
    }
}
