<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAppDomainDefaultValueCompaniesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('companies', function(Blueprint $table)
        {
            //
            $table->dropColumn('app_domain');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('companies', function(Blueprint $table)
        {
            //
            $table->string('app_domain')->default('http://dev.edoccloud.com');
        });
	}

}
