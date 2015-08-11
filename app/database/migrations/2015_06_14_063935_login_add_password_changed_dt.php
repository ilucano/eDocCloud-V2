<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LoginAddPasswordChangedDt extends Migration {

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
			$table->dateTime('password_changed_at');
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
			$table->dropColumn('password_changed_at');
		});
	}

}
