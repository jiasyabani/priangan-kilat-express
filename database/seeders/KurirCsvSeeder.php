<?php

namespace Database\Seeders;

use App\Models\Kurir;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class KurirCsvSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/data/kurir.csv');

        if (!file_exists($path)) {
            throw new RuntimeException('File kurir.csv tidak ditemukan.');
        }

        $file = fopen($path, 'r');

        if ($file === false) {
            throw new RuntimeException('File kurir.csv gagal dibuka.');
        }

        try {
            $header = fgetcsv($file);

            if ($header === false) {
                throw new RuntimeException('File kurir.csv kosong.');
            }

            $header = array_map(function ($kolom) {
                return strtolower(trim(
                    preg_replace('/^\xEF\xBB\xBF/', '', $kolom)
                ));
            }, $header);

            $kolomWajib = ['kode', 'nama', 'telepon'];

            if (array_diff($kolomWajib, $header)) {
                throw new RuntimeException(
                    'Kolom CSV harus berisi: kode, nama, telepon.'
                );
            }

            $ditambahkan = 0;
            $dilewati = 0;
            $kredensial = [];

            DB::transaction(function () use (
                $file,
                $header,
                &$ditambahkan,
                &$dilewati,
                &$kredensial
            ) {
                while (($baris = fgetcsv($file)) !== false) {
                    if (count($baris) !== count($header)) {
                        $dilewati++;
                        continue;
                    }

                    $data = array_combine($header, $baris);

                    $kode = strtoupper(trim($data['kode']));
                    $nama = trim($data['nama']);
                    $telepon = preg_replace(
                        '/\D/',
                        '',
                        trim($data['telepon'])
                    );

                    if ($kode === '' || $nama === '' || $telepon === '') {
                        $dilewati++;
                        continue;
                    }

                    if (str_starts_with($telepon, '0')) {
                        $telepon = '62' . substr($telepon, 1);
                    } elseif (str_starts_with($telepon, '8')) {
                        $telepon = '62' . $telepon;
                    }

                    if (
                        !str_starts_with($telepon, '62') ||
                        strlen($telepon) < 11 ||
                        strlen($telepon) > 15
                    ) {
                        $dilewati++;
                        continue;
                    }

                    if (Kurir::where('kode_kurir', $kode)->exists()) {
                        $dilewati++;
                        continue;
                    }

                    $email = strtolower($kode) . '@kurir.priangankilat.local';

                    if (User::where('email', $email)->exists()) {
                        $dilewati++;
                        continue;
                    }

                    // Password sementara unik untuk setiap kurir.
                    $passwordSementara = Str::random(12);

                    $user = User::create([
                        'name' => $nama,
                        'email' => $email,
                        'password' => $passwordSementara,
                        'role' => 'kurir',
                        'wajib_ganti_password' => true,
                    ]);

                    Kurir::create([
                        'user_id' => $user->id,
                        'kode_kurir' => $kode,
                        'no_telepon' => $telepon,
                        'aktif' => true,
                    ]);

                    $kredensial[] = [
                        'kode' => $kode,
                        'password' => $passwordSementara,
                    ];

                    $ditambahkan++;
                }
            });

            echo "\nKurir berhasil ditambahkan: {$ditambahkan}\n";
            echo "Data yang dilewati: {$dilewati}\n";

            if (count($kredensial) > 0) {
                echo "\nPASSWORD SEMENTARA KURIR:\n";

                foreach ($kredensial as $akun) {
                    echo $akun['kode'] . ' : ' . $akun['password'] . "\n";
                }

                echo "\nSimpan password ini dengan aman. "
                    . "Password hanya ditampilkan saat akun dibuat.\n";
            }
        } finally {
            fclose($file);
        }
    }
}
