<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnForeignTblPayroll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_gaji', function (Blueprint $table) {
            // table payroll - karyawan
            $table->unsignedBigInteger('id_karyawan')->after('id_gaji');
            $table->foreign('id_karyawan')->references('id_karyawan')->on('tbl_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_gaji', function (Blueprint $table) {
            // table payroll - karyawan
            $table->dropForeign(['id_karyawan']);
            $table->dropColumn('id_karyawan');
        });
    }
}
