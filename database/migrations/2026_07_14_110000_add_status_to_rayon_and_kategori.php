<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add status to rayon table
        if (Schema::hasTable('rayon') && !Schema::hasColumn('rayon', 'status')) {
            Schema::table('rayon', function (Blueprint $table) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('keterangan');
            });
        }

        // Add status to kategori_transaksi table
        if (Schema::hasTable('kategori_transaksi') && !Schema::hasColumn('kategori_transaksi', 'status')) {
            Schema::table('kategori_transaksi', function (Blueprint $table) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('keterangan');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('rayon') && Schema::hasColumn('rayon', 'status')) {
            Schema::table('rayon', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if (Schema::hasTable('kategori_transaksi') && Schema::hasColumn('kategori_transaksi', 'status')) {
            Schema::table('kategori_transaksi', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
