
@extends('layouts.admin')

@section('title', 'Detail Paket')

@section('content')

<div class="page-heading">
    <div>
        <small class="eyebrow">PRIANGAN KILAT EXPRESS</small>
        <h1>Detail Paket</h1>
        <p>Informasi lengkap pengiriman paket.</p>
    </div>

    <a href="{{ route('admin.packages.index') }}" class="primary-button">
        Kembali
    </a>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3>Informasi Pengiriman</h3>
    </div>

    <table>
        <tr>
            <th>No. Resi</th>
            <td>{{ $paket->no_resi }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $paket->status }}</td>
        </tr>
        <tr>
            <th>Nama Penerima</th>
            <td>{{ $paket->nama_penerima }}</td>
        </tr>
        <tr>
            <th>No. Telepon Penerima</th>
            <td>{{ $paket->no_telepon_penerima }}</td>
        </tr>
        <tr>
            <th>Alamat Penerima</th>
            <td>{{ $paket->alamat_penerima }}</td>
        </tr>
        <tr>
            <th>Kota Tujuan</th>
            <td>{{ $paket->kota_tujuan }}</td>
        </tr>
        <tr>
            <th>Zona</th>
            <td>{{ $paket->zona }}</td>
        </tr>
        <tr>
            <th>Berat</th>
            <td>{{ $paket->berat }} kg</td>
        </tr>
        <tr>
            <th>Layanan</th>
            <td>{{ $paket->layanan }}</td>
        </tr>
        <tr>
            <th>Tarif per Kg</th>
            <td>Rp{{ number_format($paket->tarif_per_kg, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Ongkos Kirim</th>
            <td>Rp{{ number_format($paket->ongkir, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Nilai COD</th>
            <td>Rp{{ number_format($paket->nilai_cod, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Biaya COD</th>
            <td>Rp{{ number_format($paket->biaya_cod, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Kurir</th>
            <td>{{ $paket->kurir?->kode_kurir ?? 'Belum ditugaskan' }}</td>
        </tr>
        <tr>
            <th>Jumlah Gagal Kirim</th>
            <td>{{ $paket->jumlah_gagal }}</td>
        </tr>
        <tr>
            <th>Tanggal Masuk</th>
            <td>{{ $paket->tanggal_masuk?->format('d-m-Y H:i') ?? '-' }}</td>
        </tr>
    </table>
</div>

@endsection
