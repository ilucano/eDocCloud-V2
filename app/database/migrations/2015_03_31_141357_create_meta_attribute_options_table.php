<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMetaAttributeOptionsTable extends Migration {

	public function up()
	{
		Schema::create('meta_attribute_options', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('attribute_id')->unsigned();
			$table->timestamps();
			$table->string('options', 250)->default('[]');
		});
	}

	public function down()
	{
		Schema::drop('meta_attribute_options');
	}
}