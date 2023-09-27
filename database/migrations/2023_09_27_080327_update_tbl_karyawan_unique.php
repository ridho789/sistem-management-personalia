<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTblKaryawanUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_karyawan', function (Blueprint $table) {
            // Tambahkan constraint unik ke kolom nik
            $table->string('nik', 20)->unique()->change();
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
            $table->dropUnique('tbl_karyawan_nik_unique');
        });
    }
}
