<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBasePriceInMonthlyUsageReport extends Migration {

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
			$table->integer('plan_id')->after('report_date');
			$table->text('plan_json')->after('plan_id')->nullable();
			$table->decimal('base_price')->after('plan_json');
			$table->dropColumn('user_tiers');
			$table->dropColumn('storage_tiers');
			$table->dropColumn('own_scan_tiers');
			$table->dropColumn('plan_scan_tiers');
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
			$table->dropColumn('plan_id');
			$table->dropColumn('base_price');
			$table->dropColumn('plan_json');
			$table->string('user_tiers', 250);
			$table->string('storage_tiers', 250);
			$table->string('own_scan_tiers', 250);
			$table->string('plan_scan_tiers', 250);

		});
	}

}
