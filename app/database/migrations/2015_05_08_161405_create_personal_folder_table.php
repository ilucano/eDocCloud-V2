<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonalFolderTable extends Migration {

	public function up()
	{
		Schema::create('personal_folder', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->timestamps();
			$table->string('original_filename', 250)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('personal_folder');
	}
}