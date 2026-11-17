<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use App\Models\Paket;
use App\Models\RiwayatStatusPaket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class KurirApiController extends Controller
{
    // Login khusus akun kurir.
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])
            ->where('role', 'kurir')
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        if (!$user->kurir || !$user->kurir->aktif) {
            throw ValidationException::withMessages([
                'email' => ['Akun kurir tidak aktif.'],
            ]);
        }

        $token = $user->createToken('kurir-app')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'token' => $token,
            'wajib_ganti_password' => $user->wajib_ganti_password,
            'kurir' => [
                'id' => $user->kurir->id,
                'kode_kurir' => $user->kurir->kode_kurir,
                'nama' => $user->name,
            ],
        ]);
    }

    // Mengganti password sementara saat pertama kali login.
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'password_lama' => ['required', 'string'],
            'password_baru' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if (!Hash::check($validated['password_lama'], $user->password)) {
            throw ValidationException::withMessages([
                'password_lama' => ['Password lama tidak sesuai.'],
            ]);
        }

        $user->password = $validated['password_baru'];
        $user->wajib_ganti_password = false;
        $user->save();

        // Cabut token lama agar kurir login ulang dengan password baru.
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Password berhasil diganti. Silakan login kembali.',
        ]);
    }

    // Menampilkan paket milik kurir yang sedang login.
    public function index(Request $request)
    {
        $kurir = $request->user()->kurir;

        if (!$kurir) {
            return response()->json([
                'message' => 'Data kurir tidak ditemukan.',
            ], 404);
        }

        $pakets = Paket::with('pengirim')
            ->where('kurir_id', $kurir->id)
            ->whereIn('status', [
                'Dikirim',
                'Terkirim',
                'Gagal Kirim',
                'Retur',
            ])
            ->latest()
            ->get();

        return response()->json([
            'data' => $pakets,
        ]);
    }

    // Memperbarui status pengantaran paket milik kurir.
    public function updateStatus(Request $request, Paket $paket)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Terkirim,Gagal Kirim'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $kurir = $request->user()->kurir;

        if (!$kurir || $paket->kurir_id !== $kurir->id) {
            return response()->json([
                'message' => 'Paket bukan milik kurir ini.',
            ], 403);
        }

        DB::transaction(function () use ($paket, $validated, $request) {
            $paket = Paket::whereKey($paket->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($paket->status !== 'Dikirim') {
                throw ValidationException::withMessages([
                    'status' => ['Status paket tidak dapat diperbarui.'],
                ]);
            }

            $statusSebelumnya = $paket->status;

            if ($validated['status'] === 'Gagal Kirim') {
                if ($paket->jumlah_gagal >= 2) {
                    throw ValidationException::withMessages([
                        'status' => ['Batas gagal kirim sudah tercapai.'],
                    ]);
                }

                $paket->jumlah_gagal++;
            }

            $paket->status = $validated['status'];
            $paket->save();

            RiwayatStatusPaket::create([
                'paket_id' => $paket->id,
                'status_sebelumnya' => $statusSebelumnya,
                'status_baru' => $validated['status'],
                'user_id' => $request->user()->id,
                'pelaku' => 'Kurir',
                'catatan' => $validated['catatan'] ?? null,
                'waktu_perubahan' => now(),
            ]);
        });

        return response()->json([
            'message' => 'Status paket berhasil diperbarui.',
        ]);
    }

    // Logout dan mencabut token yang sedang digunakan.
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }
}
