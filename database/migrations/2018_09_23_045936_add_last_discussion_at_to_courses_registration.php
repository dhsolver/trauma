<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastDiscussionAtToCoursesRegistration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_courses_registrations', function (Blueprint $table) {
            $table->timestamp('last_discussion_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_courses_registrations', function (Blueprint $table) {
            $table->dropColumn('last_discussion_at');
        });
    }
}
