<?php namespace Repositories\File;

use FileTable;
use Company;
use DB;
use Helpers;
use MetaTargetAttributeValue;

class FileRepository implements FileRepositoryInterface
{

    public function getFiles($companyId = null, array $permission = array(), $start = 0, $limit = 50, array $filters = array())
    {

        $file_in_string = '';
        $user_file_mark_id_allowed = '';
        $match_company = '';
        $filter_attribute_files = '';

        if ($companyId) {
            $match_company = "AND fk_empresa = '" . addslashes($companyId) . "'";
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
                $andString[]  = " AND table_{$attribute_id}.attribute_id = '".addslashes($attribute_id). "' AND table_{$attribute_id}.value = '".addslashes($value). "' ";
            }

            if (count($joinTables) >= 1) {
                $attributeSql = "SELECT DISTINCT(`master`.target_id) FROM  `meta_target_attribute_values`  as `master` ";
                $attributeSql .= implode(" ", $joinTables);
                $attributeSql .= " WHERE 1 ";
                $attributeSql .= implode(" ", $andString);

                
                $filteredFiles = DB::select(DB::raw($attributeSql));

                $arrayFilteredFile = array();

                foreach ($filteredFiles as $filteredFile) {
                    $arrayFilteredFile[] = $filteredFile->target_id;
                }

                if (count($arrayFilteredFile) >= 1) {
                    $filter_attribute_files = " AND row_id IN (". implode(", ", $arrayFilteredFile) .")";
                }
            }

        }

        $filter_file_permission = " AND (file_mark_id IS NULL OR file_mark_id = '' $user_file_mark_id_allowed ) ";

        $mainMatchQuery = "WHERE 1 $match_company $filter_file_permission $filter_attribute_files";

        $sqlQuery = 'SELECT row_id, creadate, pages, filesize, moddate, filename, file_mark_id FROM files '.$mainMatchQuery.' ORDER BY creadate DESC LIMIT '. $start . ', ' . $limit;


        $files = DB::select(DB::raw($sqlQuery));
 
        return $files;
    }

    public function getFile($id, $companyId = null, array $permission = array())
    {
        if (!$id) {
            return null;
        }


        $file = FileTable::where('row_id', '=', $id)
                            ->where(function($query) use ($permission)
                                {   
                                    if (count($permission) >= 1) {
                                        $query->whereIn('file_mark_id', $permission)
                                              ->orWhere('file_mark_id','=', '')
                                              ->orWhereRaw('file_mark_id is null');
                                    }
                                    else {
                                        $query->where('file_mark_id','=', '')
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
}
