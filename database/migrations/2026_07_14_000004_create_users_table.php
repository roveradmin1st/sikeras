<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->unsignedBigInteger('id_church');
            $table->string('nama');
            $table->string('username'); // unique within tenant check will be handled in app logic or composite unique index
            $table->string('password');
            $table->enum('role', ['admin', 'bendahara_kas', 'bendahara_pembangunan', 'pendeta', 'jemaat']);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->unsignedBigInteger('id_jemaat')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_church')->references('id_church')->on('churches')->onDelete('cascade');
            $table->foreign('id_jemaat')->references('id_jemaat')->on('jemaat')->onDelete('set null');
            
            $table->unique(['id_church', 'username']); // composite unique key
            $table->index('id_church');
            $table->index('id_jemaat');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
