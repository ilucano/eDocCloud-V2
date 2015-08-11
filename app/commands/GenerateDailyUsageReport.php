<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Repositories\File\FileRepositoryInterface;
use Repositories\PricePlan\PricePlanRepositoryInterface;
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
    public function __construct(FileRepositoryInterface $filerepo, PricePlanRepositoryInterface $priceplanrepo)
    {
        $this->filerepo = $filerepo;
        $this->priceplanrepo = $priceplanrepo;

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

        if (count($companyIds) >= 1) {
            return Company::whereIn('row_id', $companyIds)->lists('row_id');
        } else {
            return Company::lists('row_id');
        }

    }


    public function generateDailyReport($companyId, $reportDate)
    {
        $this->info("generateDailyReport: $companyId $reportDate");

        if ($companyId == '' || $reportDate == '') {
            return;
        }
        
        $pricePlan = $this->priceplanrepo->getPricePlanByCompanyId($companyId);

        $dailyReport = new MonthlyUsageReport;
        
        $dailyReport->company_id = $companyId;
        $dailyReport->report_date = $reportDate;
        $dailyReport->plan_json = $pricePlan->toJson();
        $dailyReport->base_price = $pricePlan->base_price;
      
        $dailyReport->daily_new_users = $this->getDailyNewUsers($companyId, $reportDate);
        $dailyReport->current_number_of_users  = $this->getCurrentNumberOfUsers($companyId, $reportDate);

        $dailyReport->user_charges = $this->calculateUserCharges($pricePlan, $dailyReport->current_number_of_users);

        print_r($dailyReport);
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
                echo $tier->user_to . ' '. $tier->price_per_user;
                $charges = $remainder * $tier->price_per_user;
                
                break;
            }
        }

        return $charges;

    }
}
