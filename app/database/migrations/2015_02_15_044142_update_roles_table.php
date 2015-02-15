<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		 Schema::table('roles', function (Blueprint $table) {
            $table->integer('fk_empresa');
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
		 Schema::table('roles', function (Blueprint $table) {
			$table->dropColumn('fk_empresa');
        });
	}

}
