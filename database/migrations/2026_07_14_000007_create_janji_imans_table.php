<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('janji_iman', function (Blueprint $table) {
            $table->id('id_janji');
            $table->unsignedBigInteger('id_church');
            $table->unsignedBigInteger('id_jemaat');
            $table->decimal('total_janji', 15, 2);
            $table->date('tanggal_mulai');
            $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->timestamps();

            $table->foreign('id_church')->references('id_church')->on('churches')->onDelete('cascade');
            $table->foreign('id_jemaat')->references('id_jemaat')->on('jemaat')->onDelete('cascade');

            $table->index('id_church');
            $table->index('id_jemaat');
        });
    }

    public function down()
    {
        Schema::dropIfExists('janji_iman');
    }
};
