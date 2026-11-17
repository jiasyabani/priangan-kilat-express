
@extends('layouts.admin')

@section('title', 'Input Paket')

@section('content')

<div class="page-heading">
    <div>
        <small class="eyebrow">PRIANGAN KILAT EXPRESS</small>
        <h1>Input Paket Baru</h1>
        <p>Masukkan informasi pengiriman paket dengan lengkap dan benar.</p>
    </div>
</div>

<form id="formInputPaket">

    {{-- DATA PENGIRIM --}}
    <div class="panel package-panel">
        <div class="package-section-heading">
            <div>
                <span class="section-number">01</span>
                <div>
                    <h3>Data Pengirim</h3>
                    <p>Pilih pelanggan yang akan mengirim paket.</p>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-7">
                <label class="form-label">Nama Pengirim <span>*</span></label>
                <select id="pengirim_id" name="pengirim_id" class="form-select">
                    <option value="">Pilih pelanggan</option>
                    @foreach ($pelanggans as $pelanggan)
                        <option
                            value="{{ $pelanggan->id }}"
                            data-telepon="{{ $pelanggan->no_telepon }}"
                        >
                            {{ $pelanggan->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-5">
                <label class="form-label">Nomor Telepon Pengirim</label>
                <input
                    type="text"
                    id="telepon_pengirim"
                    class="form-control"
                    placeholder="Otomatis terisi"
                    readonly
                >
            </div>
        </div>

        <div class="package-note">
            Pelanggan baru dapat ditambahkan melalui menu Data Pelanggan.
        </div>
    </div>

    {{-- DATA PENERIMA --}}
    <div class="panel package-panel">
        <div class="package-section-heading">
            <div>
                <span class="section-number">02</span>
                <div>
                    <h3>Data Penerima</h3>
                    <p>Lengkapi informasi penerima paket.</p>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Penerima <span>*</span></label>
                <input
                    type="text"
                    name="nama_penerima"
                    class="form-control"
                    placeholder="Masukkan nama penerima"
                >
            </div>

            <div class="col-md-6">
                <label class="form-label">Nomor Telepon Penerima <span>*</span></label>
                <input
                    type="tel"
                    name="no_telepon_penerima"
                    class="form-control"
                    placeholder="Contoh: 081234567890"
                >
            </div>

            <div class="col-12">
                <label class="form-label">Alamat Lengkap Penerima <span>*</span></label>
                <textarea
                    name="alamat_penerima"
                    class="form-control"
                    rows="3"
                    placeholder="Masukkan alamat lengkap penerima"
                ></textarea>
            </div>
        </div>
    </div>

    {{-- INFORMASI PENGIRIMAN --}}
    <div class="panel package-panel">
        <div class="package-section-heading">
            <div>
                <span class="section-number">03</span>
                <div>
                    <h3>Informasi Pengiriman</h3>
                    <p>Tentukan tujuan dan spesifikasi paket.</p>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Kota Tujuan <span>*</span></label>
                <input
                    type="text"
                    id="kota_tujuan"
                    name="kota_tujuan"
                    class="form-control"
                    placeholder="Masukkan kota tujuan"
                    autocomplete="off"
                >
                <small class="form-help">
                    Zona pengiriman akan ditentukan melalui layanan Pusat Wilayah.
                </small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Zona Pengiriman</label>
                <input
                    type="text"
                    id="zona_tampilan"
                    class="form-control"
                    value="Menunggu kota tujuan"
                    readonly
                >
                <small id="zona_status" class="form-help">
                    Masukkan kota tujuan untuk memeriksa zona.
                </small>
            </div>

            {{-- PILIHAN ZONA MANUAL --}}
            <div class="col-md-6" id="pilihan_zona_manual" hidden>
                <label class="form-label">Pilih Zona Manual <span>*</span></label>
                <select
                    id="zona_manual_pilihan"
                    name="zona_manual_pilihan"
                    class="form-select"
                    disabled
                >
                    <option value="">Pilih zona pengiriman</option>

                    @foreach ($tarifs as $tarif)
                        <option value="{{ $tarif->zona }}">
                            Zona {{ $tarif->zona }} - {{ $tarif->nama_zona }}
                        </option>
                    @endforeach
                </select>

                <small class="form-help">
                    Pilih zona secara manual karena layanan Pusat Wilayah
                    tidak dapat menentukan zona tujuan.
                </small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Berat Paket (kg) <span>*</span></label>
                <input
                    type="number"
                    name="berat"
                    class="form-control"
                    min="0.01"
                    step="0.01"
                    placeholder="Contoh: 2.5"
                >
            </div>

            <div class="col-md-6">
                <label class="form-label">Jenis Layanan <span>*</span></label>
                <select name="layanan" class="form-select">
                    <option value="">Pilih layanan</option>
                    <option value="Reguler">Reguler</option>
                    <option value="Express">Express</option>
                </select>
            </div>
        </div>
    </div>

    {{-- PEMBAYARAN COD --}}
    <div class="panel package-panel">
        <div class="package-section-heading">
            <div>
                <span class="section-number">04</span>
                <div>
                    <h3>Informasi Pembayaran</h3>
                    <p>Tentukan apakah paket menggunakan layanan COD.</p>
                </div>
            </div>
        </div>

        <div class="cod-option">
            <label class="cod-check">
                <input type="checkbox" id="gunakan_cod">
                <span>
                    <strong>Gunakan layanan COD</strong>
                    <small>Penerima membayar nilai barang saat paket diterima.</small>
                </span>
            </label>
        </div>

        <div class="row g-3 mt-1" id="cod_fields" hidden>
            <div class="col-md-6">
                <label class="form-label">Nilai COD (Rp) <span>*</span></label>
                <input
                    type="number"
                    name="nilai_cod"
                    id="nilai_cod"
                    class="form-control"
                    min="1"
                    step="1"
                    placeholder="Masukkan nilai COD"
                    disabled
                >
            </div>

            <div class="col-md-6">
                <label class="form-label">Biaya Layanan COD</label>
                <input
                    type="text"
                    id="biaya_cod_preview"
                    class="form-control"
                    value="Dihitung otomatis"
                    readonly
                >
            </div>
        </div>
    </div>

    {{-- RINCIAN BIAYA --}}
    <div class="panel package-panel cost-panel">
        <div class="package-section-heading">
            <div>
                <span class="section-number">05</span>
                <div>
                    <h3>Rincian Pengiriman</h3>
                    <p>Ringkasan biaya pengiriman paket.</p>
                </div>
            </div>
        </div>

        <div class="cost-row">
            <span>Ongkos Kirim</span>
            <strong id="ongkir_preview">Menunggu data pengiriman</strong>
        </div>

        <div class="cost-row">
            <span>Biaya COD</span>
            <strong id="cod_preview">Rp0</strong>
        </div>

        <div class="cost-total">
            <span>Total Biaya</span>
            <strong id="total_preview">Menunggu perhitungan</strong>
        </div>

        <div class="package-note">
            Berat dihitung dengan pembulatan ke atas dan minimum 1 kg.
            Layanan Express dikenakan tambahan 50%.
            Biaya COD sebesar 2% dengan minimum Rp2.000.
        </div>
    </div>

    {{-- TOMBOL --}}
    <div class="package-actions">
        <a href="{{ route('admin.dashboard') }}" class="cancel-button">
            Batal
        </a>

        <button
            type="button"
            class="primary-button"
            disabled
            title="Fitur simpan akan diaktifkan pada tahap integrasi database"
        >
            Simpan Paket
        </button>
    </div>

</form>

@endsection

{{-- ========================================
     STYLE
======================================== --}}
@push('styles')
<style>
    .package-panel {
        margin-bottom: 20px;
        padding: 25px;
    }

    .package-section-heading {
        margin-bottom: 23px;
    }

    .package-section-heading > div {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .section-number {
        width: 38px;
        height: 38px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 10px;
        background: #f5e8d8;
        color: #a96825;
        font-weight: 700;
        font-size: 13px;
    }

    .package-section-heading h3 {
        margin: 0 0 5px;
        font-size: 15px;
        font-weight: 700;
    }

    .package-section-heading p {
        margin: 0;
        color: var(--muted);
        font-size: 11px;
    }

    .form-label {
        color: var(--brown);
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .form-label span {
        color: #bd583e;
    }

    .form-control,
    .form-select {
        min-height: 44px;
        border: 1px solid var(--line);
        border-radius: 8px;
        background-color: #fffdfa;
        color: var(--brown);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 12px;
    }

    textarea.form-control {
        min-height: 100px;
        padding-top: 12px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(217, 138, 53, .12);
    }

    .form-control[readonly] {
        background: #f7f2eb;
        color: var(--muted);
    }

    .form-help {
        display: block;
        margin-top: 7px;
        color: var(--muted);
        font-size: 10px;
    }

    .package-note {
        margin-top: 18px;
        padding: 12px 14px;
        border-radius: 8px;
        background: #f8f3eb;
        color: var(--muted);
        font-size: 11px;
        line-height: 1.7;
    }

    .cod-option {
        padding: 15px;
        background: #f8f3eb;
        border-radius: 9px;
    }

    .cod-check {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
    }

    .cod-check input {
        width: 17px;
        height: 17px;
        accent-color: var(--orange);
    }

    .cod-check strong,
    .cod-check small {
        display: block;
    }

    .cod-check strong {
        font-size: 12px;
    }

    .cod-check small {
        margin-top: 4px;
        color: var(--muted);
        font-size: 10px;
    }

    .cost-panel {
        background: #fffaf3;
    }

    .cost-row,
    .cost-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 14px 0;
        border-bottom: 1px solid var(--line);
        font-size: 12px;
    }

    .cost-row span {
        color: var(--muted);
    }

    .cost-row strong {
        color: var(--brown);
    }

    .cost-total {
        border: none;
        font-weight: 700;
        font-size: 14px;
    }

    .cost-total strong {
        color: #a96825;
        font-size: 18px;
    }

    .package-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 25px;
        margin-bottom: 15px;
    }

    .cancel-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 20px;
        border: 1px solid var(--line);
        border-radius: 8px;
        color: var(--brown);
        background: white;
        font-size: 12px;
        font-weight: 700;
    }

    .package-actions button:disabled {
        opacity: .6;
        cursor: not-allowed;
    }

    @media (max-width: 650px) {
        .package-panel {
            padding: 18px;
        }

        .package-actions {
            flex-direction: column-reverse;
        }

        .package-actions a,
        .package-actions button {
            width: 100%;
        }
    }
</style>
@endpush

{{-- ========================================
     JAVASCRIPT
======================================== --}}

@push('scripts')
<script>
    // ========================================
    // DATA TARIF DARI DATABASE
    // ========================================

    const tarifPerZona = @json(
        $tarifs->pluck('tarif_per_kg', 'zona')
    );

    // ========================================
    // DATA PENGIRIM
    // ========================================

    const pengirimSelect = document.getElementById('pengirim_id');
    const teleponPengirim = document.getElementById('telepon_pengirim');

    pengirimSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        teleponPengirim.value = selected.dataset.telepon || '';
    });


    // ========================================
    // ELEMEN FORM PENGIRIMAN
    // ========================================

    const kotaTujuan = document.getElementById('kota_tujuan');
    const zonaTampilan = document.getElementById('zona_tampilan');
    const zonaStatus = document.getElementById('zona_status');

    const pilihanZonaManual = document.getElementById('pilihan_zona_manual');
    const zonaManual = document.getElementById('zona_manual_pilihan');

    const inputBerat = document.querySelector('[name="berat"]');
    const pilihanLayanan = document.querySelector('[name="layanan"]');

    const ongkirPreview = document.getElementById('ongkir_preview');
    const codPreview = document.getElementById('cod_preview');
    const totalPreview = document.getElementById('total_preview');


    // ========================================
    // FORMAT RUPIAH
    // ========================================

    function formatRupiah(angka) {
        return 'Rp' + Math.ceil(angka).toLocaleString('id-ID');
    }


    // ========================================
    // PEMBAYARAN COD
    // ========================================

    const codCheckbox = document.getElementById('gunakan_cod');
    const codFields = document.getElementById('cod_fields');
    const nilaiCod = document.getElementById('nilai_cod');

    codCheckbox.addEventListener('change', function () {
        codFields.hidden = !this.checked;
        nilaiCod.disabled = !this.checked;
        nilaiCod.required = this.checked;

        if (!this.checked) {
            nilaiCod.value = '';

            document.getElementById('biaya_cod_preview').value =
                'Dihitung otomatis';
        }

        hitungBiaya();
    });

    nilaiCod.addEventListener('input', hitungBiaya);


    // ========================================
    // VARIABEL ZONA AKTIF
    // ========================================

    let zonaAktif = null;
    let waktuPengecekan;
    let nomorPermintaan = 0;


    // ========================================
    // RESET PILIHAN ZONA MANUAL
    // ========================================

    function sembunyikanZonaManual() {
        pilihanZonaManual.hidden = true;
        zonaManual.disabled = true;
        zonaManual.required = false;
        zonaManual.value = '';
    }


    // ========================================
    // ZONA MANUAL
    // ========================================

    function aktifkanZonaManual(pesan) {
        zonaAktif = null;

        zonaTampilan.value = 'Ditentukan secara manual';
        zonaStatus.textContent = pesan;

        pilihanZonaManual.hidden = false;
        zonaManual.disabled = false;
        zonaManual.required = true;

        hitungBiaya();
    }

    zonaManual.addEventListener('change', function () {
        zonaAktif = this.value ? Number(this.value) : null;
        hitungBiaya();
    });


    // ========================================
    // CEK ZONA MELALUI API
    // ========================================

    async function cekZona(kota, nomorSaatIni) {
        try {
            const response = await fetch(
                `{{ route('admin.packages.check-zone') }}?kota=${encodeURIComponent(kota)}`,
                {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            if (!response.ok) {
                throw new Error('Layanan tidak tersedia.');
            }

            const hasil = await response.json();

            // Abaikan respons lama.
            if (nomorSaatIni !== nomorPermintaan) {
                return;
            }

            if (
                hasil.tersedia &&
                [1, 2, 3].includes(Number(hasil.zona))
            ) {
                zonaAktif = Number(hasil.zona);

                zonaTampilan.value = `Zona ${zonaAktif}`;
                zonaStatus.textContent = hasil.pesan;

                sembunyikanZonaManual();

                hitungBiaya();

            } else {
                aktifkanZonaManual(hasil.pesan);
            }

        } catch (error) {
            if (nomorSaatIni !== nomorPermintaan) {
                return;
            }

            aktifkanZonaManual(
                'Layanan Pusat Wilayah tidak dapat dihubungi. Silakan pilih zona manual.'
            );
        }
    }


    // ========================================
    // INPUT KOTA TUJUAN
    // ========================================

    kotaTujuan.addEventListener('input', function () {
        clearTimeout(waktuPengecekan);

        nomorPermintaan++;
        zonaAktif = null;

        const kota = this.value.trim();

        if (kota.length === 0) {
            zonaTampilan.value = 'Menunggu kota tujuan';

            zonaStatus.textContent =
                'Masukkan kota tujuan untuk memeriksa zona.';

            sembunyikanZonaManual();
            hitungBiaya();

            return;
        }

        zonaTampilan.value = 'Memeriksa...';

        zonaStatus.textContent =
            'Menghubungi layanan Pusat Wilayah...';

        sembunyikanZonaManual();
        hitungBiaya();

        const nomorSaatIni = nomorPermintaan;

        waktuPengecekan = setTimeout(() => {
            cekZona(kota, nomorSaatIni);
        }, 500);
    });


    // ========================================
    // PERHITUNGAN ONGKOS KIRIM
    // ========================================

    function hitungBiaya() {

        const berat = Number(inputBerat.value) || 0;
        const layanan = pilihanLayanan.value;

        let ongkir = null;
        let beratTagihan = 0;

        // Menghitung berat dengan pembulatan ke atas.
        if (berat > 0) {
            beratTagihan = Math.max(1, Math.ceil(berat));
        }

        // Mengambil tarif berdasarkan zona.
        if (
            zonaAktif !== null &&
            beratTagihan > 0 &&
            tarifPerZona[zonaAktif] !== undefined &&
            layanan !== ''
        ) {
            const tarif = Number(tarifPerZona[zonaAktif]);

            ongkir = beratTagihan * tarif;

            // Express dikenakan tambahan 50%.
            if (layanan === 'Express') {
                ongkir = ongkir * 1.5;
            }
        }

        // Menampilkan ongkos kirim.
        if (ongkir !== null) {
            ongkirPreview.textContent = formatRupiah(ongkir);
        } else {
            ongkirPreview.textContent = 'Menunggu data pengiriman';
        }


        // ========================================
        // PERHITUNGAN BIAYA COD
        // ========================================

        let biayaCod = 0;

        if (codCheckbox.checked) {
            const nilai = Number(nilaiCod.value) || 0;

            if (nilai > 0) {
                biayaCod = Math.max(nilai * 0.02, 2000);
                biayaCod = Math.ceil(biayaCod);
            }
        }

        codPreview.textContent = formatRupiah(biayaCod);

        document.getElementById('biaya_cod_preview').value =
            codCheckbox.checked
                ? formatRupiah(biayaCod)
                : 'Dihitung otomatis';


        // ========================================
        // TOTAL BIAYA
        // ========================================

        if (ongkir !== null) {
            const total = ongkir + biayaCod;

            totalPreview.textContent = formatRupiah(total);
        } else {
            totalPreview.textContent = 'Menunggu perhitungan';
        }
    }


    // ========================================
    // PERBARUI PREVIEW SAAT INPUT BERUBAH
    // ========================================

    inputBerat.addEventListener('input', hitungBiaya);
    pilihanLayanan.addEventListener('change', hitungBiaya);

</script>
@endpush

