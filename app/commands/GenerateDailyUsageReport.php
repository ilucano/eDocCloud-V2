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
        MonthlyUsageReportRepositoryInterface $monthly_usage_report_repo
    ) {
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
                $this->info("generateDailyReport: $companyId $reportDate");

                $this->monthly_usage_report_repo->generateDailyReport($companyId, $reportDate);
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
            return array_intersect($idsWithPlan, $companyIds);
        } else {
            return $idsWithPlan;
        }

    }
}
