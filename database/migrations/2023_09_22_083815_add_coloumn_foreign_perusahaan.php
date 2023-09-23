<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumnForeignPerusahaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_perusahaan', function (Blueprint $table) {
            // table karyawan - jabatan (id_jabatan)
            $table->unsignedBigInteger('id_karyawan')->after('nama_alamat');
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
        Schema::table('tbl_perusahaan', function (Blueprint $table) {
            $table->dropForeign(['id_karyawan']);
            $table->dropColumn('id_karyawan');
        });
    }
}
