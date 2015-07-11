<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePricePlanOwnScanTiersTable extends Migration {

	public function up()
	{
		Schema::create('price_plan_own_scan_tiers', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('plan_id')->index();
			$table->integer('own_scan_to');
			$table->decimal('price_per_own_scan');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('price_plan_own_scan_tiers');
	}
}