<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori_transaksi', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->unsignedBigInteger('id_church');
            $table->string('nama_kategori');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_church')->references('id_church')->on('churches')->onDelete('cascade');
            $table->index('id_church');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_transaksi');
    }
};
