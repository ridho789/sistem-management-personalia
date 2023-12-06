<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableAllocationRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_alokasi_sisa_cuti', function (Blueprint $table) {
            $table->id('id_alokasi_sisa_cuti');
            $table->string('sisa_cuti')->default('12');
            $table->string('status')->default('-');
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
        Schema::dropIfExists('tbl_alokasi_sisa_cuti');
    }
}
