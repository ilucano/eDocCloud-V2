<?php namespace Repositories\Filemark;

use Filemark;
use FileTable;
use Company;

class FilemarkRepository implements FilemarkRepositoryInterface
{
    public function getAll()
    {
        $filemarks = Filemark::orderBy('global', 'asc')
                                ->orderBy('label')->get();

        foreach ($filemarks as $filemark) {
            $filemark->file_count = $this->getFilemarkCount($filemark->id);

            $filemark->company_name = $this->getCompanyNameById($filemark->fk_empresa);
        }

        return $filemarks;
    }

    /**
     * Return number of file with this mark id.
     *
     * @param interger $id        Filemark Id
     * @param integer  $companyId Filter by company Id. Default null
     *
     * @return integer $count
     */
    protected function getFilemarkCount($id, $companyId = null)
    {

        //get number of files
        try {
            $file = FileTable::where('file_mark_id', '=', $id);

            if ($companyId) {
                $file  = $file->where('fk_empresa', '=', $companyId);
            }

            return $file->count();
        } catch (Exception $e) {
            return 0;
        }
    }

    public function find($id)
    {
        return Filemark::find($id);
    }

    public function getGlobalFilemark()
    {
        return Filemark::where('global', '=', 1);
    }

    /**
     * Return filemarks of a company.
     *
     * @param integer $companyId Company Id
     * @param boolean $global    Retrieve global label or not.
     */
    public function getFilemarkByCompany($companyId, $global = true)
    {
        if ($companyId == '') {
            return;
        }

        $filemarks = Filemark::where('fk_empresa', '=', $companyId);

        if ($global) {
            $filemarks->orWhere('global', '=', 1)
                      ->orderBy('global', 'desc');
        }

        $filemarks = $filemarks->get();

        foreach ($filemarks as $filemark) {
            $filemark->file_count = $this->getFilemarkCount($filemark->id, $companyId);
        }

        return $filemarks;
    }

    /**
     * [getCompanyNameById Return company_name by id].
     *
     * @param integer $id [id of company]
     *
     * @return string [name]
     */
    protected function getCompanyNameById($id)
    {
        if (!$id) {
            return;
        }

        try {
            return Company::where('row_id', '=', $id)->first()->company_name;
        } catch (Exception $e) {
            return;
        }
    }
}
