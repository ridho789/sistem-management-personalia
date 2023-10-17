<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TabelDataCuti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_data_cuti', function (Blueprint $table) {
            $table->id('id_data_cuti');
            $table->string('deskripsi');
            $table->dateTime('mulai_cuti');
            $table->dateTime('selesai_cuti');
            $table->string('durasi_cuti');
            $table->string('file');
            $table->string('file_approved')->nullable();
            $table->string('status_cuti');
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
        Schema::dropIfExists('tbl_data_cuti');
    }
}
