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
        Schema::table('transaksi_kas', function (Blueprint $table) {
            $table->string('alasan_penolakan')->nullable()->after('status');
        });

        // Modify the enum using DB::statement
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE transaksi_kas MODIFY COLUMN status ENUM('pending', 'disetujui', 'ditolak') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // It's generally risky to drop an enum value if there are rows using it, but for down() we'll attempt it.
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE transaksi_kas MODIFY COLUMN status ENUM('pending', 'disetujui') DEFAULT 'pending'");
        
        Schema::table('transaksi_kas', function (Blueprint $table) {
            $table->dropColumn('alasan_penolakan');
        });
    }
};
