<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePricePlansTable extends Migration {

	public function up()
	{
		Schema::create('price_plans', function(Blueprint $table) {
			$table->increments('id');
			$table->string('plan_code', 100);
			$table->string('plan_name', 250);
			$table->decimal('base_price', 8,2)->default('0.00');
			$table->smallInteger('free_users')->default('1');
			$table->decimal('free_gb')->default('0.00');
			$table->integer('free_own_scans')->default('0');
			$table->integer('free_plan_scans')->default('0');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('price_plans');
	}
}