<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToChurch;

class TransaksiKas extends Model
{
    use BelongsToChurch;

    protected $table = 'transaksi_kas';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'tanggal',
        'keterangan',
        'debit',
        'kredit',
        'saldo',
        'jenis_kas',
        'id_kategori',
        'id_user',
        'id_jemaat',
        'bukti_transaksi',
        'status',
        'alasan_penolakan',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriTransaksi::class, 'id_kategori', 'id_kategori');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function jemaat()
    {
        return $this->belongsTo(Jemaat::class, 'id_jemaat', 'id_jemaat');
    }

    public function pembayaranJanji()
    {
        return $this->hasOne(PembayaranJanji::class, 'id_transaksi', 'id_transaksi');
    }
}
