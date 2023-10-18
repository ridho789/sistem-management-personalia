<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnForeignTableAllocationRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_alokasi_sisa_cuti', function (Blueprint $table) {
            // table alokasi sisa cuti - karyawan (id_karyawan)
            $table->unsignedBigInteger('id_karyawan')->after('id_alokasi_sisa_cuti');
            $table->foreign('id_karyawan')->references('id_karyawan')->on('tbl_karyawan');

            // table alokasi sisa cuti - data cuti (id_data_cuti)
            $table->unsignedBigInteger('id_data_cuti')->after('id_karyawan');
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
        Schema::table('tbl_alokasi_sisa_cuti', function (Blueprint $table) {
            // table alokasi sisa cuti - karyawan (id_karyawan)
            $table->dropForeign(['id_karyawan']);
            $table->dropColumn('id_karyawan');

            // table alokasi sisa cuti - data cuti (id_data_cuti)
            $table->dropForeign(['id_data_cuti']);
            $table->dropColumn('id_data_cuti');
        });
    }
}
