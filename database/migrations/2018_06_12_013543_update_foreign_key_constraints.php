<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateForeignKeyConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_courses_registrations', function ($table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->dropForeign(['course_id']);
            $table->foreign('course_id')
                ->references('id')->on('courses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('course_keys', function ($table) {
            $table->dropForeign(['redeemed_user_id']);
            $table->foreign('redeemed_user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->dropForeign(['course_id']);
            $table->foreign('course_id')
                ->references('id')->on('courses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('course_documents', function ($table) {
            $table->dropForeign(['course_id']);
            $table->foreign('course_id')
                ->references('id')->on('courses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('course_modules', function ($table) {
            $table->dropForeign(['course_id']);
            $table->foreign('course_id')
                ->references('id')->on('courses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('course_module_documents', function ($table) {
            $table->dropForeign(['course_module_id']);
            $table->foreign('course_module_id')
                ->references('id')->on('course_modules')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
