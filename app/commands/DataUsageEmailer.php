<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Repositories\File\FileRepositoryInterface;

class DataUsageEmailer extends Command {

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
	 *
	 * @return void
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

	 

		foreach ($companyAdmins as $companyAdmin) {
            try {
            	$company = Company::where('row_id', '=', $companyAdmin->fk_empresa)->first();

		        $company->todate_data_usage = $this->filerepo->getDataUsage($company->row_id);

		        $company->monthly_data_usage = $this->filerepo->getMonthlyDataUsage($company->row_id);

		        $company->monthly_number_of_files = $this->filerepo->getMonthlyNumberOfFiles($company->row_id);

		        //$htmlContent = View::make('emails.datausage.emailer')->with('company', $company);
		        $data = array(
					'company'=> $company
				);

				Mail::send('emails.datausage.emailer', $data, function($message) use ($companyAdmin)
				{
				   $message->from('admin@edoccloud.com', 'eDocCloud');
				   $message->to($companyAdmin->email, $companyAdmin->first_name . ' '. $companyAdmin->last_name)->subject('Your data usage report');
				}
				);
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
