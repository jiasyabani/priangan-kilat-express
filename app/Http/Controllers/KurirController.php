<?php

namespace App\Http\Controllers;

use App\Models\Kurir;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KurirController extends Controller
{
    // Menampilkan daftar data kurir.
    public function index()
    {
        $kurirs = Kurir::with('user')
            ->orderBy('kode_kurir', 'asc')
            ->paginate(10);

        return view('admin.couriers.index', compact('kurirs'));
    }

    // Menampilkan form tambah kurir.
    public function create()
    {
        return view('admin.couriers.create');
    }

    // Menyimpan data kurir sekaligus membuat akun.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'kode_kurir' => ['required', 'string', 'max:20', 'unique:kurirs,kode_kurir'],
            'no_telepon' => ['required', 'string', 'max:20'],
        ]);

        DB::transaction(function () use ($validated) {

            // Membuat password sementara untuk akun kurir.
            $passwordSementara = Str::random(12);

            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => $passwordSementara,
                'role' => 'kurir',
                'wajib_ganti_password' => true,
            ]);

            // Menyimpan data kurir dan menghubungkannya dengan akun.
            Kurir::create([
                'user_id' => $user->id,
                'kode_kurir' => $validated['kode_kurir'],
                'no_telepon' => $validated['no_telepon'],
                'aktif' => true,
            ]);

            // Menampilkan password sementara satu kali kepada Admin.
            session()->flash('password_sementara', $passwordSementara);
        });

        return redirect()
            ->route('admin.couriers.index')
            ->with('success', 'Data kurir dan akun berhasil dibuat.');
    }

    // Menampilkan form edit kurir.
    public function edit(Kurir $kurir)
    {
        $kurir->load('user');

        return view('admin.couriers.edit', compact('kurir'));
    }

    // Memperbarui data kurir dan akun.
    public function update(Request $request, Kurir $kurir)
    {
        $kurir->load('user');

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($kurir->user_id),
            ],
            'kode_kurir' => [
                'required',
                'string',
                'max:20',
                Rule::unique('kurirs', 'kode_kurir')->ignore($kurir->id),
            ],
            'no_telepon' => ['required', 'string', 'max:20'],
        ]);

        DB::transaction(function () use ($validated, $kurir) {

            // Memperbarui informasi akun kurir.
            $kurir->user->update([
                'name' => $validated['nama'],
                'email' => $validated['email'],
            ]);

            // Memperbarui informasi data kurir.
            $kurir->update([
                'kode_kurir' => $validated['kode_kurir'],
                'no_telepon' => $validated['no_telepon'],
            ]);
        });

        return redirect()
            ->route('admin.couriers.index')
            ->with('success', 'Data kurir berhasil diperbarui.');
    }

    // Menonaktifkan kurir yang tidak sedang membawa paket Dikirim.
    public function deactivate(Kurir $kurir)
    {
        // Memeriksa paket yang masih dalam proses pengiriman.
        $masihMengirim = $kurir->pakets()
            ->where('status', 'Dikirim')
            ->exists();

        if ($masihMengirim) {
            return back()->with(
                'error',
                'Kurir tidak dapat dinonaktifkan karena masih membawa paket berstatus Dikirim.'
            );
        }

        // Mengubah status aktif kurir.
        $kurir->update([
            'aktif' => false,
        ]);

        return back()->with('success', 'Kurir berhasil dinonaktifkan.');
    }

    // Mereset password akun kurir.
    public function resetPassword(Kurir $kurir)
    {
        // Membuat password sementara baru.
        $passwordSementara = Str::random(12);

        $kurir->user->update([
            'password' => $passwordSementara,
            'wajib_ganti_password' => true,
        ]);

        // Menampilkan password sementara satu kali kepada Admin.
        return back()
            ->with('success', 'Password akun kurir berhasil direset.')
            ->with('password_sementara', $passwordSementara);
    }
}

