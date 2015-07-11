<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePricePlanUserTiersTable extends Migration {

	public function up()
	{
		Schema::create('price_plan_user_tiers', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('plan_id')->index();
			$table->smallInteger('user_to');
			$table->decimal('price_per_user');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('price_plan_user_tiers');
	}
}