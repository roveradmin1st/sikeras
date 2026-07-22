<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayaran_janji', function (Blueprint $table) {
            $table->id('id_bayar');
            $table->unsignedBigInteger('id_church');
            $table->unsignedBigInteger('id_janji');
            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->unsignedBigInteger('id_transaksi')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->foreign('id_church')->references('id_church')->on('churches')->onDelete('cascade');
            $table->foreign('id_janji')->references('id_janji')->on('janji_iman')->onDelete('cascade');
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi_kas')->onDelete('set null');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('restrict');

            $table->index('id_church');
            $table->index('id_janji');
            $table->index('id_transaksi');
            $table->index('id_user');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran_janji');
    }
};
