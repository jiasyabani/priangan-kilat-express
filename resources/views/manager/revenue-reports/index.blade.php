
@extends('layouts.manager')

@section('title', 'Laporan Pendapatan')

@section('content')

    <div class="page-heading">
        <div>
            <small class="eyebrow">ANALISIS KEUANGAN</small>
            <h1>Laporan Pendapatan</h1>
            <p>Pantau pendapatan ongkos kirim dan biaya COD berdasarkan zona.</p>
        </div>
    </div>

    {{-- Filter Periode --}}
    <section class="panel" style="margin-bottom:22px;">

        <div class="panel-heading">
            <h3>Filter Periode</h3>
        </div>

        <form method="GET"
            action="{{ route('manager.revenue-reports.index') }}"
            style="display:flex; flex-wrap:wrap; gap:15px; align-items:end;">

            <div>
                <label for="tanggal_awal"
                    style="display:block; font-size:11px; margin-bottom:7px;">
                    Tanggal Awal
                </label>

                <input type="date"
                    name="tanggal_awal"
                    id="tanggal_awal"
                    value="{{ $tanggalAwal }}"
                    required
                    style="padding:10px; border:1px solid #eee5da; border-radius:7px;">
            </div>

            <div>
                <label for="tanggal_akhir"
                    style="display:block; font-size:11px; margin-bottom:7px;">
                    Tanggal Akhir
                </label>

                <input type="date"
                    name="tanggal_akhir"
                    id="tanggal_akhir"
                    value="{{ $tanggalAkhir }}"
                    required
                    style="padding:10px; border:1px solid #eee5da; border-radius:7px;">
            </div>

            <button type="submit" class="primary-button">
                Terapkan Filter
            </button>

            <a href="{{ route('manager.revenue-reports.index') }}"
                class="filter-button">
                Reset
            </a>

        </form>

        @if ($errors->any())
            <p style="color:#b33c32; font-size:12px; margin-top:12px;">
                {{ $errors->first() }}
            </p>
        @endif

    </section>

    {{-- Ringkasan Pendapatan --}}
    <div class="stats">

        <article class="stat-card">
            <span class="stat-icon">Rp</span>
            <p>Total Pendapatan</p>
            <strong>Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">Z1</span>
            <p>Pendapatan Zona 1</p>
            <strong>
                Rp{{ number_format($pendapatanZona->firstWhere('zona', '1')->total ?? 0, 0, ',', '.') }}
            </strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">Z2</span>
            <p>Pendapatan Zona 2</p>
            <strong>
                Rp{{ number_format($pendapatanZona->firstWhere('zona', '2')->total ?? 0, 0, ',', '.') }}
            </strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">Z3</span>
            <p>Pendapatan Zona 3</p>
            <strong>
                Rp{{ number_format($pendapatanZona->firstWhere('zona', '3')->total ?? 0, 0, ',', '.') }}
            </strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">O</span>
            <p>Total Ongkos Kirim</p>
            <strong>Rp{{ number_format($totalOngkir, 0, ',', '.') }}</strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">COD</span>
            <p>Total Biaya COD</p>
            <strong>Rp{{ number_format($totalBiayaCod, 0, ',', '.') }}</strong>
        </article>

    </div>

    {{-- Grafik Pendapatan per Zona --}}
    <section class="panel" style="margin-bottom:22px;">

        <div class="panel-heading">
            <div>
                <h3>Grafik Pendapatan per Zona</h3>
                <p>Perbandingan pendapatan setiap zona pengiriman.</p>
            </div>
        </div>

        @php
            $namaZona = [
                '1' => 'Zona 1 - Bandung Raya',
                '2' => 'Zona 2 - Jawa Barat',
                '3' => 'Zona 3 - Luar Jawa Barat',
            ];

            $maksPendapatan = max(1, $pendapatanZona->max('total') ?? 0);
        @endphp

        @foreach (['1', '2', '3'] as $zona)

            @php
                $dataZona = $pendapatanZona->firstWhere('zona', $zona);
                $totalZona = $dataZona->total ?? 0;
            @endphp

            <div style="margin-bottom:20px;">

                <div style="display:flex; justify-content:space-between; margin-bottom:7px; font-size:11px;">
                    <strong>{{ $namaZona[$zona] }}</strong>
                    <span>Rp{{ number_format($totalZona, 0, ',', '.') }}</span>
                </div>

                <div style="height:14px; background:#f1e8dc; border-radius:10px; overflow:hidden;">

                    <div style="
                        height:100%;
                        width:{{ ($totalZona / $maksPendapatan) * 100 }}%;
                        background:#d98a35;
                        border-radius:10px;
                    "></div>

                </div>

            </div>

        @endforeach

    </section>

    {{-- Tabel Rincian Pendapatan --}}
    <section class="panel">

        <div class="panel-heading">
            <div>
                <h3>Rincian Pendapatan</h3>
                <p>Detail transaksi paket pada periode yang dipilih.</p>
            </div>
        </div>

        <div class="table-responsive">

            <table>
                <thead>
                    <tr>
                        <th>No. Resi</th>
                        <th>Zona</th>
                        <th>Layanan</th>
                        <th>Ongkos Kirim</th>
                        <th>Biaya COD</th>
                        <th>Total</th>
                        <th>Tanggal Masuk</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($pakets as $paket)

                        <tr>
                            <td><strong>{{ $paket->no_resi }}</strong></td>
                            <td>Z{{ $paket->zona }}</td>
                            <td>{{ $paket->layanan }}</td>

                            <td>
                                Rp{{ number_format($paket->ongkir, 0, ',', '.') }}
                            </td>

                            <td>
                                Rp{{ number_format($paket->biaya_cod, 0, ',', '.') }}
                            </td>

                            <td>
                                <strong>
                                    Rp{{ number_format($paket->ongkir + $paket->biaya_cod, 0, ',', '.') }}
                                </strong>
                            </td>

                            <td>
                                {{ $paket->tanggal_masuk?->format('d/m/Y') ?? '-' }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" style="text-align:center; padding:30px;">
                                Belum ada transaksi pada periode ini.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

        @if ($pakets->hasPages())
            <div class="package-pagination" style="margin-top:20px;">
                {{ $pakets->links() }}
            </div>
        @endif

    </section>

@endsection
  