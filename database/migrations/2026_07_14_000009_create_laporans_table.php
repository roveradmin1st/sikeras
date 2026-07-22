<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->unsignedBigInteger('id_church');
            $table->string('kode_laporan');
            $table->enum('jenis_kas', ['kas_umum', 'pembangunan']);
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->decimal('total_debit', 15, 2)->default(0.00);
            $table->decimal('total_kredit', 15, 2)->default(0.00);
            $table->decimal('saldo_akhir', 15, 2)->default(0.00);
            $table->unsignedBigInteger('id_user');
            $table->enum('status', ['pending', 'disetujui'])->default('pending');
            $table->dateTime('tanggal_diajukan');
            $table->dateTime('tanggal_disetujui')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_church')->references('id_church')->on('churches')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('restrict');

            $table->index('id_church');
            $table->index('id_user');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan');
    }
};
