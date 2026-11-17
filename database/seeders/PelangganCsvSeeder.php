<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PelangganCsvSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/data/pelanggan.csv');

        if (!file_exists($path)) {
            throw new RuntimeException(
                'File pelanggan.csv tidak ditemukan.'
            );
        }

        $file = fopen($path, 'r');

        if ($file === false) {
            throw new RuntimeException(
                'File pelanggan.csv gagal dibuka.'
            );
        }

        try {
            $header = fgetcsv($file);

            if (!$header) {
                throw new RuntimeException(
                    'Header CSV tidak ditemukan.'
                );
            }

            // Membersihkan nama kolom dan BOM.
            $header = array_map(function ($kolom) {
                return strtolower(trim(
                    preg_replace('/^\xEF\xBB\xBF/', '', $kolom)
                ));
            }, $header);

            $kolomWajib = ['nama', 'telepon', 'alamat', 'kota'];

            foreach ($kolomWajib as $kolom) {
                if (!in_array($kolom, $header, true)) {
                    throw new RuntimeException(
                        "Kolom {$kolom} tidak ditemukan dalam CSV."
                    );
                }
            }

            $ditambahkan = 0;
            $dilewati = 0;

            DB::transaction(function () use (
                $file,
                $header,
                &$ditambahkan,
                &$dilewati
            ) {
                while (($baris = fgetcsv($file)) !== false) {

                    if (count($baris) !== count($header)) {
                        $dilewati++;
                        continue;
                    }

                    $data = array_combine($header, $baris);

                    $nama = trim($data['nama']);
                    $telepon = preg_replace(
                        '/\D/',
                        '',
                        trim($data['telepon'])
                    );

                    $alamat = trim($data['alamat']);
                    $kota = trim($data['kota']);

                    if ($nama === '' || $telepon === '') {
                        $dilewati++;
                        continue;
                    }

                    // Normalisasi nomor telepon ke format 62.
                    if (str_starts_with($telepon, '0')) {
                        $telepon = '62' . substr($telepon, 1);
                    } elseif (str_starts_with($telepon, '8')) {
                        $telepon = '62' . $telepon;
                    }

                    // Validasi nomor telepon.
                    if (
                        !str_starts_with($telepon, '62') ||
                        strlen($telepon) < 11 ||
                        strlen($telepon) > 15
                    ) {
                        $dilewati++;
                        continue;
                    }

                    // Merapikan penulisan nama.
                    $nama = mb_convert_case(
                        mb_strtolower($nama, 'UTF-8'),
                        MB_CASE_TITLE,
                        'UTF-8'
                    );

                    // Mencegah nomor telepon yang sama masuk dua kali.
                    $sudahAda = Pelanggan::where(
                        'no_telepon',
                        $telepon
                    )->exists();

                    if ($sudahAda) {
                        $dilewati++;
                        continue;
                    }

                    Pelanggan::create([
                        'nama' => $nama,
                        'no_telepon' => $telepon,
                        'alamat' => $alamat,
                        'kota' => $kota,
                    ]);

                    $ditambahkan++;
                }
            });

            $this->command->info(
                "Pelanggan berhasil ditambahkan: {$ditambahkan}"
            );

            $this->command->info(
                "Data yang dilewati: {$dilewati}"
            );

        } finally {
            fclose($file);
        }
    }
}
