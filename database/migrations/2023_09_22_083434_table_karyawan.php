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
            $table->string('lokasi')->notNull();
            $table->string('alamat')->notNull();
            $table->date('awal_bergabung')->nullable();
            $table->string('gaji_pokok', 25)->notNull();
            $table->string('lama_kontrak')->nullable();
            $table->date('awal_masa_kontrak')->nullable();
            $table->date('akhir_masa_kontrak')->nullable();
            $table->string('foto')->nullable();
            $table->bigInteger('id_card', 10)->notNullable()->autoIncrement(false);
            $table->boolean('is_active')->default(true);
            $table->string('reason')->nullable();
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
