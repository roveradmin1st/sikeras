<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToChurch;

class Approval extends Model
{
    use BelongsToChurch;

    protected $table = 'approval';
    protected $primaryKey = 'id_approval';

    protected $fillable = [
        'id_transaksi',
        'id_pendeta',
        'tanggal_approve',
        'status',
        'catatan',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiKas::class, 'id_transaksi', 'id_transaksi');
    }

    public function pendeta()
    {
        return $this->belongsTo(User::class, 'id_pendeta', 'id_user');
    }
}
