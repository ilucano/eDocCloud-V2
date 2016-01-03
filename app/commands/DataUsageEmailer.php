<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Repositories\File\FileRepositoryInterface;
use Carbon\Carbon;

class DataUsageEmailer extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'datausage:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email company admin of their data usage';

    /**
     * Create a new command instance.
     */
    public function __construct(FileRepositoryInterface $filerepo)
    {
        $this->filerepo = $filerepo;
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
        $companyAdmins = User::where('company_admin', '=', 'X')->get();

        echo 'Last day of previous month';

        $dt = new Carbon('last day of previous month');
        $usages =  MonthlyUsageReport::where('report_date', '=', $dt->toDateString())->get();
        echo $dt->toDateString();
        foreach ($usages as $usage) {
            try {
                $company = Company::where('row_id', '=', $usage->company_id)->first();
                $companyAdmins = User::where('company_admin', '=', 'X')
                                       ->where('fk_empresa', '=', $usage->company_id)
                                       ->where('status', '=', 'X')->get();

                $data = array(
                    'company' => $company,
                    'usage'   => $usage,
                    'month_name' => $dt->format('F Y'),
                );

                foreach ($companyAdmins as $companyAdmin) {
                    echo "Emailing ". $companyAdmin->email;
                    Mail::send('emails.datausage.emailer', $data, function ($message) use ($companyAdmin) {
                       $message->from('admin@edoccloud.com', 'eDocCloud');
                       $message->to($companyAdmin->email, $companyAdmin->first_name.' '.$companyAdmin->last_name)->subject('Your data usage report');
                    }
                   );
                }
            } catch (Expception $e) {
                echo $e->getMessage();
            }
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
            //array('period', InputArgument::REQUIRED, ''),
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
            //array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }
}
