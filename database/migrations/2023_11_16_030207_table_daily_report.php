<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableDailyReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_catatan_harian', function (Blueprint $table) {
            $table->id('id_catatan_harian');
            $table->date('tanggal_catatan_harian')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('dibuat_oleh')->nullable();
            $table->string('diperbaharui_oleh')->nullable();
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
        Schema::dropIfExists('tbl_catatan_harian');
    }
}
