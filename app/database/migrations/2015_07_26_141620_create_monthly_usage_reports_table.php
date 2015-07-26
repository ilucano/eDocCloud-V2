<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMonthlyUsageReportsTable extends Migration {

	public function up()
	{
		Schema::create('monthly_usage_reports', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('company_id')->index();
			$table->date('report_date')->index();
			$table->smallInteger('current_number_of_users');
			$table->decimal('user_charges')->default('0.00');
			$table->string('user_tiers', 250);
			$table->decimal('current_storage_usage');
			$table->decimal('storage_charges')->index();
			$table->string('storage_tiers', 250);
			$table->integer('current_number_of_own_scans')->index();
			$table->decimal('own_scan_charges')->index();
			$table->string('own_scan_tiers', 250);
			$table->integer('current_number_of_plan_scans')->index();
			$table->decimal('plan_scan_charges')->index();
			$table->string('plan_scan_tiers', 250);
			$table->decimal('current_charges', 10,2)->index();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('monthly_usage_reports');
	}
}