<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jemaat', function (Blueprint $table) {
            $table->id('id_jemaat');
            $table->unsignedBigInteger('id_church');
            $table->string('nama_jemaat');
            $table->string('alamat');
            $table->string('no_hp');
            $table->unsignedBigInteger('id_rayon');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();

            $table->foreign('id_church')->references('id_church')->on('churches')->onDelete('cascade');
            $table->foreign('id_rayon')->references('id_rayon')->on('rayon')->onDelete('cascade');
            
            $table->index('id_church');
            $table->index('id_rayon');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jemaat');
    }
};
