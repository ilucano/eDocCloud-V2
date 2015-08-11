<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PricePlansCreateIsTemplateColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('price_plans', function(Blueprint $table)
		{
			//
			$table->tinyInteger('is_template')->default(1)->after('free_plan_scans');
			$table->integer('company_id')->nullable()->after('is_template');
			$table->integer('copy_from_template')->nullable()->after('company_id');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('price_plans', function(Blueprint $table)
		{
			//
			$table->dropColumn('is_template');
			$table->dropColumn('company_id');
			$table->dropColumn('copy_from_template');
		});
	}

}
