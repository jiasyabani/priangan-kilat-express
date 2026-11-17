<?php

namespace Database\Seeders;

use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Kurir;
use App\Models\Tarif;
use App\Models\RiwayatStatusPaket;
use App\Services\PusatWilayahService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaketHistorisCsvSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/data/paket_historis.csv');

        if (!file_exists($path)) {
            $this->command->error('File paket_historis.csv tidak ditemukan.');
            return;
        }

        $handle = fopen($path, 'r');

        $header = fgetcsv($handle);

        if (!$header) {
            $this->command->error('File CSV kosong atau tidak valid.');
            fclose($handle);
            return;
        }

        // Membersihkan BOM jika terdapat pada kolom pertama.
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);

        $service = app(PusatWilayahService::class);

        $pelanggans = Pelanggan::all()->groupBy(
            fn ($item) => mb_strtolower(trim($item->nama))
        );

        $kurirs = Kurir::all()->keyBy('kode_kurir');

        $tarifs = Tarif::all()->keyBy('zona');

        // Cache hasil API agar kota yang sama tidak dipanggil berulang.
        $cacheZona = [];

        $berhasil = 0;
        $dilewati = 0;

        while (($row = fgetcsv($handle)) !== false) {

            if (count($row) !== count($header)) {
                $dilewati++;
                $this->command->warn('Baris CSV tidak valid, dilewati.');
                continue;
            }

            $data = array_combine($header, $row);

            $resi = trim($data['no_resi']);
            $namaPengirim = mb_strtolower(trim($data['pengirim']));
            $kota = trim($data['kota_tujuan']);

            // Cegah resi duplikat.
            if (Paket::where('no_resi', $resi)->exists()) {
                $dilewati++;
                $this->command->warn("Resi {$resi} sudah ada, dilewati.");
                continue;
            }

            // Cocokkan pengirim dengan data pelanggan.
            $calonPengirim = $pelanggans->get($namaPengirim);

            if (!$calonPengirim || $calonPengirim->count() !== 1) {
                $dilewati++;
                $this->command->warn(
                    "Pengirim {$data['pengirim']} tidak ditemukan atau ambigu."
                );
                continue;
            }

            $pengirim = $calonPengirim->first();

            // Cocokkan kode kurir.
            $kodeKurir = trim($data['kode_kurir']);
            $kurir = $kurirs->get($kodeKurir);

            if ($kodeKurir !== '' && !$kurir) {
                $dilewati++;
                $this->command->warn(
                    "Kurir {$kodeKurir} tidak ditemukan untuk resi {$resi}."
                );
                continue;
            }

            // Ambil zona dari API Pusat Wilayah.
            if (!array_key_exists($kota, $cacheZona)) {
                $cacheZona[$kota] = $service->cariZona($kota);
            }

            $hasilZona = $cacheZona[$kota];

            if (!$hasilZona['tersedia'] || $hasilZona['zona'] === null) {
                $dilewati++;
                $this->command->warn(
                    "Zona kota {$kota} tidak ditemukan. Resi {$resi} dilewati."
                );
                continue;
            }

            $zona = $hasilZona['zona'];
            $tarif = $tarifs->get($zona);

            if (!$tarif) {
                $dilewati++;
                $this->command->warn(
                    "Tarif zona {$zona} belum tersedia."
                );
                continue;
            }

            // Berat kosong disimpan NULL dan perlu diverifikasi.
            $beratInput = trim($data['berat_kg']);

            $berat = ($beratInput === '' || (float) $beratInput <= 0)
                ? null
                : (float) $beratInput;

            $perluVerifikasi = $berat === null;

            // Ongkir hanya dihitung jika berat tersedia.
            if ($berat === null) {
                $ongkir = 0;
            } else {
                $beratTagihan = max(1, ceil($berat));

                $ongkir = $beratTagihan * (float) $tarif->tarif_per_kg;

                if (trim($data['layanan']) === 'Express') {
                    $ongkir *= 1.5;
                }
            }

            // Perhitungan COD.
            $nilaiCod = (float) $data['nilai_cod'];

            $biayaCod = $nilaiCod > 0
                ? max($nilaiCod * 0.02, 2000)
                : 0;

            // Normalisasi nomor telepon.
            $telepon = preg_replace(
                '/\D/',
                '',
                $data['telepon_penerima']
            );

            if (str_starts_with($telepon, '0')) {
                $telepon = '62' . substr($telepon, 1);
            } elseif (str_starts_with($telepon, '8')) {
                $telepon = '62' . $telepon;
            }

            $tanggal = Carbon::parse($data['tanggal_terima']);

            DB::transaction(function () use (
                $data,
                $resi,
                $pengirim,
                $kurir,
                $telepon,
                $zona,
                $tarif,
                $berat,
                $perluVerifikasi,
                $ongkir,
                $nilaiCod,
                $biayaCod,
                $tanggal
            ) {
                $paket = Paket::create([
                    'no_resi' => $resi,
                    'pengirim_id' => $pengirim->id,
                    'kurir_id' => $kurir?->id,

                    'nama_penerima' => trim($data['penerima']),
                    'no_telepon_penerima' => $telepon,

                    'alamat_penerima' =>
                        'Alamat tidak tersedia pada data historis.',

                    'kota_tujuan' => trim($data['kota_tujuan']),
                    'zona' => $zona,
                    'zona_manual' => false,

                    'berat' => $berat,
                    'perlu_verifikasi' => $perluVerifikasi,

                    'layanan' => trim($data['layanan']),
                    'tarif_per_kg' => $tarif->tarif_per_kg,
                    'ongkir' => $ongkir,

                    'nilai_cod' => $nilaiCod,
                    'biaya_cod' => $biayaCod,
                    'cod_disetor' => false,

                    'status' => trim($data['status']),
                    'jumlah_gagal' => 0,

                    'tanggal_masuk' => $tanggal,
                ]);

                // Catat status awal paket dalam riwayat.
                RiwayatStatusPaket::create([
                    'paket_id' => $paket->id,
                    'status_sebelumnya' => null,
                    'status_baru' => $paket->status,
                    'user_id' => null,
                    'pelaku' => 'Admin',
                    'catatan' => 'Impor data historis dari CSV.',
                    'waktu_perubahan' => $tanggal,
                ]);
            });

            $berhasil++;
        }

        fclose($handle);

        $this->command->info('================================');
        $this->command->info('IMPORT PAKET HISTORIS SELESAI');
        $this->command->info("Berhasil ditambahkan: {$berhasil}");
        $this->command->info("Data dilewati: {$dilewati}");
        $this->command->info('================================');
    }
}
