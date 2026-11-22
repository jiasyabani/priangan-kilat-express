<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $resi = $request->query('resi');
        $paket = null;
        $tidakDitemukan = false;

        if ($resi !== null && trim($resi) !== '') {
            $request->validate([
                'resi' => ['required', 'string', 'max:50'],
            ]);

            $resi = trim($resi);

            $dataPaket = Paket::query()
                ->where('no_resi', $resi)
                ->first();

            if ($dataPaket) {
                // Menyamarkan nomor telepon di server.
                $nomorTelepon = preg_replace(
                    '/\D/',
                    '',
                    $dataPaket->no_telepon_penerima ?? ''
                );

                $teleponTersamar = strlen($nomorTelepon) > 7
                    ? substr($nomorTelepon, 0, 7)
                        . str_repeat('*', strlen($nomorTelepon) - 7)
                    : $nomorTelepon;

                // Mengambil informasi yang boleh dilihat publik.
                $paket = [
                    'no_resi' => $dataPaket->no_resi,
                    'nama_penerima' => $dataPaket->nama_penerima,
                    'no_telepon' => $teleponTersamar,
                    'status' => $dataPaket->status,
                    'tanggal_masuk' => $dataPaket->tanggal_masuk,

                    'riwayat' => $dataPaket->riwayatStatus()
                        ->orderBy('waktu_perubahan')
                        ->get([
                            'status_baru',
                            'catatan',
                            'waktu_perubahan',
                        ]),
                ];
            } else {
                $tidakDitemukan = true;
            }
        }

        return view('tracking.index', compact(
            'resi',
            'paket',
            'tidakDitemukan'
        ));
    }
}
