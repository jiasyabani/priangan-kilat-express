<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use App\Models\Paket;
use App\Models\RiwayatStatusPaket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class KurirApiController extends Controller
{
    // Login menggunakan kode kurir dan password.
    public function login(Request $request)
    {
        $validated = $request->validate([
            'kode_kurir' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $kurir = Kurir::with('user')
            ->where('kode_kurir', $validated['kode_kurir'])
            ->first();

        $user = $kurir?->user;

        if (
            !$user ||
            $user->role !== 'kurir' ||
            !Hash::check($validated['password'], $user->password)
        ) {
            throw ValidationException::withMessages([
                'kode_kurir' => ['Kode kurir atau password salah.'],
            ]);
        }

        if (!$kurir->aktif) {
            throw ValidationException::withMessages([
                'kode_kurir' => ['Akun kurir tidak aktif.'],
            ]);
        }

        // Hapus token lama sebelum membuat sesi baru.
        $user->tokens()->delete();

        $token = $user->createToken('kurir-app')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'token' => $token,
            'wajib_ganti_password' => (bool) $user->wajib_ganti_password,
            'kurir' => [
                'id' => $kurir->id,
                'kode_kurir' => $kurir->kode_kurir,
                'nama' => $user->name,
            ],
        ]);
    }

    // Mengganti password sementara atau password lama.
    public function changePassword(Request $request)
    {
        $user = $request->user();
        $kurir = $user->kurir;

        if (
            $user->role !== 'kurir' ||
            !$kurir ||
            !$kurir->aktif
        ) {
            return response()->json([
                'message' => 'Akses hanya tersedia untuk akun kurir aktif.',
            ], 403);
        }

        $validated = $request->validate([
            'password_lama' => ['required', 'string'],
            'password_baru' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:password_lama',
            ],
        ]);

        if (!Hash::check($validated['password_lama'], $user->password)) {
            throw ValidationException::withMessages([
                'password_lama' => ['Password lama tidak sesuai.'],
            ]);
        }

        $user->password = Hash::make($validated['password_baru']);
        $user->wajib_ganti_password = false;
        $user->save();

        // Cabut seluruh token. Kurir harus login kembali.
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Password berhasil diganti. Silakan login kembali.',
            'wajib_ganti_password' => false,
        ]);
    }

    // Menampilkan paket yang sedang dibawa kurir.
    public function index(Request $request)
    {
        $user = $request->user();
        $kurir = $user->kurir;

        if (
            $user->role !== 'kurir' ||
            !$kurir ||
            !$kurir->aktif
        ) {
            return response()->json([
                'message' => 'Akses hanya tersedia untuk akun kurir aktif.',
            ], 403);
        }

        if ($user->wajib_ganti_password) {
            return response()->json([
                'message' => 'Silakan ganti password terlebih dahulu.',
                'wajib_ganti_password' => true,
            ], 403);
        }

        $pakets = Paket::where('kurir_id', $kurir->id)
            ->where('status', 'Dikirim')
            ->orderBy('tanggal_masuk')
            ->get([
                'id',
                'no_resi',
                'nama_penerima',
                'no_telepon_penerima',
                'alamat_penerima',
                'kota_tujuan',
                'berat',
                'layanan',
                'nilai_cod',
                'status',
                'jumlah_gagal',
            ]);

        return response()->json([
            'message' => 'Daftar paket berhasil diambil.',
            'data' => $pakets,
        ]);
    }

    // Menampilkan paket COD terkirim dan total COD yang belum disetor.
    public function cod(Request $request)
    {
        $user = $request->user();
        $kurir = $user->kurir;

        if (
            $user->role !== 'kurir' ||
            !$kurir ||
            !$kurir->aktif
        ) {
            return response()->json([
                'message' => 'Akses hanya tersedia untuk akun kurir aktif.',
            ], 403);
        }

        if ($user->wajib_ganti_password) {
            return response()->json([
                'message' => 'Silakan ganti password terlebih dahulu.',
                'wajib_ganti_password' => true,
            ], 403);
        }

        // Semua paket COD yang sudah terkirim oleh kurir.
        $pakets = Paket::where('kurir_id', $kurir->id)
            ->where('status', 'Terkirim')
            ->where('nilai_cod', '>', 0)
            ->orderByDesc('updated_at')
            ->get([
                'id',
                'no_resi',
                'nama_penerima',
                'nilai_cod',
                'status',
                'cod_disetor',
            ]);

        // Menghitung hanya COD yang belum disetorkan.
        $totalCodBelumDisetor = Paket::where('kurir_id', $kurir->id)
            ->where('status', 'Terkirim')
            ->where('nilai_cod', '>', 0)
            ->where('cod_disetor', false)
            ->sum('nilai_cod');

        return response()->json([
            'message' => 'Rekap COD berhasil diambil.',
            'jumlah_paket' => $pakets->count(),
            'total_cod_belum_disetor' => $totalCodBelumDisetor,
            'data' => $pakets,
        ]);
    }

    // Memperbarui status pengantaran paket milik kurir.
    public function updateStatus(Request $request, Paket $paket)
    {
        $user = $request->user();
        $kurir = $user->kurir;

        if (
            $user->role !== 'kurir' ||
            !$kurir ||
            !$kurir->aktif
        ) {
            return response()->json([
                'message' => 'Akses hanya tersedia untuk akun kurir aktif.',
            ], 403);
        }

        if ($user->wajib_ganti_password) {
            return response()->json([
                'message' => 'Silakan ganti password terlebih dahulu.',
                'wajib_ganti_password' => true,
            ], 403);
        }

        $validated = $request->validate([
            'status' => [
                'required',
                'in:Terkirim,Gagal Kirim',
            ],
            'catatan' => [
                'required_if:status,Gagal Kirim',
                'nullable',
                'string',
                'max:500',
            ],
        ], [
            'catatan.required_if' =>
                'Catatan wajib diisi jika paket gagal dikirim.',
        ]);

        DB::transaction(function () use (
            $paket,
            $validated,
            $user,
            $kurir
        ) {
            // Mengunci data paket selama proses perubahan.
            $paket = Paket::whereKey($paket->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Memeriksa kembali kepemilikan paket di dalam transaksi.
            if ((int) $paket->kurir_id !== (int) $kurir->id) {
                throw ValidationException::withMessages([
                    'paket' => ['Paket bukan milik kurir ini.'],
                ]);
            }

            if ($paket->status !== 'Dikirim') {
                throw ValidationException::withMessages([
                    'status' => ['Status paket tidak dapat diperbarui.'],
                ]);
            }

            $statusSebelumnya = $paket->status;

            if ($validated['status'] === 'Gagal Kirim') {
                if ($paket->jumlah_gagal >= 2) {
                    throw ValidationException::withMessages([
                        'status' => [
                            'Batas gagal kirim sudah tercapai. Paket harus dikembalikan oleh Admin.',
                        ],
                    ]);
                }

                $paket->jumlah_gagal++;
            }

            $paket->status = $validated['status'];
            $paket->save();

            // Mencatat riwayat perubahan status.
            RiwayatStatusPaket::create([
                'paket_id' => $paket->id,
                'status_sebelumnya' => $statusSebelumnya,
                'status_baru' => $validated['status'],
                'user_id' => $user->id,
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
