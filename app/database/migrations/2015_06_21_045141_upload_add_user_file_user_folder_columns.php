<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UploadAddUserFileUserFolderColumns extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('uploads', function (Blueprint $table) {
            //
            //
            $table->string('user_filename', 250)->default('');
            $table->string('user_folder', 250)->default('');
            $table->index('user_filename');
            $table->index('user_folder');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('uploads', function (Blueprint $table) {

            $table->dropColumn('user_filename');
            $table->dropColumn('user_folder');
        });
    }
}
