
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    {{-- Heading dashboard dan tombol pintas untuk input paket baru. --}}
    <div class="page-heading">
        <div>
            <small class="eyebrow">RINGKASAN OPERASIONAL</small>
            <h1>Dashboard Admin</h1>
            <p>Pantau aktivitas pengiriman Priangan Kilat Express.</p>
        </div>

        {{-- Tombol menuju halaman input paket. --}}
        <a class="primary-button" href="{{ route('admin.packages.create') }}">
            + Input Paket
        </a>
    </div>

    {{-- Kartu statistik menggunakan ikon PNG transparan. --}}
    {{-- Angka berikut masih berupa data contoh dan nantinya diganti query database. --}}
    <div class="stats">

        {{-- Statistik jumlah paket hari ini. --}}
        <article class="stat-card">
            <span class="stat-icon">
                <img
                    src="{{ asset('assets/icons/paket.png') }}"
                    alt="Ikon Paket"
                >
            </span>
            <p>Paket Hari Ini</p>
            <strong>128</strong>
        </article>

        {{-- Statistik paket yang sedang dalam pengiriman. --}}
        <article class="stat-card">
            <span class="stat-icon">
                <img
                    src="{{ asset('assets/icons/transit.png') }}"
                    alt="Ikon Transit"
                >
            </span>
            <p>Dalam Pengiriman</p>
            <strong>46</strong>
        </article>

        {{-- Statistik paket yang berhasil terkirim. --}}
        <article class="stat-card">
            <span class="stat-icon">
                <img
                    src="{{ asset('assets/icons/terkirim.png') }}"
                    alt="Ikon Terkirim"
                >
            </span>
            <p>Berhasil Terkirim</p>
            <strong>72</strong>
        </article>

        {{-- Statistik paket yang mengalami kegagalan pengiriman. --}}
        <article class="stat-card">
            <span class="stat-icon">
                <img
                    src="{{ asset('assets/icons/gagal-kirim.png') }}"
                    alt="Ikon Gagal Kirim"
                >
            </span>
            <p>Gagal Kirim</p>
            <strong>10</strong>
        </article>

    </div>

    {{-- Tabel ringkasan aktivitas paket terbaru. --}}
    <section class="panel">

        {{-- Judul dan tautan menuju seluruh data paket. --}}
        <div class="panel-heading">
            <div>
                <h3>Aktivitas Paket Terbaru</h3>
                <p>Contoh ringkasan data pengiriman.</p>
            </div>

            <a href="{{ route('admin.packages.index') }}">
                Lihat semua
            </a>
        </div>

        {{-- Tabel informasi paket terbaru. --}}
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No. Resi</th>
                        <th>Penerima</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- Data contoh paket pertama. --}}
                    <tr>
                        <td>PKE-261112-0001</td>
                        <td>Andi Saputra</td>
                        <td>Bandung</td>
                        <td>
                            <span class="pill transit">Transit</span>
                        </td>
                    </tr>

                    {{-- Data contoh paket kedua. --}}
                    <tr>
                        <td>PKE-261112-0002</td>
                        <td>Siti Rahma</td>
                        <td>Garut</td>
                        <td>
                            <span class="pill sent">Dikirim</span>
                        </td>
                    </tr>

                    {{-- Data contoh paket ketiga. --}}
                    <tr>
                        <td>PKE-261112-0003</td>
                        <td>Dewi Lestari</td>
                        <td>Jakarta</td>
                        <td>
                            <span class="pill success">Terkirim</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </section>

@endsection
