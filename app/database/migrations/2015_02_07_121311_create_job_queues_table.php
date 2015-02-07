<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobQueuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('job_queues', function(Blueprint $table)
		{
			//
			$table->increments('id');
			$table->string('job_type', 100);
			$table->text('job_data');
			$table->tinyInteger('completed')->default(0);
			$table->mediumText('job_log')->nullable();
			$table->timestamps();
			 
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		 Schema::drop('job_queues');
	}

}
