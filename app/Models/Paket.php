<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Paket extends Model
{
    protected $fillable = [
        'no_resi',
        'pengirim_id',
        'kurir_id',
        'nama_penerima',
        'no_telepon_penerima',
        'alamat_penerima',
        'kota_tujuan',
        'zona',
        'zona_manual',
        'berat',
        'perlu_verifikasi',
        'layanan',
        'tarif_per_kg',
        'ongkir',
        'nilai_cod',
        'biaya_cod',
        'cod_disetor',
        'status',
        'jumlah_gagal',
        'tanggal_masuk',
    ];

    protected function casts(): array
    {
        return [
            'zona_manual' => 'boolean',
            'perlu_verifikasi' => 'boolean',
            'cod_disetor' => 'boolean',
            'berat' => 'decimal:2',
            'tarif_per_kg' => 'decimal:2',
            'ongkir' => 'decimal:2',
            'nilai_cod' => 'decimal:2',
            'biaya_cod' => 'decimal:2',
            'tanggal_masuk' => 'datetime',
        ];
    }

    // Paket dimiliki oleh seorang pengirim
    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pengirim_id');
    }

    // Paket ditugaskan kepada seorang kurir
    public function kurir(): BelongsTo
    {
        return $this->belongsTo(Kurir::class, 'kurir_id');
    }

    // Paket memiliki banyak riwayat status
    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(RiwayatStatusPaket::class, 'paket_id');
    }

    // Paket memiliki satu catatan setoran COD
    public function setoranCod(): HasOne
    {
        return $this->hasOne(SetoranCod::class, 'paket_id');
    }
}
