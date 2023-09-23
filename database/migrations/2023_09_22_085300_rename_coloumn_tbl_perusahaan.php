<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColoumnTblPerusahaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_perusahaan', function (Blueprint $table) {
            $table->renameColumn('id_karyawan', 'penanggung_jawab');
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
            $table->renameColumn('id_karyawan', 'penanggung_jawab');
        });
    }
}
