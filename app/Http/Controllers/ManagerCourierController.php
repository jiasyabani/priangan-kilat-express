<?php

namespace App\Http\Controllers;

use App\Models\Kurir;
use Illuminate\Http\Request;

class ManagerCourierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $kurirs = Kurir::with('user')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_kurir', 'like', "%{$search}%")
                        ->orWhere('no_telepon', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('kode_kurir')
            ->paginate(10)
            ->withQueryString();

        $totalKurir = Kurir::count();
        $kurirAktif = Kurir::where('aktif', true)->count();
        $kurirNonaktif = Kurir::where('aktif', false)->count();

        return view('manager.couriers.index', compact(
            'kurirs',
            'totalKurir',
            'kurirAktif',
            'kurirNonaktif',
            'search'
        ));
    }
}
