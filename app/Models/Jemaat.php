<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToChurch;

class Jemaat extends Model
{
    use BelongsToChurch;

    protected $table = 'jemaat';
    protected $primaryKey = 'id_jemaat';

    protected $fillable = [
        'nama_jemaat',
        'alamat',
        'no_hp',
        'id_rayon',
        'status',
    ];

    public function rayon()
    {
        return $this->belongsTo(Rayon::class, 'id_rayon', 'id_rayon');
    }
}
