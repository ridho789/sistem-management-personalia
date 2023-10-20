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
        });
    }
}
