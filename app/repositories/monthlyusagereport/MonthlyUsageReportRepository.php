<?php namespace Repositories\MonthlyUsageReport;

use Repositories\File\FileRepositoryInterface;
use Repositories\PricePlan\PricePlanRepositoryInterface;
use Repositories\MonthlyUsageReport\MonthlyUsageReportRepositoryInterface;
use Carbon\Carbon;
use \Company as Company;
use \User as User;
use \Helpers;
use \MonthlyUsageReport as MonthlyUsageReport;
use \DB as DB;

class MonthlyUsageReportRepository implements MonthlyUsageReportRepositoryInterface
{

    public function __construct(FileRepositoryInterface $filerepo, PricePlanRepositoryInterface $priceplanrepo)
    {
        $this->filerepo = $filerepo;
        $this->priceplanrepo = $priceplanrepo;
    }

    public function generateDailyReport($companyId, $reportDate)
    {
       
        if ($companyId == '' || $reportDate == '') {
            return;
        }
        
        $pricePlan = $this->priceplanrepo->getPricePlanByCompanyId($companyId);

        $dailyReport = MonthlyUsageReport::where(['company_id' => $companyId, 'report_date' => $reportDate])->first();
        
        if (!$dailyReport) {
            $dailyReport = new MonthlyUsageReport;
        }

        $dailyReport->company_id = $companyId;
        $dailyReport->report_date = $reportDate;
        $dailyReport->plan_id = $pricePlan->id;
        $dailyReport->plan_json = $pricePlan->toJson();
        $dailyReport->base_price = $pricePlan->base_price;
      
        $dailyReport->daily_new_users = $this->getDailyNewUsers($companyId, $reportDate);
        $dailyReport->current_number_of_users  = $this->getCurrentNumberOfUsers($companyId, $reportDate);

        $dailyReport->user_charges = $this->calculateUserCharges($pricePlan, $dailyReport->current_number_of_users);

        $dailyDataUsage = $this->filerepo->getDataUsage($companyId, $reportDate . ' 00:00:00', $reportDate . ' 23:59:59');
         
        $dailyReport->daily_storage_usage = Helpers::bytesToGigabytes($dailyDataUsage, null);

        $firstDayOfMonth = Carbon::createFromFormat('Y-m-d', $reportDate)->startOfMonth()->toDateString();
  
        $monthToDateDataUsage = $this->filerepo->getDataUsage($companyId, $firstDayOfMonth . ' 00:00:00', $reportDate . ' 23:59:59');
        
        $dailyReport->current_storage_usage = Helpers::bytesToGigabytes($monthToDateDataUsage, null);

        $dailyReport->storage_charges = $this->calculateStorageCharges($pricePlan, $dailyReport->current_storage_usage);

        $dailyReport->daily_own_scans = $this->filerepo->getNumberOfFiles($companyId, $reportDate . ' 00:00:00', $reportDate . ' 23:59:59', 1);
        
        $dailyReport->current_number_of_own_scans = $this->filerepo->getNumberOfFiles($companyId, $firstDayOfMonth . ' 00:00:00', $reportDate . ' 23:59:59', 1);
        
        $dailyReport->own_scan_charges = $this->calculateOwnScanCharges($pricePlan, $dailyReport->current_number_of_own_scans);

        $dailyReport->daily_plan_scans = $this->filerepo->getNumberOfFiles($companyId, $reportDate . ' 00:00:00', $reportDate . ' 23:59:59', 2);

        $dailyReport->current_number_of_plan_scans = $this->filerepo->getNumberOfFiles($companyId, $firstDayOfMonth . ' 00:00:00', $reportDate . ' 23:59:59', 2);

        $dailyReport->plan_scan_charges = $this->calculatePlanScanCharges($pricePlan, $dailyReport->current_number_of_plan_scans);

        $dailyReport->current_charges = $this->calculateCurrentCharges($dailyReport);
        
        $dailyReport->save();
        
    }

    private function getDailyNewUsers($companyId, $reportDate)
    {

        $userCount = DB::table('users')->join('logins', 'users.username', '=', 'logins.username')
                                       ->where('users.fk_empresa', '=', $companyId)
                                       ->where('logins.active', '=', 1)
                                       ->whereRaw("DATE(logins.created_at) = '$reportDate'")->count();


        return $userCount;
    }


    private function getCurrentNumberOfUsers($companyId, $reportDate)
    {

        $userCount = DB::table('users')->join('logins', 'users.username', '=', 'logins.username')
                                       ->where('users.fk_empresa', '=', $companyId)
                                       ->where('logins.active', '=', 1)
                                       ->whereRaw("DATE(logins.created_at) <= '$reportDate'")->count();

        
        return $userCount;
    }

    private function calculateUserCharges($plan, $numberOfUsers)
    {
        if ($plan->free_users >= $numberOfUsers) {
            return 0.00;
        }
        $charges = 999999;

        $remainder = $numberOfUsers - $plan->free_users;

        foreach ($plan->plan_user_tiers as $tier) {
            if ($remainder <= $tier->user_to) {
                $charges = $remainder * $tier->price_per_user;

                break;
            }
        }

        return $charges;

    }

    private function calculateStorageCharges($plan, $storage)
    {
        if ($plan->free_gb >= $storage) {
            return 0.00;
        }

        $charges = 999999;

        $remainder = $storage - $plan->free_gb;

        foreach ($plan->plan_storage_tiers as $tier) {
            if ($remainder <= $tier->gb_to) {
                $charges = $remainder * $tier->price_per_gb;

                break;
            }
        }

        return $charges;

    }

    private function calculateOwnScanCharges($plan, $numberOfFiles)
    {
        if ($plan->free_own_scans >= $numberOfFiles) {
            return 0.00;
        }

        $charges = 999999;

        $remainder = $numberOfFiles - $plan->free_own_scans;

        foreach ($plan->plan_own_scan_tiers as $tier) {
            if ($remainder <= $tier->own_scan_to) {
                $charges = $remainder * $tier->price_per_own_scan;

                break;
            }
        }

        return $charges;

    }

    private function calculatePlanScanCharges($plan, $numberOfFiles)
    {
        if ($plan->free_plan_scans >= $numberOfFiles) {
            return 0.00;
        }

        $charges = 999999;

        $remainder = $numberOfFiles - $plan->free_plan_scans;

        foreach ($plan->plan_plan_scan_tiers as $tier) {
            if ($remainder <= $tier->plan_scan_to) {
                $charges = $remainder * $tier->price_per_plan_scan;

                break;
            }
        }

        return $charges;

    }

    private function calculateCurrentCharges($dailyReport)
    {
        return $dailyReport->base_price + $dailyReport->user_charges + $dailyReport->storage_charges + $dailyReport->own_scan_charges + $dailyReport->plan_scan_charges;
    }


    /**
     * Return the lastest charges of each company
     * @param  integer $companyId if filter by company id
     * @return array  $results Eloquent object
     */
    public function getCurrentUsageReport($companyId = null)
    {
        $rowIds = DB::table('monthly_usage_reports')
                     ->select(DB::raw('max(report_date) as latest_date, company_id'));

        if ($companyId) {
            $rowIds  = $rowIds->where('company_id', '=', $companyId);
        }
            $rowIds = $rowIds->groupBy('company_id')
                             ->get();

        $results = array();
        foreach ($rowIds as $row) {
            $dailyReport = MonthlyUsageReport::where('company_id', '=', $row->company_id)
                                             ->where('report_date', '=', $row->latest_date)
                                             ->first();
            try {
                $dailyReport->company_name = Company::where('row_id', '=', $dailyReport->company_id)->first()->company_name;
            } catch (\Exception $e) {
                $dailyReport->company_name = '';
            }

            $results[] = $dailyReport;
        }

        return $results;
    }
}
