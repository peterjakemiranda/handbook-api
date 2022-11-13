<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('program_description')->nullable()->after('remember_token');
            $table->string('year_level')->nullable()->after('program_description');
            $table->string('section')->nullable()->after('year_level');
            $table->string('gender')->nullable()->after('section');
            $table->string('address')->nullable()->after('gender');
            $table->string('program_code')->nullable()->after('address');
            $table->string('cp_number')->nullable()->after('program_code');
            $table->string('rfid')->nullable()->after('cp_number');
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
            $table->dropColumn('program_description');
            $table->dropColumn('year_level');
            $table->dropColumn('section');
            $table->dropColumn('gender');
            $table->dropColumn('address');
            $table->dropColumn('program_code');
            $table->dropColumn('cp_number');
            $table->dropColumn('rfid');
        });
    }
}
