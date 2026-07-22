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
            $table->text('alamat')->nullable();
            $table->string('kontak');
            $table->string('no_telp', 20)->nullable();
            $table->string('path_logo')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'trial'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('churches');
    }
};
