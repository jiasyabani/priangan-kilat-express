<?php

namespace App\Http\Controllers;

use App\Models\Tarif;

class TarifController extends Controller
{
    // Menampilkan daftar tarif pengiriman berdasarkan urutan zona.
    public function index()
    {
        $tarifs = Tarif::orderBy('zona', 'asc')->get();

        return view('admin.shipping-rates.index', compact('tarifs'));
    }
}
