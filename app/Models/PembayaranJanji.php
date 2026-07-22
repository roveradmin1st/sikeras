<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToChurch;

class PembayaranJanji extends Model
{
    use BelongsToChurch;

    protected $table = 'pembayaran_janji';
    protected $primaryKey = 'id_bayar';

    protected $fillable = [
        'id_janji',
        'tanggal_bayar',
        'jumlah_bayar',
        'id_transaksi',
        'bukti_bayar',
        'id_user',
    ];

    public function janjiIman()
    {
        return $this->belongsTo(JanjiIman::class, 'id_janji', 'id_janji');
    }

    public function transaksi()
    {
        return $this->belongsTo(TransaksiKas::class, 'id_transaksi', 'id_transaksi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
