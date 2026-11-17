<?php

namespace App\Http\Controllers;

// Memanggil model Pelanggan untuk mengambil data dari database.
use App\Models\Pelanggan;

class PelangganController extends Controller
{
    // Menampilkan daftar seluruh data pelanggan.
    public function index()
    {
        // Mengambil data pelanggan dan mengurutkannya berdasarkan nama.
        // Setiap halaman menampilkan maksimal 10 pelanggan.
        $pelanggans = Pelanggan::orderBy('nama', 'asc')
            ->paginate(10);

        // Mengirim data pelanggan ke halaman Data Pelanggan.
        return view('admin.customers.index', compact('pelanggans'));
    }
}
