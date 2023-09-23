<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableKaryawan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_karyawan', function (Blueprint $table) {
            $table->id('id_karyawan');
            $table->string('nama_karyawan')->notNull();
            $table->bigInteger('nik', 16)->notNullable()->autoIncrement(false);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin')->notNull();
            $table->bigInteger('no_telp', 15)->notNullable()->autoIncrement(false);
            $table->string('alamat')->notNull();
            $table->string('foto')->notNull();
            $table->bigInteger('id_card', 10)->notNullable()->autoIncrement(false);
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
        Schema::dropIfExists('tbl_karyawan');
    }
}
