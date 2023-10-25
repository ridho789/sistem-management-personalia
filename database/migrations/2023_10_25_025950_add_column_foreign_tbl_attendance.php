<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnForeignTblAttendance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_attendance', function (Blueprint $table) {
            // table attendance - data leave (id_data_cuti)
            $table->unsignedBigInteger('id_data_cuti')->nullable()->after('id_attendance');
            $table->foreign('id_data_cuti')->references('id_data_cuti')->on('tbl_data_cuti');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_attendance', function (Blueprint $table) {
            // table attendance - data leave (id_data_cuti)
            $table->dropForeign(['id_data_cuti']);
            $table->dropColumn('id_data_cuti');
        });
    }
}
