<?php

class CompanyUuidSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		
		
		$companies = Company::all();
		
		foreach ($companies as $company) {
		    if (! $company->uuid) {
                $uuid = Uuid::generate()->string; 
                $company->uuid = $uuid;
                $company->save();
                echo "Generated uuid ".$uuid. " for ". $company->company_name ."\r\n";
            }

		}

	}

}
