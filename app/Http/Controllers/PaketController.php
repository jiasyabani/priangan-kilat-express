<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Kurir;
use App\Models\Tarif;
use App\Services\PusatWilayahService;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    // Menampilkan dan mengelola data paket.
    public function index(Request $request)
    {
        // Daftar status sesuai ketentuan UKK.
        $daftarStatus = [
            'Diterima',
            'Transit',
            'Dikirim',
            'Terkirim',
            'Gagal Kirim',
            'Retur',
        ];

        // Mengambil kata pencarian nomor resi.
        $pencarian = trim($request->query('cari', ''));

        // Mengambil filter status jika status valid.
        $statusAktif = $request->query('status');

        if (!in_array($statusAktif, $daftarStatus, true)) {
            $statusAktif = null;
        }

        // Mengambil data paket beserta pengirim dan kurir.
        $query = Paket::with(['pengirim', 'kurir'])
            ->latest();

        // Pencarian berdasarkan nomor resi.
        if ($pencarian !== '') {
            $query->where('no_resi', 'like', "%{$pencarian}%");
        }

        // Filter berdasarkan status paket.
        if ($statusAktif !== null) {
            $query->where('status', $statusAktif);
        }

        // Menampilkan 10 paket per halaman.
        $pakets = $query->paginate(10)->withQueryString();

        // Menghitung jumlah paket Dikirim pada setiap kurir aktif.
        $kurirs = Kurir::where('aktif', true)
            ->withCount([
                'pakets as jumlah_dikirim' => function ($query) {
                    $query->where('status', 'Dikirim');
                }
            ])
            ->orderBy('kode_kurir')
            ->get();

        return view('admin.packages.index', compact(
            'pakets',
            'pencarian',
            'statusAktif',
            'daftarStatus',
            'kurirs'
        ));
    }

    // Menampilkan formulir input paket.
    public function create()
    {
        $pelanggans = Pelanggan::orderBy('nama')->get();

        $tarifs = Tarif::orderBy('zona')->get();

        return view('admin.packages.create', compact(
            'pelanggans',
            'tarifs'
        ));
    }

    // Memeriksa zona kota melalui API Pusat Wilayah.
    public function checkZone(
        Request $request,
        PusatWilayahService $service
    ) {
        $validated = $request->validate([
            'kota' => ['required', 'string', 'max:100'],
        ]);

        $hasil = $service->cariZona($validated['kota']);

        return response()->json($hasil);
    }
}
