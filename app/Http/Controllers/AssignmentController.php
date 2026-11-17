<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Kurir;
use App\Models\RiwayatStatusPaket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AssignmentController extends Controller
{
    public function index()
    {
        $pakets = Paket::with(['pengirim', 'kurir'])
            ->where(function ($query) {
                $query->where('status', 'Transit')
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('status', 'Gagal Kirim')
                            ->where('jumlah_gagal', '<', 2);
                    })
                    ->orWhere('status', 'Dikirim');
            })
            ->latest()
            ->paginate(10);

        $kurirs = Kurir::where('aktif', true)
            ->withCount([
                'pakets as jumlah_dikirim' => function ($query) {
                    $query->where('status', 'Dikirim');
                }
            ])
            ->orderBy('kode_kurir')
            ->get();

        return view('admin.assignments.index', compact('pakets', 'kurirs'));
    }

    public function store(Request $request, Paket $paket)
    {
        $validated = $request->validate([
            'kurir_id' => ['required', 'integer', 'exists:kurirs,id'],
        ]);

        DB::transaction(function () use ($validated, $paket) {
            $paket = Paket::whereKey($paket->id)
                ->lockForUpdate()
                ->firstOrFail();

            $bolehDitugaskan = $paket->status === 'Transit'
                || (
                    $paket->status === 'Gagal Kirim'
                    && $paket->jumlah_gagal < 2
                );

            if (!$bolehDitugaskan) {
                throw ValidationException::withMessages([
                    'kurir_id' => 'Paket tidak memenuhi syarat untuk ditugaskan atau dikirim ulang.',
                ]);
            }

            $kurir = Kurir::whereKey($validated['kurir_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if (!$kurir->aktif) {
                throw ValidationException::withMessages([
                    'kurir_id' => 'Kurir tidak aktif dan tidak dapat menerima penugasan.',
                ]);
            }

            $jumlahDikirim = Paket::where('kurir_id', $kurir->id)
                ->where('status', 'Dikirim')
                ->count();

            if ($jumlahDikirim >= 20) {
                throw ValidationException::withMessages([
                    'kurir_id' => 'Kapasitas kurir sudah penuh (20 paket).',
                ]);
            }

            $statusSebelumnya = $paket->status;

            $paket->kurir_id = $kurir->id;
            $paket->status = 'Dikirim';
            $paket->save();

            RiwayatStatusPaket::create([
                'paket_id' => $paket->id,
                'status_sebelumnya' => $statusSebelumnya,
                'status_baru' => 'Dikirim',
                'user_id' => auth()->id(),
                'pelaku' => 'Admin',
                'catatan' => $statusSebelumnya === 'Gagal Kirim'
                    ? 'Paket dikirim ulang kepada kurir ' . $kurir->kode_kurir
                    : 'Paket ditugaskan kepada kurir ' . $kurir->kode_kurir,
                'waktu_perubahan' => now(),
            ]);
        });

        return back()->with('success', 'Penugasan kurir berhasil disimpan.');
    }
}
