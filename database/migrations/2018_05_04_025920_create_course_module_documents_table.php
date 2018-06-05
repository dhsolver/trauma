<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseModuleDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_module_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_module_id')->unsigned();
            $table->enum('type', ['url', 'file'])->default('url');
            $table->string('url')->nullable();
            $table->string('file')->nullable();
            $table->foreign('course_module_id')->references('id')->on('course_modules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('course_module_documents');
    }
}
