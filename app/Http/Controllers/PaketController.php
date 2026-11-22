<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Kurir;
use App\Models\Tarif;
use App\Models\RiwayatStatusPaket;
use App\Services\PusatWilayahService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PaketController extends Controller
{
    // Menampilkan dan mengelola data paket.
    public function index(Request $request)
    {
        $daftarStatus = [
            'Diterima',
            'Transit',
            'Dikirim',
            'Terkirim',
            'Gagal Kirim',
            'Retur',
        ];

        $pencarian = trim($request->query('cari', ''));

        $statusAktif = $request->query('status');

        if (!in_array($statusAktif, $daftarStatus, true)) {
            $statusAktif = null;
        }

        $query = Paket::with(['pengirim', 'kurir'])->latest();

        if ($pencarian !== '') {
            $query->where('no_resi', 'like', "%{$pencarian}%");
        }

        if ($statusAktif !== null) {
            $query->where('status', $statusAktif);
        }

        $pakets = $query->paginate(10)->withQueryString();

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

    // Menampilkan detail paket.
    public function show(Paket $paket)
    {
        $paket->load([
            'pengirim',
            'kurir',
            'riwayatStatus'
        ]);

        return view('admin.packages.show', compact('paket'));
    }

    // Menyimpan paket baru.
    public function store(
        Request $request,
        PusatWilayahService $service
    ) {
        $validated = $request->validate([
            'pengirim_id' => [
                'required',
                'integer',
                'exists:pelanggans,id',
            ],
            'nama_penerima' => [
                'required',
                'string',
                'max:150',
            ],
            'no_telepon_penerima' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s]+$/',
                function ($attribute, $value, $fail) {
                    $jumlahDigit = strlen(
                        preg_replace('/\D/', '', $value)
                    );

                    if ($jumlahDigit < 10) {
                        $fail(
                            'Nomor telepon penerima harus terdiri dari minimal 10 digit.'
                        );
                    }
                },
            ],
            'alamat_penerima' => [
                'required',
                'string',
            ],
            'kota_tujuan' => [
                'required',
                'string',
                'max:100',
            ],
            'berat' => [
                'required',
                'numeric',
                'gt:0',
                'max:999999.99',
            ],
            'layanan' => [
                'required',
                'in:Reguler,Express',
            ],
            'nilai_cod' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999999999.99',
            ],
        ], [
            'pengirim_id.required' => 'Silakan pilih pengirim.',
            'pengirim_id.exists' => 'Data pengirim tidak ditemukan.',
            'nama_penerima.required' => 'Nama penerima wajib diisi.',
            'no_telepon_penerima.required' => 'Nomor telepon penerima wajib diisi.',
            'no_telepon_penerima.regex' => 'Format nomor telepon penerima tidak valid.',
            'alamat_penerima.required' => 'Alamat penerima wajib diisi.',
            'kota_tujuan.required' => 'Kota tujuan wajib diisi.',
            'berat.required' => 'Berat paket wajib diisi.',
            'berat.gt' => 'Berat paket harus lebih dari 0 kg.',
            'layanan.in' => 'Jenis layanan tidak valid.',
            'nilai_cod.min' => 'Nilai COD tidak boleh negatif.',
        ]);

        // Cari zona tujuan melalui API.
        $hasilZona = $service->cariZona($validated['kota_tujuan']);

        $zonaManual = false;
        $zona = $hasilZona['zona'] ?? null;

        // Jika zona tidak ditemukan atau layanan tidak tersedia,
        // Admin harus memilih zona secara manual.
        if (
            !($hasilZona['tersedia'] ?? false)
            || $zona === null
        ) {
            $validatorZona = Validator::make(
                $request->all(),
                [
                    'zona_manual_pilihan' => [
                        'required',
                        'integer',
                        'in:1,2,3',
                    ],
                ],
                [
                    'zona_manual_pilihan.required' =>
                        'Silakan tentukan zona tujuan secara manual.',
                    'zona_manual_pilihan.in' =>
                        'Zona manual harus antara zona 1 sampai 3.',
                ]
            );

            if ($validatorZona->fails()) {
                throw new ValidationException($validatorZona);
            }

            $zona = (int) $request->input('zona_manual_pilihan');
            $zonaManual = true;
        } else {
            $zona = (int) $zona;
        }

        // Ambil tarif berdasarkan zona.
        $tarif = Tarif::where('zona', $zona)->first();

        if (!$tarif) {
            throw ValidationException::withMessages([
                'kota_tujuan' => [
                    'Tarif untuk zona tujuan tersebut belum tersedia.',
                ],
            ]);
        }

        // Berat dibulatkan ke atas dengan minimum 1 kg.
        $berat = max(1, (int) ceil((float) $validated['berat']));

        // Hitung ongkir berdasarkan tarif zona.
        $ongkir = (float) $tarif->tarif_per_kg * $berat;

        // Express mendapat tambahan biaya 50%.
        if ($validated['layanan'] === 'Express') {
            $ongkir *= 1.5;
        }

        $ongkir = round($ongkir, 2);

        // Hitung biaya COD.
        $nilaiCod = (float) ($validated['nilai_cod'] ?? 0);
        $biayaCod = 0;

        if ($nilaiCod > 0) {
            $biayaCod = max(2000, $nilaiCod * 0.02);
            $biayaCod = round($biayaCod, 2);
        }

        // Simpan paket dan riwayat awal dalam satu transaksi.
        $paket = DB::transaction(function () use (
            $validated,
            $zona,
            $zonaManual,
            $berat,
            $tarif,
            $ongkir,
            $nilaiCod,
            $biayaCod
        ) {
            $tanggal = now();
            $prefix = 'PKE-' . $tanggal->format('ymd') . '-';

            // Ambil nomor urut terakhir untuk tanggal hari ini.
            $resiTerakhir = Paket::where(
                'no_resi',
                'like',
                $prefix . '%'
            )
                ->orderByDesc('no_resi')
                ->lockForUpdate()
                ->value('no_resi');

            $nomorUrut = $resiTerakhir
                ? ((int) substr($resiTerakhir, -4)) + 1
                : 1;

            $noResi = $prefix . str_pad(
                (string) $nomorUrut,
                4,
                '0',
                STR_PAD_LEFT
            );

            $paket = Paket::create([
                'no_resi' => $noResi,
                'pengirim_id' => $validated['pengirim_id'],
                'kurir_id' => null,
                'nama_penerima' => $validated['nama_penerima'],
                'no_telepon_penerima' => $validated['no_telepon_penerima'],
                'alamat_penerima' => $validated['alamat_penerima'],
                'kota_tujuan' => $validated['kota_tujuan'],
                'zona' => $zona,
                'zona_manual' => $zonaManual,
                'berat' => $berat,
                'perlu_verifikasi' => false,
                'layanan' => $validated['layanan'],
                'tarif_per_kg' => $tarif->tarif_per_kg,
                'ongkir' => $ongkir,
                'nilai_cod' => $nilaiCod,
                'biaya_cod' => $biayaCod,
                'cod_disetor' => false,
                'status' => 'Diterima',
                'jumlah_gagal' => 0,
                'tanggal_masuk' => $tanggal,
            ]);

            RiwayatStatusPaket::create([
                'paket_id' => $paket->id,
                'status_sebelumnya' => null,
                'status_baru' => 'Diterima',
                'user_id' => Auth::id(),
                'pelaku' => 'Admin',
                'catatan' => 'Paket diterima dan dicatat oleh Admin.',
                'waktu_perubahan' => $tanggal,
            ]);

            return $paket;
        });

        return redirect()
            ->route('admin.packages.index')
            ->with(
                'success',
                'Paket berhasil disimpan. Nomor resi: ' . $paket->no_resi
            );
    }
}
