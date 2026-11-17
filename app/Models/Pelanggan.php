<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    
protected $fillable = [
    'nama',
    'no_telepon',
    'alamat',
    'kota',
];


    // Satu pelanggan dapat memiliki banyak paket
    public function pakets(): HasMany
    {
        return $this->hasMany(Paket::class, 'pengirim_id');
    }
}
