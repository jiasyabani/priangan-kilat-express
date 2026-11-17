<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\RiwayatStatusPaket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StatusPaketController extends Controller
{
    // Mengubah status paket dari Diterima menjadi Transit
    public function transit(Request $request, Paket $paket)
    {
        $validated = $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($paket, $validated) {
            $paket = Paket::whereKey($paket->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($paket->status !== 'Diterima') {
                throw ValidationException::withMessages([
                    'status' => 'Hanya paket berstatus Diterima yang dapat dipindahkan ke Transit.',
                ]);
            }

            $statusSebelumnya = $paket->status;

            $paket->update([
                'status' => 'Transit',
            ]);

            RiwayatStatusPaket::create([
                'paket_id' => $paket->id,
                'status_sebelumnya' => $statusSebelumnya,
                'status_baru' => 'Transit',
                'user_id' => Auth::id(),
                'pelaku' => 'Admin',
                'catatan' => $validated['catatan'] ?? 'Paket masuk proses transit.',
                'waktu_perubahan' => now(),
            ]);
        });

        return back()->with('success', 'Status paket berhasil diubah menjadi Transit.');
    }

    // Mengubah paket yang sudah dua kali gagal kirim menjadi Retur
    public function retur(Request $request, Paket $paket)
    {
        $validated = $request->validate([
            'catatan' => ['required', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($paket, $validated) {
            $paket = Paket::whereKey($paket->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($paket->status !== 'Gagal Kirim' || (int) $paket->jumlah_gagal < 2) {
                throw ValidationException::withMessages([
                    'status' => 'Retur hanya dapat ditetapkan Admin setelah paket mengalami dua kali gagal kirim.',
                ]);
            }

            $statusSebelumnya = $paket->status;

            $paket->update([
                'status' => 'Retur',
            ]);

            RiwayatStatusPaket::create([
                'paket_id' => $paket->id,
                'status_sebelumnya' => $statusSebelumnya,
                'status_baru' => 'Retur',
                'user_id' => Auth::id(),
                'pelaku' => 'Admin',
                'catatan' => $validated['catatan'],
                'waktu_perubahan' => now(),
            ]);
        });

        return back()->with('success', 'Paket berhasil ditetapkan sebagai Retur.');
    }
}
