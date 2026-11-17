<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SetoranCod extends Model
{
    // Menentukan nama tabel sesuai migration.
    protected $table = 'setoran_cods';

    protected $fillable = [
        'paket_id',
        'admin_id',
        'jumlah_setoran',
        'tanggal_setoran',
    ];

    // Mengatur tipe data atribut.
    protected function casts(): array
    {
        return [
            'jumlah_setoran' => 'decimal:2',
            'tanggal_setoran' => 'datetime',
        ];
    }

    // Relasi ke paket.
    public function paket(): BelongsTo
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    // Relasi ke admin yang mencatat setoran.
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
