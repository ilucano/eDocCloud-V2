<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePricePlanStorageTiersTable extends Migration {

	public function up()
	{
		Schema::create('price_plan_storage_tiers', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('plan_id')->index();
			$table->decimal('gb_to');
			$table->decimal('price_per_gb');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('price_plan_storage_tiers');
	}
}