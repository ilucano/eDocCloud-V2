<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMonthlyUsageReports extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('monthly_usage_reports', function(Blueprint $table)
		{
			//
			$table->integer('daily_new_users')->default(0)->after('report_date');
			$table->decimal('daily_storage_usage')->default(0.00)->after('user_tiers');
			$table->integer('daily_own_scans')->default(0)->after('storage_tiers');
			$table->integer('daily_plan_scans')->default(0)->after('own_scan_tiers');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('monthly_usage_reports', function(Blueprint $table)
		{
			//
			$table->dropColumn('daily_new_users');
			$table->dropColumn('daily_storage_usage');
			$table->dropColumn('daily_own_scans');
			$table->dropColumn('daily_plan_scans');
		});
	}

}
