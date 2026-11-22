<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class PusatWilayahService
{
    public function cariZona(string $kota): array
    {
        try {
            $response = Http::acceptJson()
                ->withHeaders([
                    'X-API-Key' => config('services.pusat_wilayah.api_key'),
                ])
                ->timeout(5)
                ->get(
                    rtrim(config('services.pusat_wilayah.url'), '/') . '/wilayah',
                    ['kota' => $kota]
                );

            // API tidak tersedia atau API key salah.
            if (!$response->successful()) {
                return [
                    'tersedia' => false,
                    'zona' => null,
                    'pesan' => 'Layanan Pusat Wilayah tidak tersedia.',
                ];
            }

            $data = $response->json();

            // Memastikan respons API memiliki format yang benar.
            if (!is_array($data) || !array_key_exists('zona', $data)) {
                return [
                    'tersedia' => false,
                    'zona' => null,
                    'pesan' => 'Respons layanan tidak valid.',
                ];
            }

            $zona = $data['zona'];

            // Kota tidak dikenal.
            if ($zona === null) {
                return [
                    'tersedia' => true,
                    'zona' => null,
                    'pesan' => 'Kota tidak ditemukan. Silakan tentukan zona secara manual.',
                ];
            }

            // Memastikan zona berupa angka 1, 2, atau 3.
            if (
                !is_numeric($zona) ||
                !in_array((int) $zona, [1, 2, 3], true)
            ) {
                return [
                    'tersedia' => false,
                    'zona' => null,
                    'pesan' => 'Zona dari layanan tidak valid.',
                ];
            }

            return [
                'tersedia' => true,
                'zona' => (int) $zona,
                'pesan' => 'Zona berhasil ditemukan.',
            ];

        } catch (ConnectionException $e) {
            return [
                'tersedia' => false,
                'zona' => null,
                'pesan' => 'Layanan Pusat Wilayah tidak dapat dihubungi.',
            ];
        }
    }
}
