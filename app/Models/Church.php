<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Church extends Model
{
    protected $table = 'churches';
    protected $primaryKey = 'id_church';

    protected $fillable = [
        'nama_gereja',
        'slug',
        'alamat',
        'kontak',
        'status',
    ];
}
