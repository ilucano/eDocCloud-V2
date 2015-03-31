<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMetaAttributesTable extends Migration {

	public function up()
	{
		Schema::create('meta_attributes', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('fk_empresa')->unsigned();
			$table->enum('type', array('string', 'float', 'integer', 'boolean', 'radio', 'select', 'checkbox', 'multiselect', 'date'));
			$table->string('name', 100);
			$table->tinyInteger('required')->unsigned()->default('0');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('meta_attributes');
	}
}