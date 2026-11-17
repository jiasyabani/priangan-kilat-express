<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun Admin
        $admin = User::firstOrNew([
            'email' => 'admin@priangankilat.test',
        ]);

        $admin->name = 'Administrator';
        $admin->role = 'admin';
        $admin->password = 'AdminPKE2026!';
        $admin->save();

        // Membuat akun Manajer
        $manajer = User::firstOrNew([
            'email' => 'manajer@priangankilat.test',
        ]);

        $manajer->name = 'Manajer';
        $manajer->role = 'manajer';
        $manajer->password = 'ManajerPKE2026!';
        $manajer->save();
    }
}
