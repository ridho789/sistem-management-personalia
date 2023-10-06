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
            $table->string('spesifikasi')->nullable();
            $table->string('nopol', 15)->nullable();
            $table->string('merk')->nullable();
            $table->string('tahun')->nullable();
            $table->date('masa_pajak')->nullable();
            $table->date('masa_plat')->nullable();
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
