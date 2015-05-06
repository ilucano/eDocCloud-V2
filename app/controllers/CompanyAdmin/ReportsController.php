<?php

use Repositories\File\FileRepositoryInterface;

class CompanyAdminReportsController extends BaseController
{

    public function __construct(FileRepositoryInterface $filerepo)
    {
        $this->filerepo = $filerepo;

    }


    public function showUsageChart()
    {
        $companyId = Auth::User()->getCompanyId();

        $company = Company::where('row_id', '=', $companyId)->first();

        $company->todate_data_usage = $this->filerepo->getDataUsage($company->row_id);


        $company->monthly_data_usage = $this->filerepo->getMonthlyDataUsage($company->row_id);
        $company->monthly_number_of_files = $this->filerepo->getMonthlyNumberOfFiles($company->row_id);

        return View::make('companyadmin.reports.usagechart')
                   ->with('company', $company);


    }

    
}
