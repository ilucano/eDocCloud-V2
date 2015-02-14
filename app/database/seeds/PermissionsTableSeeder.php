<?php

class PermissionsTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		
		$arrayPermissions = array(
							'system_admin_pickup' => 'Workflow > Pickup',
							'system_admin_prepare' => 'Workflow > Preparation',
							'system_admin_scan' => 'Workflow > Scan',
							'system_admin_qa' => 'Workflow > QA',
							'system_admin_ocr' => 'Workflow > OCR',
							'system_admin_reports_allboxes' => 'Reports > All Boxes',
							'system_admin_reports_groupbystatus' => 'Reports > Group By Status',
							'system_admin_user' => 'System Admin > Users',
							'system_admin_company' => 'System Admin > Companies',
							'system_admin_administrator'   => 'System Admin > Administrators'
							
		);
		
		foreach ($arrayPermissions as $key => $text)
		{
			$permission = Permission::firstOrNew(array('name' => $key ,
													   'display_name' => $text)
												);
			$permission->save();
			
		}
		
	}

}
