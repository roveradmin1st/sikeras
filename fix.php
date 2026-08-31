<?php
require 'vendor/autoload.php';
\ = require_once 'bootstrap/app.php';
\ = \->make(Illuminate\Contracts\Console\Kernel::class);
\->bootstrap();
\ = \App\Models\PembayaranJanji::whereNull('id_transaksi')->get();
foreach(\ as \) {
  \ = \App\Models\TransaksiKas::create(['tanggal' => \->tanggal_bayar, 'keterangan' => 'Pembayaran Janji Iman (Recovery)', 'debit' => \->jumlah_bayar, 'kredit' => 0, 'jenis_kas' => 'pembangunan', 'id_kategori' => 2, 'status' => 'disetujui']);
  \->id_transaksi = \->id_transaksi;
  \->save();
  echo 'Recovered ' . \->id_bayar . PHP_EOL;
}
