<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnForeignTableAset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_aset', function (Blueprint $table) {
            // table aset - perusahaan (id_perusahaan)
            $table->unsignedBigInteger('id_perusahaan')->after('masa_plat');
            $table->foreign('id_perusahaan')->references('id_perusahaan')->on('tbl_perusahaan');

            // table aset - kategori (id_kategori)
            $table->unsignedBigInteger('id_kategori')->after('id_perusahaan');
            $table->foreign('id_kategori')->references('id_kategori')->on('tbl_kategori');

            // table aset - sub_kategori (id_sub_kategori)
            $table->unsignedBigInteger('id_sub_kategori')->nullable()->after('id_kategori');
            $table->foreign('id_sub_kategori')->references('id_sub_kategori')->on('tbl_sub_kategori');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_aset', function (Blueprint $table) {
            // table aset - perusahaan (id_perusahaan)
            $table->dropForeign(['id_perusahaan']);
            $table->dropColumn('id_perusahaan');

            // table aset - kategori (id_kategori)
            $table->dropForeign(['id_kategori']);
            $table->dropColumn('id_kategori');

            // table aset - sub_kategori (id_sub_kategori)
            $table->dropForeign(['id_sub_kategori']);
            $table->dropColumn('id_sub_kategori');
        });
    }
}