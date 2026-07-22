<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksi_kas', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_church');
            $table->date('tanggal');
            $table->string('keterangan');
            $table->decimal('debit', 15, 2)->default(0.00);
            $table->decimal('kredit', 15, 2)->default(0.00);
            $table->decimal('saldo', 15, 2)->default(0.00);
            $table->enum('jenis_kas', ['kas_umum', 'pembangunan', 'rayon']);
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_jemaat')->nullable();
            $table->string('bukti_transaksi')->nullable();
            $table->enum('status', ['pending', 'disetujui'])->default('pending');
            $table->timestamps();

            $table->foreign('id_church')->references('id_church')->on('churches')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_transaksi')->onDelete('restrict');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('restrict');
            $table->foreign('id_jemaat')->references('id_jemaat')->on('jemaat')->onDelete('set null');

            $table->index('id_church');
            $table->index('id_kategori');
            $table->index('id_user');
            $table->index('id_jemaat');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_kas');
    }
};
