<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Church;
use App\Models\Rayon;
use App\Models\Jemaat;
use App\Models\User;
use App\Models\KategoriTransaksi;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Default Church (Tenant)
        $church = Church::create([
            'nama_gereja' => 'GPdI Mahanaim',
            'slug' => 'mahanaim',
            'alamat' => 'Sadaperarih',
            'kontak' => '08123456789',
            'status' => 'aktif',
        ]);

        // Set tenant context for seeder so trait scopes and automatic column assignments work
        app(\App\Services\TenantManager::class)->setTenant($church);

        // 2. Create Default Rayon
        $rayon = Rayon::create([
            'nama_rayon' => 'Rayon Sadaperarih',
            'keterangan' => 'Wilayah pusat Sadaperarih',
        ]);

        // 3. Create Default Kategori Transaksi
        $kategoriList = [
            ['nama_kategori' => 'Persembahan Mingguan', 'jenis' => 'pemasukan', 'keterangan' => 'Persembahan ibadah raya minggu'],
            ['nama_kategori' => 'Persepuluhan', 'jenis' => 'pemasukan', 'keterangan' => 'Persembahan persepuluhan jemaat'],
            ['nama_kategori' => 'Donasi Umum', 'jenis' => 'pemasukan', 'keterangan' => 'Donasi sukarela umum jemaat'],
            ['nama_kategori' => 'Kas Pembangunan', 'jenis' => 'pemasukan', 'keterangan' => 'Pemasukan khusus dana pembangunan / janji iman'],
            ['nama_kategori' => 'Ucapan Syukur', 'jenis' => 'pemasukan', 'keterangan' => 'Persembahan ucapan syukur jemaat'],
            ['nama_kategori' => 'Biaya Operasional', 'jenis' => 'pengeluaran', 'keterangan' => 'Pengeluaran listrik, air, dan operasional gereja'],
            ['nama_kategori' => 'Biaya Pelayanan', 'jenis' => 'pengeluaran', 'keterangan' => 'Biaya pelayanan firman, bantuan jemaat sakit'],
            ['nama_kategori' => 'Biaya Pembangunan', 'jenis' => 'pengeluaran', 'keterangan' => 'Pengeluaran pembelian bahan bangunan atau upah tukang'],
            ['nama_kategori' => 'Biaya Inventaris', 'jenis' => 'pengeluaran', 'keterangan' => 'Pembelian kursi, sound system, dll'],
        ];

        foreach ($kategoriList as $kat) {
            KategoriTransaksi::create($kat);
        }

        // 4. Create Default Users (Admin, Pendeta, Bendahara Kas, Bendahara Pembangunan)
        User::create([
            'nama' => 'Admin Mahanaim',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        User::create([
            'nama' => 'Pdt. Elma Br Sihombing',
            'username' => 'pendeta',
            'password' => Hash::make('password123'),
            'role' => 'pendeta',
            'status' => 'aktif',
        ]);

        User::create([
            'nama' => 'Bendahara Kas',
            'username' => 'bendahara_kas',
            'password' => Hash::make('password123'),
            'role' => 'bendahara_kas',
            'status' => 'aktif',
        ]);

        User::create([
            'nama' => 'Bendahara Pembangunan',
            'username' => 'bendahara_pemb',
            'password' => Hash::make('password123'),
            'role' => 'bendahara_pembangunan',
            'status' => 'aktif',
        ]);

        // 5. Create a Dummy Jemaat & Jemaat User for testing Jemaat role login
        $jemaat = Jemaat::create([
            'nama_jemaat' => 'Jemaat Bantal Surbakti',
            'alamat' => 'Sadaperarih',
            'no_hp' => '081398765432',
            'id_rayon' => $rayon->id_rayon,
            'status' => 'aktif',
        ]);

        User::create([
            'nama' => 'Jemaat Bantal Surbakti',
            'username' => 'jemaat',
            'password' => Hash::make('password123'),
            'role' => 'jemaat',
            'status' => 'aktif',
            'id_jemaat' => $jemaat->id_jemaat,
        ]);
    }
}
