<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAksesorisLayanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aksesoris_layanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aksesoris_id');
            $table->unsignedBigInteger('reservasi_id');
            $table->integer('jumlah');
            $table->foreign('aksesoris_id')->references('id')->on('aksesoris')->onDelete('cascade');
            $table->foreign('reservasi_id')->references('id')->on('reservasi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aksesoris_layanans');
    }
}
