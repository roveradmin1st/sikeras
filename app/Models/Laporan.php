<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToChurch;

class Laporan extends Model
{
    use BelongsToChurch;

    protected $table = 'laporan';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'kode_laporan',
        'jenis_kas',
        'periode_awal',
        'periode_akhir',
        'total_debit',
        'total_kredit',
        'saldo_akhir',
        'id_user',
        'status',
        'tanggal_diajukan',
        'tanggal_disetujui',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
