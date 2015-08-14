<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Repositories\File\FileRepositoryInterface;
use Repositories\PricePlan\PricePlanRepositoryInterface;
use Repositories\MonthlyUsageReport\MonthlyUsageReportRepositoryInterface;
use Carbon\Carbon;
use \Company as Company;
use \User as User;
use \Helpers;

class GenerateDailyUsageReport extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'datausage:dailyreport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily usage and bill of company in to monthly table';

    protected $daterange;

    protected $companylist = array();

    protected $datelist = array();

    /**
     * Create a new command instance.
     */
    public function __construct(
        FileRepositoryInterface $filerepo,
        PricePlanRepositoryInterface $priceplanrepo,
        MonthlyUsageReportRepositoryInterface $monthly_usage_report_repo)
    {
        $this->filerepo = $filerepo;
        $this->priceplanrepo = $priceplanrepo;
        $this->monthly_usage_report_repo = $monthly_usage_report_repo;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        //
        if ($this->option('date-range')) {
            $this->daterange = $this->option('date-range');
        } else {
            $yesterday = Carbon::now()->subDay(1)->toDateString();
             
            $this->daterange = $yesterday. '_'. $yesterday;
        }

        $tmp = explode('_', $this->daterange);
        
        $fromDate = min($tmp);

        $toDate = max($tmp);
        
        
        $dateToCheck = $fromDate;

        while ($dateToCheck <= $toDate) {
            $this->datelist[] = $dateToCheck;
            $dateToCheck = Carbon::createFromFormat('Y-m-d', $dateToCheck)->addDay(1)->toDateString();
        }



        if ($this->option('company-ids')) {
            $this->companylist = $this->getCompanyIds(explode(',', $this->option('company-ids')));
        } else {
            $this->companylist = $this->getCompanyIds();
        }

        foreach ($this->companylist as $companyId) {
            $this->info('Generate daily stats for company id ' . $companyId);

            foreach ($this->datelist as $reportDate) {
                $this->generateDailyReport($companyId, $reportDate);
            }

            $this->info('');
        }
 
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            //array('example', InputArgument::REQUIRED, 'An example argument.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('date-range', null, InputOption::VALUE_OPTIONAL, 'Date Range, e.g. 2015-06-10_2015-06-13 . Date are inclusive', null),
            array('company-ids', null, InputOption::VALUE_OPTIONAL, 'Specify company by IDs, separate by comma. e.g. 3,4,6,10', null),
        );
    }

    /**
     * [getCompanyIds description]
     * @return [type] [description]
     */
    private function getCompanyIds($companyIds = array())
    {
        $companyWithPlan = $this->priceplanrepo->getCompanyWithPlan();

        $idsWithPlan = array();

        foreach ($companyWithPlan as $company) {
            $idsWithPlan[] = $company->row_id;
        }


        if (count($companyIds) >= 1) {
            return array_intersect($idWithPlan, $companyIds);
        } else {
            return $idsWithPlan;
        }

    }


    public function generateDailyReport($companyId, $reportDate)
    {
        $this->info("generateDailyReport: $companyId $reportDate");

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
        $this->info("getDailyNewUsers: $companyId $reportDate");
        $userCount = DB::table('users')->join('logins', 'users.username', '=', 'logins.username')
                                       ->where('users.fk_empresa', '=', $companyId)
                                       ->where('logins.active', '=', 1)
                                       ->whereRaw("DATE(logins.created_at) = '$reportDate'")->count();

        
        $this->info($userCount);

        return $userCount;
    }


    private function getCurrentNumberOfUsers($companyId, $reportDate)
    {
        $this->info("getCurrentNumberOfUsers: $companyId $reportDate");
        $userCount = DB::table('users')->join('logins', 'users.username', '=', 'logins.username')
                                       ->where('users.fk_empresa', '=', $companyId)
                                       ->where('logins.active', '=', 1)
                                       ->whereRaw("DATE(logins.created_at) <= '$reportDate'")->count();

        
        $this->info($userCount);

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
}
