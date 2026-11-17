<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kurir extends Model
{
    protected $fillable = [
        'user_id',
        'kode_kurir',
        'no_telepon',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    // Data akun pengguna milik kurir
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Paket yang ditugaskan kepada kurir
    public function pakets(): HasMany
    {
        return $this->hasMany(Paket::class, 'kurir_id');
    }
}
