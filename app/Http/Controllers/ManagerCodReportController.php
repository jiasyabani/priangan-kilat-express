<?php

namespace App\Http\Controllers;

use App\Models\Paket;

class ManagerCodReportController extends Controller
{
    public function index()
    {
        // Ambil paket COD yang sudah berhasil terkirim
       $paketsCod = Paket::with('kurir.user')
            ->where('status', 'Terkirim')
            ->where('nilai_cod', '>', 0)
            ->get();

        // Total COD yang berhasil dikirim
        $totalCodTerkirim = $paketsCod->sum('nilai_cod');

        // Total COD yang sudah disetor
        $totalCodDisetor = $paketsCod
            ->where('cod_disetor', true)
            ->sum('nilai_cod');

        // Total COD yang belum disetor
        $totalCodBelumDisetor = $paketsCod
            ->where('cod_disetor', false)
            ->sum('nilai_cod');

        // Jumlah paket COD
        $jumlahPaketCod = $paketsCod->count();

        // Rekap berdasarkan kurir
        $rekapKurir = $paketsCod
            ->groupBy('kurir_id')
            ->map(function ($items, $kurirId) {
                return [
                    'kurir' => $items->first()->kurir,
                    'jumlah_paket' => $items->count(),
                    'total_cod' => $items->sum('nilai_cod'),
                    'cod_disetor' => $items
                        ->where('cod_disetor', true)
                        ->sum('nilai_cod'),
                    'cod_belum_disetor' => $items
                        ->where('cod_disetor', false)
                        ->sum('nilai_cod'),
                ];
            })
            ->values();

        return view('manager.cod-reports.index', compact(
            'totalCodTerkirim',
            'totalCodDisetor',
            'totalCodBelumDisetor',
            'jumlahPaketCod',
            'rekapKurir'
        ));
    }
}
