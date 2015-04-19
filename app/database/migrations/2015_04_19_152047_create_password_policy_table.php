<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePasswordPolicyTable extends Migration {

	public function up()
	{
		Schema::create('password_policy', function(Blueprint $table) {
			$table->increments('id');
			$table->tinyInteger('uppercase')->default('0');
			$table->tinyInteger('lowercase')->default('1');
			$table->tinyInteger('min_length')->default('6');
			$table->tinyInteger('special_character')->default('0');
			$table->smallInteger('expire_days')->default('30');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('password_policy');
	}
}