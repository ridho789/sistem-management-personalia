<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnForeignTblDataCuti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_data_cuti', function (Blueprint $table) {
            // table data cuti - karyawan (id_karyawan)
            $table->unsignedBigInteger('id_karyawan')->after('id_data_cuti');
            $table->foreign('id_karyawan')->references('id_karyawan')->on('tbl_karyawan');

            // table data cuti - karyawan (id_karyawan - penanggung jawab)
            $table->unsignedBigInteger('id_penangung_jawab')->after('id_karyawan');
            $table->foreign('id_penangung_jawab')->references('id_karyawan')->on('tbl_karyawan');

            // table data cuti - tipe cuti (type leave)
            $table->unsignedBigInteger('id_tipe_cuti')->after('deskripsi');
            $table->foreign('id_tipe_cuti')->references('id_tipe_cuti')->on('tbl_tipe_cuti');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_data_cuti', function (Blueprint $table) {
            // table data cuti - karyawan (id_karyawan)
            $table->dropForeign(['id_karyawan']);
            $table->dropColumn('id_karyawan');

            // table data cuti - karyawan (id_karyawan - penanggung jawab)
            $table->dropForeign(['id_penangung_jawab']);
            $table->dropColumn('id_penangung_jawab');

            // table data cuti - tipe cuti (type leave)
            $table->dropForeign(['id_tipe_cuti']);
            $table->dropColumn('id_tipe_cuti');
        });
    }
}
