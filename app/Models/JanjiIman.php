<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToChurch;

class JanjiIman extends Model
{
    use BelongsToChurch;

    protected $table = 'janji_iman';
    protected $primaryKey = 'id_janji';

    protected $fillable = [
        'id_jemaat',
        'total_janji',
        'tanggal_mulai',
        'status',
    ];

    public function jemaat()
    {
        return $this->belongsTo(Jemaat::class, 'id_jemaat', 'id_jemaat');
    }

    public function pembayaran()
    {
        return $this->hasMany(PembayaranJanji::class, 'id_janji', 'id_janji');
    }

    // Accessor for total paid
    public function getTerbayarAttribute()
    {
        return $this->pembayaran()->sum('jumlah_bayar');
    }

    // Accessor for remaining amount
    public function getSisaAttribute()
    {
        $sisa = $this->total_janji - $this->getTerbayarAttribute();
        return $sisa < 0 ? 0 : $sisa;
    }
}
