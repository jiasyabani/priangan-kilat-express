<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    protected $fillable = [
        'zona',
        'nama_zona',
        'tarif_per_kg',
    ];

    protected function casts(): array
    {
        return [
            'zona' => 'integer',
            'tarif_per_kg' => 'decimal:2',
        ];
    }
}
