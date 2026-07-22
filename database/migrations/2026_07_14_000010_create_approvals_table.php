<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('approval', function (Blueprint $table) {
            $table->id('id_approval');
            $table->unsignedBigInteger('id_church');
            $table->unsignedBigInteger('id_transaksi');
            $table->unsignedBigInteger('id_pendeta');
            $table->dateTime('tanggal_approve');
            $table->enum('status', ['disetujui', 'ditolak'])->default('disetujui');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_church')->references('id_church')->on('churches')->onDelete('cascade');
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi_kas')->onDelete('cascade');
            $table->foreign('id_pendeta')->references('id_user')->on('users')->onDelete('restrict');

            $table->index('id_church');
            $table->index('id_transaksi');
            $table->index('id_pendeta');
        });
    }

    public function down()
    {
        Schema::dropIfExists('approval');
    }
};
