<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadFolders extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('upload_folders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('folder_name')->default('');
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('upload_folders');
    }
}
