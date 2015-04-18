<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('logins', function(Blueprint $table)
		{
			$table->increments('id');
  
			$table->string('username', 32);
			$table->string('password', 64);

			// required for Laravel 4.1.26
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
		});
		   
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('logins');
	}

}
