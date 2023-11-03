<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TablePayroll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_gaji', function (Blueprint $table) {
            $table->id('id_gaji');
            $table->string('periode_gaji');
            $table->string('gaji_pokok');
            $table->string('tunjangan_jabatan');
            $table->string('potongan');
            $table->string('total_gaji');
            $table->string('jumlah_hari_kerja');
            $table->string('jumlah_hari_sakit')->nullable();
            $table->string('jumlah_hari_tidak_masuk')->nullable();
            $table->string('jumlah_hari_cuti_resmi')->nullable();
            $table->string('jumlah_hari_telat')->nullable();
            $table->string('bulan');
            $table->string('tahun');
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_gaji');
    }
}
