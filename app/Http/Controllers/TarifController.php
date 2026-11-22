<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    public function index()
    {
        $tarifs = Tarif::orderBy('zona', 'asc')->get();

        return view('admin.shipping-rates.index', compact('tarifs'));
    }

    public function managerIndex()
    {
        $tarifs = Tarif::orderBy('zona', 'asc')->get();

        return view('manager.shipping-rates.index', compact('tarifs'));
    }

    public function update(Request $request, Tarif $tarif)
    {
        $validated = $request->validate([
            'tarif_per_kg' => ['required', 'numeric', 'min:1'],
        ]);

        $tarif->update([
            'tarif_per_kg' => $validated['tarif_per_kg'],
        ]);

        return redirect()
            ->route('manager.shipping-rates.index')
            ->with('success', 'Tarif pengiriman berhasil diperbarui.');
    }
}
