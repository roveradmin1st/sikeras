<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('churches', function (Blueprint $table) {
            $table->text('alamat')->nullable()->after('nama_gereja');
            $table->string('no_telp', 20)->nullable()->after('alamat');
            $table->string('path_logo')->nullable()->after('no_telp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('churches', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'no_telp', 'path_logo']);
        });
    }
};
