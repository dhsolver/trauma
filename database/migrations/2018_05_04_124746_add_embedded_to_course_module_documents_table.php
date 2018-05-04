<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmbeddedToCourseModuleDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_module_documents', function (Blueprint $table) {
            $table->boolean('embedded')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_module_documents', function (Blueprint $table) {
            $table->dropColumn('embedded');
        });
    }
}
