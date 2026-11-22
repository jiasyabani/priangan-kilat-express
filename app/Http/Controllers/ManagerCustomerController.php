<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class ManagerCustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pelanggans = Pelanggan::query()
            ->withCount('pakets')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('no_telepon', 'like', "%{$search}%")
                        ->orWhere('kota', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        $totalPelanggan = Pelanggan::count();

        return view('manager.customers.index', [
            'pelanggans' => $pelanggans,
            'totalPelanggan' => $totalPelanggan,
            'search' => $search,
        ]);
    }
}
