<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToChurch;

class Rayon extends Model
{
    use BelongsToChurch;

    protected $table = 'rayon';
    protected $primaryKey = 'id_rayon';

    protected $fillable = [
        'nama_rayon',
        'keterangan',
        'status',
    ];
}
