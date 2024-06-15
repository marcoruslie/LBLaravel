<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelanggansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama',255);
            $table->string('alamat',255);
            $table->string('no_telepon',20);
            $table->string('username',255);
            $table->string('password',255);
            $table->string('kota',255);
            $table->enum('jenis_kelamin',['L','P']);
            $table->string('email',255);
            $table->string('foto',255)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelanggan');
    }
}
