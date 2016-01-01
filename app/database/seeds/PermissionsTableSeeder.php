<?php

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
                            'system_admin_administrator'   => 'System Admin > Administrators',
                            'admin_user' => 'Company Admin > My Users',
                            'admin_role' => 'Company Admin > User Roles',
                            'admin_filemark' => 'Company Admin > My Filemarks',
                            'admin_metadata' => 'Company Admin > Metadata Setup',
                            'admin_datausage' => 'Company Admin > Data Usage',
                            'user_order' => 'User > Orders',
                            'user_search' => 'User > File Search',
                            'user_file'    => 'User > File Browser',
                            'user_changepassword' => 'User > Change Password',
                            'user_myfolder' => 'User > My Folder',

        );

        foreach ($arrayPermissions as $key => $text) {
            $permission = Permission::firstOrNew(array('name' => $key,
                                                       'display_name' => $text,
                                                       'created_at' => date("Y-m-d H:i:s"),
                                                       'updated_at' => date("Y-m-d H:i:s"))
                                                );
            $permission->save();
        }
    }
}
