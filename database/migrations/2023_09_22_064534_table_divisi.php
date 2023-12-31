<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableDivisi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_divisi', function (Blueprint $table) {
            $table->id('id_divisi');
            $table->string('nama_divisi');
            $table->string('kode_divisi', 5)->nullable();
            $table->integer('jumlah_hari_kerja', 2)->autoIncrement(false)->nullable();
            $table->boolean('is_daily_report')->default(false);
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
        Schema::dropIfExists('tbl_divisi');
    }
}
