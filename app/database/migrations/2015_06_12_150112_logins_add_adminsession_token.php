<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LoginsAddAdminsessionToken extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('logins', function(Blueprint $table)
		{
			//
			$table->string('admin_session_token')->default('');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('logins', function(Blueprint $table)
		{
			//
			$table->dropColumn('admin_session_token');
		});
	}

}
