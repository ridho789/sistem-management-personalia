<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumnForeignKaryawan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_karyawan', function (Blueprint $table) {
            // table karyawan - jabatan (id_jabatan)
            $table->unsignedBigInteger('id_jabatan')->after('id_card');
            $table->foreign('id_jabatan')->references('id_jabatan')->on('tbl_jabatan');

            // table karyawan - divisi (id_divisi)
            $table->unsignedBigInteger('id_divisi')->after('id_jabatan');
            $table->foreign('id_divisi')->references('id_divisi')->on('tbl_divisi');

            // table karyawan - perusahaan (id_perusahaan)
            $table->unsignedBigInteger('id_perusahaan')->after('id_divisi');
            $table->foreign('id_perusahaan')->references('id_perusahaan')->on('tbl_perusahaan');

            // table karyawan - status karyawan (id_status_kary)
            $table->unsignedBigInteger('id_status')->after('id_perusahaan');
            $table->foreign('id_status')->references('id_status')->on('tbl_status_kary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_karyawan', function (Blueprint $table) {
            // table karyawan - jabatan (id_jabatan)
            $table->dropForeign(['id_jabatan']);
            $table->dropColumn('id_jabatan');

            // table karyawan - divisi (id_divisi)
            $table->dropForeign(['id_divisi']);
            $table->dropColumn('id_divisi');

            // table karyawan - perusahaan (id_perusahaan)
            $table->dropForeign(['id_perusahaan']);
            $table->dropColumn('id_perusahaan');

            // table karyawan - status karyawan (id_status)
            $table->dropForeign(['id_status']);
            $table->dropColumn('id_status');
        });
    }
}
