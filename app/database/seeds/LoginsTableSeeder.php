<?php

class LoginsTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		
		DB::table('logins')->delete();
		//few sample users
		Login::create(array(
			'username' => 'admin',
			'password' => Hash::make('test123'),
		));
		
		Login::create(array(
			'username' => 'seast',
			'password' => Hash::make('test123'),
		));
			
		Login::create(array(
			'username' => 'lisa',
			'password' => Hash::make('test123'),
		));
		
	    Login::create(array(
			'username' => 'cecilia',
			'password' => Hash::make('test123'),
		));
	}

}
