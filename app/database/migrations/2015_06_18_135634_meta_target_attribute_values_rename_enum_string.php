<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MetaTargetAttributeValuesRenameEnumString extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('meta_target_attribute_values', function(Blueprint $table)
		{
			//
			Schema::table('meta_target_attribute_values', function(Blueprint $table)
		{
			//
			DB::statement("ALTER TABLE `local_edoccloud`.`meta_target_attribute_values`   
  CHANGE `target_type` `target_type` ENUM('object','file','upload') NOT NULL;");
		});
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('meta_target_attribute_values', function(Blueprint $table)
		{
			//
		});
	}

}
