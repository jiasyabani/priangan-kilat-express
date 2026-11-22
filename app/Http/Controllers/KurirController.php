<?php

namespace App\Http\Controllers;

use App\Models\Kurir;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'kode_kurir' => [
                'required',
                'string',
                'max:20',
                'unique:kurirs,kode_kurir',
            ],
            'no_telepon' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s]+$/',
                function ($attribute, $value, $fail) {
                    $jumlahDigit = strlen(preg_replace('/\D/', '', $value));

                    if ($jumlahDigit < 10) {
                        $fail('Nomor telepon harus terdiri dari minimal 10 digit.');
                    }
                },
            ],
        ], [
            'nama.required' => 'Nama kurir wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'kode_kurir.required' => 'Kode kurir wajib diisi.',
            'kode_kurir.unique' => 'Kode kurir sudah digunakan.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.regex' => 'Format nomor telepon tidak valid.',
        ]);

        DB::transaction(function () use ($validated) {
            // Membuat password sementara minimal 8 karakter.
            $passwordSementara = Str::random(12);

            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($passwordSementara),
                'role' => 'kurir',
                'wajib_ganti_password' => true,
            ]);

            Kurir::create([
                'user_id' => $user->id,
                'kode_kurir' => $validated['kode_kurir'],
                'no_telepon' => $validated['no_telepon'],
                'aktif' => true,
            ]);

            // Password sementara ditampilkan kepada Admin.
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
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
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
            'no_telepon' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s]+$/',
                function ($attribute, $value, $fail) {
                    $jumlahDigit = strlen(preg_replace('/\D/', '', $value));

                    if ($jumlahDigit < 10) {
                        $fail('Nomor telepon harus terdiri dari minimal 10 digit.');
                    }
                },
            ],
        ], [
            'nama.required' => 'Nama kurir wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'kode_kurir.required' => 'Kode kurir wajib diisi.',
            'kode_kurir.unique' => 'Kode kurir sudah digunakan.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.regex' => 'Format nomor telepon tidak valid.',
        ]);

        DB::transaction(function () use ($validated, $kurir) {
            $kurir->user->update([
                'name' => $validated['nama'],
                'email' => $validated['email'],
            ]);

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
        $masihMengirim = $kurir->pakets()
            ->where('status', 'Dikirim')
            ->exists();

        if ($masihMengirim) {
            return back()->with(
                'error',
                'Kurir tidak dapat dinonaktifkan karena masih membawa paket berstatus Dikirim.'
            );
        }

        $kurir->update([
            'aktif' => false,
        ]);

        return back()->with(
            'success',
            'Kurir berhasil dinonaktifkan.'
        );
    }

    // Mereset password akun kurir.
    public function resetPassword(Kurir $kurir)
    {
        $passwordSementara = Str::random(12);

        DB::transaction(function () use ($kurir, $passwordSementara) {
            $kurir->load('user');

            $kurir->user->update([
                'password' => Hash::make($passwordSementara),
                'wajib_ganti_password' => true,
            ]);

            // Mencabut seluruh token lama agar kurir harus login kembali.
            $kurir->user->tokens()->delete();
        });

        return back()
            ->with('success', 'Password akun kurir berhasil direset.')
            ->with('password_sementara', $passwordSementara);
    }
}
