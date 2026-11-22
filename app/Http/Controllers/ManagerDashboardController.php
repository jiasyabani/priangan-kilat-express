<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Support\Facades\DB;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $totalPaket = Paket::count();

        $paketTerkirim = Paket::where('status', 'Terkirim')->count();

        $paketTransit = Paket::where('status', 'Transit')->count();

        $paketGagal = Paket::where('status', 'Gagal Kirim')->count();

        $totalPendapatan = Paket::sum(
            DB::raw('COALESCE(ongkir, 0) + COALESCE(biaya_cod, 0)')
        );

        $codBelumDisetor = Paket::where('status', 'Terkirim')
            ->where('cod_disetor', false)
            ->sum('nilai_cod');

        $grafikPengiriman = Paket::select(
                DB::raw('DATE(tanggal_masuk) as tanggal'),
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('tanggal_masuk')
            ->whereDate('tanggal_masuk', '>=', now()->subDays(6)->toDateString())
            ->groupBy(DB::raw('DATE(tanggal_masuk)'))
            ->orderBy('tanggal')
            ->get();

        $grafikPendapatan = Paket::select(
                'zona',
                DB::raw('SUM(COALESCE(ongkir, 0) + COALESCE(biaya_cod, 0)) as total')
            )
            ->groupBy('zona')
            ->orderBy('zona')
            ->get();

        $distribusiZona = Paket::select(
                'zona',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('zona')
            ->orderBy('zona')
            ->get();

        return view('manager.dashboard', compact(
            'totalPaket',
            'paketTerkirim',
            'paketTransit',
            'paketGagal',
            'totalPendapatan',
            'codBelumDisetor',
            'grafikPengiriman',
            'grafikPendapatan',
            'distribusiZona'
        ));
    }
}
