<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class ManagerRevenueReportController extends Controller
{
    public function index(Request $request)
    {
        // Periode default: awal bulan sampai hari ini
        $validated = $request->validate([
            'tanggal_awal' => ['nullable', 'date'],
            'tanggal_akhir' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_awal'
            ],
        ]);

        $tanggalAwal = $validated['tanggal_awal']
            ?? now()->startOfMonth()->toDateString();

        $tanggalAkhir = $validated['tanggal_akhir']
            ?? now()->toDateString();

        // Query berdasarkan tanggal masuk paket
        $query = Paket::query()
            ->whereDate('tanggal_masuk', '>=', $tanggalAwal)
            ->whereDate('tanggal_masuk', '<=', $tanggalAkhir);

        // Total ongkos kirim
        $totalOngkir = (clone $query)->sum('ongkir');

        // Total biaya COD
        $totalBiayaCod = (clone $query)->sum('biaya_cod');

        // Total pendapatan
        $totalPendapatan = $totalOngkir + $totalBiayaCod;

        // Rekap pendapatan berdasarkan zona
        $pendapatanZona = (clone $query)
            ->selectRaw('zona, SUM(COALESCE(ongkir, 0) + COALESCE(biaya_cod, 0)) as total')
            ->groupBy('zona')
            ->orderBy('zona')
            ->get();

        // Rincian transaksi
        $pakets = (clone $query)
            ->orderByDesc('tanggal_masuk')
            ->paginate(10)
            ->withQueryString();

        return view('manager.revenue-reports.index', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'totalOngkir',
            'totalBiayaCod',
            'totalPendapatan',
            'pendapatanZona',
            'pakets'
        ));
    }
}
