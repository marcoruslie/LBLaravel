<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservasi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_reservasi');
            $table->time('jam_reservasi');
            $table->string('keterangan',45);
            $table->string('status_reservasi',45);
            $table->enum('jenis_aksesoris',['Bawa Sendiri', 'Beli Ditempat']);
            $table->unsignedBigInteger('layanan_id');
            $table->unsignedBigInteger('pelanggan_id');
            $table->unsignedBigInteger('kendaraan_id');
            $table->foreign('layanan_id')->references('id')->on('layanan')->onDelete('cascade');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onDelete('cascade');
            $table->foreign('kendaraan_id')->references('id')->on('kendaraan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservasi');
    }
}
