<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'wajib_ganti_password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
   use HasApiTokens, HasFactory, Notifiable;
   protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'wajib_ganti_password' => 'boolean',
    ];
}

    // Relasi User dengan data Kurir
    public function kurir(): HasOne
    {
        return $this->hasOne(Kurir::class);
    }

    // Relasi User dengan riwayat perubahan status paket
    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(RiwayatStatusPaket::class);
    }

    // Relasi User dengan catatan setoran COD yang diterima Admin
    public function setoranCod(): HasMany
    {
        return $this->hasMany(SetoranCod::class, 'admin_id');
    }
}
