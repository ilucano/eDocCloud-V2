<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Repositories\File\FileRepositoryInterface;
use Repositories\PricePlan\PricePlanRepositoryInterface;

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
        print_r($this->priceplanrepo->getPricePlans());
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
            array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }
}
