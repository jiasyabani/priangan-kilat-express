<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    // Menampilkan daftar pelanggan.
    public function index()
    {
        $pelanggans = Pelanggan::orderBy('nama', 'asc')
            ->paginate(10);

        return view('admin.customers.index', compact('pelanggans'));
    }

    // Menampilkan form tambah pelanggan.
    public function create()
    {
        return view('admin.customers.create');
    }

    // Menyimpan pelanggan baru.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:150',
            ],
            'no_telepon' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9+()\-\s]+$/',
                function ($attribute, $value, $fail) {
                    $jumlahDigit = strlen(
                        preg_replace('/\D/', '', $value)
                    );

                    if ($jumlahDigit < 10) {
                        $fail(
                            'Nomor telepon harus terdiri dari minimal 10 digit.'
                        );
                    }
                },
                'unique:pelanggans,no_telepon',
            ],
            'alamat' => [
                'nullable',
                'string',
            ],
            'kota' => [
                'nullable',
                'string',
                'max:100',
            ],
        ], [
            'nama.required' => 'Nama pelanggan wajib diisi.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.unique' => 'Nomor telepon sudah terdaftar.',
            'no_telepon.regex' => 'Format nomor telepon tidak valid.',
        ]);

        Pelanggan::create($validated);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    // Menampilkan form edit pelanggan.
    public function edit(Pelanggan $pelanggan)
    {
        return view('admin.customers.edit', compact('pelanggan'));
    }

    // Memperbarui data pelanggan.
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:150',
            ],
            'no_telepon' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9+()\-\s]+$/',
                function ($attribute, $value, $fail) {
                    $jumlahDigit = strlen(
                        preg_replace('/\D/', '', $value)
                    );

                    if ($jumlahDigit < 10) {
                        $fail(
                            'Nomor telepon harus terdiri dari minimal 10 digit.'
                        );
                    }
                },
                Rule::unique('pelanggans', 'no_telepon')
                    ->ignore($pelanggan->id),
            ],
            'alamat' => [
                'nullable',
                'string',
            ],
            'kota' => [
                'nullable',
                'string',
                'max:100',
            ],
        ], [
            'nama.required' => 'Nama pelanggan wajib diisi.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.unique' => 'Nomor telepon sudah digunakan pelanggan lain.',
            'no_telepon.regex' => 'Format nomor telepon tidak valid.',
        ]);

        $pelanggan->update($validated);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }
}
