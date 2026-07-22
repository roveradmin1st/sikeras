<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rayon', function (Blueprint $table) {
            $table->id('id_rayon');
            $table->unsignedBigInteger('id_church');
            $table->string('nama_rayon');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_church')->references('id_church')->on('churches')->onDelete('cascade');
            $table->index('id_church');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rayon');
    }
};
