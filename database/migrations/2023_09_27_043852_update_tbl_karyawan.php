<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTblKaryawan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_karyawan', function (Blueprint $table) {
            // Ubah tipe data kolom no_telp menjadi string
            $table->string('no_telp', 25)->change();
            
            // Tambahkan constraint unik ke kolom id_card
            $table->string('id_card', 10)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_karyawan', function (Blueprint $table) {
            // Untuk mengembalikan perubahan jika diperlukan
            $table->bigInteger('no_telp', 15)->change();
            $table->dropUnique('tbl_karyawan_id_card_unique'); // Hapus constraint unik
        });
    }
}
