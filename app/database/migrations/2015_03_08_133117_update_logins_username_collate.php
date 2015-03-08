<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLoginsUsernameCollate extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		DB::statement("ALTER TABLE `logins`
				CHANGE `username` `username` varchar(32) COLLATE 'utf8_general_ci' NOT NULL AFTER `id`,
				COMMENT='';");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
			DB::statement("ALTER TABLE `logins`
					CHANGE `username` `username` varchar(32) COLLATE 'utf8_unicode_ci' NOT NULL AFTER `id`,
					COMMENT='';");
	}

}
