<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UploadsChangeFolderDataType extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('uploads', function (Blueprint $table) {
            //
            DB::statement('ALTER TABLE `uploads` CHANGE `user_folder` `user_folder` INT(11) DEFAULT 0  NOT NULL;');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('uploads', function (Blueprint $table) {
            //
        });
    }
}
