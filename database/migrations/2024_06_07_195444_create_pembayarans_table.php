<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice',45);
            $table->integer('total');
            $table->string('bukti_pembayaran',45)->nullable(true);
            $table->enum('status',['pending','success','cancel']);
            $table->enum('metode_pembayaran',['cash','transfer']);
            $table->unsignedBigInteger('reservasi_id');
            $table->unsignedBigInteger('admin_id');
            $table->foreign('reservasi_id')->references('id')->on('reservasi')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admin')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
}
