<?php

namespace App\Http\Controllers;

use App\Models\RiwayatStatusPaket;
use Illuminate\Http\Request;

class RiwayatStatusController extends Controller
{
    public function index(Request $request)
    {
        $query = RiwayatStatusPaket::with(['paket', 'user'])
            ->orderByDesc('waktu_perubahan');

        if ($request->filled('cari')) {
            $cari = trim($request->cari);

            $query->whereHas('paket', function ($subQuery) use ($cari) {
                $subQuery->where('no_resi', 'like', "%{$cari}%");
            });
        }

        $riwayats = $query->paginate(10)->withQueryString();

        return view('admin.history.index', compact('riwayats'));
    }
}
