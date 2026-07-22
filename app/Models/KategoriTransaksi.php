<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToChurch;

class KategoriTransaksi extends Model
{
    use BelongsToChurch;

    protected $table = 'kategori_transaksi';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori',
        'jenis',
        'keterangan',
        'status',
    ];
}
