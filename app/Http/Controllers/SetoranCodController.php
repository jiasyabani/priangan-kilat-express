<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\SetoranCod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SetoranCodController extends Controller
{
    // Menampilkan daftar paket COD dan riwayat setoran.
    public function index()
    {
        $pakets = Paket::with('pengirim', 'kurir.user')
            ->where('nilai_cod', '>', 0)
            ->where('cod_disetor', false)
            ->where('status', 'Terkirim')
            ->whereDoesntHave('setoranCod')
            ->orderBy('tanggal_masuk', 'desc')
            ->get();

        $setorans = SetoranCod::with('paket.pengirim', 'admin')
            ->orderBy('tanggal_setoran', 'desc')
            ->get();

        return view('admin.cod.index', compact('pakets', 'setorans'));
    }

    // Mencatat setoran COD untuk satu paket.
    public function store(Request $request)
    {
        $request->validate([
            'paket_id' => ['required', 'integer', 'exists:pakets,id'],
        ]);

        DB::transaction(function () use ($request) {
            // Mengunci data paket agar tidak disetor bersamaan dua kali.
            $paket = Paket::whereKey($request->paket_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Memastikan paket merupakan paket COD yang sudah terkirim.
            if (
                (float) $paket->nilai_cod <= 0 ||
                $paket->status !== 'Terkirim'
            ) {
                throw ValidationException::withMessages([
                    'paket_id' => 'Paket harus memiliki COD dan berstatus Terkirim.',
                ]);
            }

            // Mencegah pencatatan setoran lebih dari satu kali.
            if (
                $paket->cod_disetor ||
                SetoranCod::where('paket_id', $paket->id)->exists()
            ) {
                throw ValidationException::withMessages([
                    'paket_id' => 'Setoran untuk paket ini sudah pernah dicatat.',
                ]);
            }

            // Menyimpan setoran sesuai nilai COD paket.
            SetoranCod::create([
                'paket_id' => $paket->id,
                'admin_id' => Auth::id(),
                'jumlah_setoran' => $paket->nilai_cod,
                'tanggal_setoran' => now(),
            ]);

            // Menandai paket sebagai sudah disetorkan tanpa mengubah status pengiriman.
            $paket->update([
                'cod_disetor' => true,
            ]);
        });

        return redirect()
            ->route('admin.cod.index')
            ->with('success', 'Setoran COD berhasil dicatat.');
    }
}
