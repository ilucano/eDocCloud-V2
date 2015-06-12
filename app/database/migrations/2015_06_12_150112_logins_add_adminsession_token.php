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
			$table->dateTime('admin_session_expired_at');

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
			$table->dropColumn('admin_session_expired_at');
		});
	}

}
