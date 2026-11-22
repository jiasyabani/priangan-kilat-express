<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class ManagerShippingReportController extends Controller
{
    public function index(Request $request)
    {
        // Periode default: awal bulan sampai hari ini
        $tanggalAwal = $request->input(
            'tanggal_awal',
            now()->startOfMonth()->toDateString()
        );

        $tanggalAkhir = $request->input(
            'tanggal_akhir',
            now()->toDateString()
        );

        // Validasi filter tanggal
        $validated = $request->validate([
            'tanggal_awal' => ['nullable', 'date'],
            'tanggal_akhir' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_awal'
            ],
        ]);

        // Query dasar berdasarkan tanggal masuk
        $query = Paket::query()
            ->whereDate('tanggal_masuk', '>=', $tanggalAwal)
            ->whereDate('tanggal_masuk', '<=', $tanggalAkhir);

        // Total paket masuk
        $totalPaket = (clone $query)->count();

        // Rekap jumlah paket berdasarkan status
        $statusPaket = [
            'Diterima' => (clone $query)->where('status', 'Diterima')->count(),
            'Transit' => (clone $query)->where('status', 'Transit')->count(),
            'Dikirim' => (clone $query)->where('status', 'Dikirim')->count(),
            'Terkirim' => (clone $query)->where('status', 'Terkirim')->count(),
            'Gagal Kirim' => (clone $query)->where('status', 'Gagal Kirim')->count(),
            'Retur' => (clone $query)->where('status', 'Retur')->count(),
        ];

        // Data untuk grafik status pengiriman
        $grafikStatus = collect($statusPaket)
            ->map(function ($total, $status) {
                return [
                    'status' => $status,
                    'total' => $total,
                ];
            })
            ->values();

        // Data tabel pengiriman
        $pakets = (clone $query)
            ->with(['pengirim', 'kurir'])
            ->orderByDesc('tanggal_masuk')
            ->paginate(10)
            ->withQueryString();

        return view('manager.reports.index', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'totalPaket',
            'statusPaket',
            'grafikStatus',
            'pakets'
        ));
    }
}
