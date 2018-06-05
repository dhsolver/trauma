<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_keys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->unsigned();
            $table->string('key');
            $table->boolean('redeemed')->default(false);
            $table->timestamp('redeemed_at');
            $table->integer('redeemed_user_id')->unsigned()->nullable();

            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('redeemed_user_id')->references('id')->on('users');
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
        Schema::drop('course_keys');
    }
}
