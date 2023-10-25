<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableAttendance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_attendance', function (Blueprint $table) {
            $table->id('id_attendance');
            $table->string('employee');
            $table->string('id_card');
            $table->string('information')->nullable();
            $table->date('attendance_date');
            $table->time('sign_in')->nullable();
            $table->time('sign_out')->nullable();
            $table->time('sign_in_late')->nullable();
            $table->time('sign_out_late')->nullable();
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
        Schema::dropIfExists('tbl_attendance');
    }
}
