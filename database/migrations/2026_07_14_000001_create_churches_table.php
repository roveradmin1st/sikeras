<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('churches', function (Blueprint $table) {
            $table->id('id_church');
            $table->string('nama_gereja');
            $table->string('slug')->unique();
            $table->string('alamat');
            $table->string('kontak');
            $table->enum('status', ['aktif', 'nonaktif', 'trial'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('churches');
    }
};
