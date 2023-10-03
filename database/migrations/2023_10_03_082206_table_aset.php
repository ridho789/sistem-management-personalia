<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableAset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_aset', function (Blueprint $table) {
            $table->id('id_aset');
            $table->string('nama_aset');
            $table->string('lokasi');
            $table->string('spesifikasi');
            $table->string('nopol', 15);
            $table->string('merk');
            $table->string('tahun');
            $table->date('masa_pajak');
            $table->date('masa_plat');
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
        Schema::dropIfExists('tbl_aset');
    }
}
