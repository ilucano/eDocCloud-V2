<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMetaTargetAttributeValuesTable extends Migration {

	public function up()
	{
		Schema::create('meta_target_attribute_values', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('attribute_id')->unsigned();
			$table->integer('target_id')->unsigned();
			$table->enum('target_type', array('object', 'file'));
			$table->string('value', 250);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('meta_target_attribute_values');
	}
}