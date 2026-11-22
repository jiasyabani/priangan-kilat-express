
@extends('layouts.manager')

@section('title', 'Laporan Pengiriman')

@section('content')

    {{-- Heading --}}
    <div class="page-heading">
        <div>
            <small class="eyebrow">ANALISIS OPERASIONAL</small>
            <h1>Laporan Pengiriman</h1>
            <p>Pantau rekapitulasi dan status seluruh pengiriman paket.</p>
        </div>
    </div>

    {{-- Filter Periode --}}
    <div class="panel" style="margin-bottom:22px;">
        <div class="panel-heading">
            <h3>Filter Periode Laporan</h3>
        </div>

        <form method="GET" action="{{ route('manager.reports.index') }}"
            style="display:flex; flex-wrap:wrap; gap:15px; align-items:end;">

            <div>
                <label for="tanggal_awal"
                    style="display:block; font-size:11px; margin-bottom:7px;">
                    Tanggal Awal
                </label>

                <input
                    type="date"
                    name="tanggal_awal"
                    id="tanggal_awal"
                    value="{{ $tanggalAwal }}"
                    required
                    style="padding:10px; border:1px solid #eee5da; border-radius:7px;"
                >
            </div>

            <div>
                <label for="tanggal_akhir"
                    style="display:block; font-size:11px; margin-bottom:7px;">
                    Tanggal Akhir
                </label>

                <input
                    type="date"
                    name="tanggal_akhir"
                    id="tanggal_akhir"
                    value="{{ $tanggalAkhir }}"
                    required
                    style="padding:10px; border:1px solid #eee5da; border-radius:7px;"
                >
            </div>

            <button type="submit" class="primary-button">
                Terapkan Filter
            </button>

            <a href="{{ route('manager.reports.index') }}"
                class="filter-button">
                Reset
            </a>

        </form>

        @if ($errors->any())
            <p style="color:#b33c32; font-size:12px; margin-top:12px;">
                {{ $errors->first() }}
            </p>
        @endif
    </div>

    {{-- Ringkasan Total Paket --}}
    <div class="stats">

        <article class="stat-card">
            <span class="stat-icon">
                <img src="{{ asset('assets/icons/paket.png') }}" alt="Paket">
            </span>
            <p>Total Paket Masuk</p>
            <strong>{{ $totalPaket }}</strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">
                <img src="{{ asset('assets/icons/transit.png') }}" alt="Diterima">
            </span>
            <p>Diterima</p>
            <strong>{{ $statusPaket['Diterima'] }}</strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">
                <img src="{{ asset('assets/icons/transit.png') }}" alt="Transit">
            </span>
            <p>Transit</p>
            <strong>{{ $statusPaket['Transit'] }}</strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">
                <img src="{{ asset('assets/icons/transit.png') }}" alt="Dikirim">
            </span>
            <p>Dikirim</p>
            <strong>{{ $statusPaket['Dikirim'] }}</strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">
                <img src="{{ asset('assets/icons/terkirim.png') }}" alt="Terkirim">
            </span>
            <p>Terkirim</p>
            <strong>{{ $statusPaket['Terkirim'] }}</strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">
                <img src="{{ asset('assets/icons/gagal-kirim.png') }}" alt="Gagal">
            </span>
            <p>Gagal Kirim</p>
            <strong>{{ $statusPaket['Gagal Kirim'] }}</strong>
        </article>

    </div>

    {{-- Grafik Status Pengiriman --}}
    <section class="panel" style="margin-bottom:22px;">

        <div class="panel-heading">
            <div>
                <h3>Grafik Status Pengiriman</h3>
                <p>Perbandingan jumlah paket berdasarkan status.</p>
            </div>
        </div>

        @php
            $warnaStatus = [
                'Diterima' => '#8b6749',
                'Transit' => '#6b9ca8',
                'Dikirim' => '#d98a35',
                'Terkirim' => '#62966b',
                'Gagal Kirim' => '#c65b4c',
                'Retur' => '#887d73',
            ];

            $maksStatus = max(1, $grafikStatus->max('total') ?? 0);
        @endphp

        @foreach ($grafikStatus as $data)

            <div style="margin-bottom:17px;">

                <div style="display:flex; justify-content:space-between; margin-bottom:7px; font-size:11px;">
                    <strong>{{ $data['status'] }}</strong>
                    <span>{{ $data['total'] }} paket</span>
                </div>

                <div style="height:13px; background:#f1e8dc; border-radius:10px; overflow:hidden;">

                    <div style="
                        height:100%;
                        width:{{ ($data['total'] / $maksStatus) * 100 }}%;
                        background:{{ $warnaStatus[$data['status']] }};
                        border-radius:10px;
                    "></div>

                </div>

            </div>

        @endforeach

    </section>

    {{-- Tabel Rekap Pengiriman --}}
    <section class="panel">

        <div class="panel-heading">
            <div>
                <h3>Rekap Data Pengiriman</h3>
                <p>
                    Total {{ $totalPaket }} paket pada periode
                    {{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }}
                    sampai
                    {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}.
                </p>
            </div>
        </div>

        <div class="table-responsive">

            <table>
                <thead>
                    <tr>
                        <th>No. Resi</th>
                        <th>Penerima</th>
                        <th>Tujuan</th>
                        <th>Zona</th>
                        <th>Tanggal Masuk</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($pakets as $paket)

                        <tr>
                            <td>
                                <strong>{{ $paket->no_resi }}</strong>
                            </td>

                            <td>{{ $paket->nama_penerima }}</td>

                            <td>{{ $paket->kota_tujuan }}</td>

                            <td>{{ $paket->zona }}</td>

                            <td>
                                {{ $paket->tanggal_masuk?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td>
                                <span class="pill
                                    @if ($paket->status === 'Terkirim')
                                        success
                                    @elseif ($paket->status === 'Transit' || $paket->status === 'Dikirim')
                                        transit
                                    @elseif ($paket->status === 'Gagal Kirim' || $paket->status === 'Retur')
                                        sent
                                    @else
                                        transit
                                    @endif
                                ">
                                    {{ $paket->status }}
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" style="text-align:center; padding:30px;">
                                Tidak ada data pengiriman pada periode ini.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

        {{-- Pagination --}}
        @if ($pakets->hasPages())
            <div class="package-pagination" style="margin-top:20px;">
                {{ $pakets->links() }}
            </div>
        @endif

    </section>

@endsection
