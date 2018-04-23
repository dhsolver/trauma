<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birthday');
            $table->string('phone');
            $table->string('address');
            $table->string('unit');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->string('hospital_name');
            $table->string('hospital_level');
            $table->string('hospital_ntdb');
            $table->string('hospital_tqip');
            $table->string('hospital_address1');
            $table->string('hospital_address2');
            $table->string('hospital_address3');
            $table->string('hospital_city');
            $table->string('hospital_state');
            $table->string('hospital_zip');
            $table->string('ssn');
            $table->string('credentials');
            $table->string('state_license');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('birthday');
            $table->dropColumn('phone');
            $table->dropColumn('address');
            $table->dropColumn('unit');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('zipcode');
            $table->dropColumn('hospital_name');
            $table->dropColumn('hospital_level');
            $table->dropColumn('hospital_ntdb');
            $table->dropColumn('hospital_tqip');
            $table->dropColumn('hospital_address1');
            $table->dropColumn('hospital_address2');
            $table->dropColumn('hospital_address3');
            $table->dropColumn('hospital_city');
            $table->dropColumn('hospital_state');
            $table->dropColumn('hospital_zip');
            $table->dropColumn('ssn');
            $table->dropColumn('credentials');
            $table->dropColumn('state_license');
        });
    }
}
