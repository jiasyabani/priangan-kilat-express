
@extends('layouts.manager')

@section('title', 'Dashboard Manager')

@section('content')

    {{-- Heading Dashboard --}}
    <div class="page-heading">
        <div>
            <small class="eyebrow">RINGKASAN OPERASIONAL</small>
            <h1>Dashboard Manager</h1>
            <p>Pantau aktivitas pengiriman dan pendapatan Priangan Kilat Express.</p>
        </div>
    </div>

    {{-- Statistik Operasional --}}
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
                <img src="{{ asset('assets/icons/terkirim.png') }}" alt="Terkirim">
            </span>
            <p>Paket Terkirim</p>
            <strong>{{ $paketTerkirim }}</strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">
                <img src="{{ asset('assets/icons/transit.png') }}" alt="Transit">
            </span>
            <p>Paket Transit</p>
            <strong>{{ $paketTransit }}</strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">
                <img src="{{ asset('assets/icons/gagal-kirim.png') }}" alt="Gagal Kirim">
            </span>
            <p>Paket Gagal</p>
            <strong>{{ $paketGagal }}</strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">Rp</span>
            <p>Total Pendapatan</p>
            <strong>Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">COD</span>
            <p>COD Belum Disetor</p>
            <strong>Rp{{ number_format($codBelumDisetor, 0, ',', '.') }}</strong>
        </article>

    </div>

    {{-- Grafik Pengiriman Mingguan --}}
    <section class="panel" style="margin-bottom: 22px;">

        <div class="panel-heading">
            <div>
                <h3>Pengiriman Mingguan</h3>
                <p>Jumlah paket masuk selama 7 hari terakhir.</p>
            </div>
        </div>

        @php
            $maksPengiriman = max(1, $grafikPengiriman->max('total') ?? 0);
        @endphp

        @forelse ($grafikPengiriman as $data)
            <div style="margin-bottom: 15px;">
                <div style="display:flex; justify-content:space-between; margin-bottom:6px; font-size:11px;">
                    <span>{{ \Carbon\Carbon::parse($data->tanggal)->format('d M Y') }}</span>
                    <strong>{{ $data->total }} paket</strong>
                </div>

                <div style="height:12px; background:#f1e8dc; border-radius:10px; overflow:hidden;">
                    <div style="height:100%; width:{{ ($data->total / $maksPengiriman) * 100 }}%; background:#d98a35; border-radius:10px;"></div>
                </div>
            </div>
        @empty
            <p>Belum ada data pengiriman dalam 7 hari terakhir.</p>
        @endforelse

    </section>

    {{-- Grafik Pendapatan dan Distribusi Zona --}}
    <div style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:20px; margin-bottom:22px;">

        <section class="panel">
            <div class="panel-heading">
                <div>
                    <h3>Pendapatan Berdasarkan Zona</h3>
                    <p>Ongkir dan biaya COD setiap zona.</p>
                </div>
            </div>

            @php
                $maksPendapatan = max(1, $grafikPendapatan->max('total') ?? 0);
            @endphp

            @forelse ($grafikPendapatan as $data)
                <div style="margin-bottom:18px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:7px; font-size:11px;">
                        <strong>{{ $data->zona }}</strong>
                        <span>Rp{{ number_format($data->total, 0, ',', '.') }}</span>
                    </div>

                    <div style="height:12px; background:#f1e8dc; border-radius:10px; overflow:hidden;">
                        <div style="height:100%; width:{{ ($data->total / $maksPendapatan) * 100 }}%; background:#d98a35; border-radius:10px;"></div>
                    </div>
                </div>
            @empty
                <p>Belum ada data pendapatan.</p>
            @endforelse
        </section>

        <section class="panel">
            <div class="panel-heading">
                <div>
                    <h3>Distribusi Paket per Zona</h3>
                    <p>Jumlah paket berdasarkan wilayah tujuan.</p>
                </div>
            </div>

            @php
                $maksZona = max(1, $distribusiZona->max('total') ?? 0);
            @endphp

            @forelse ($distribusiZona as $data)
                <div style="margin-bottom:18px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:7px; font-size:11px;">
                        <strong>{{ $data->zona }}</strong>
                        <span>{{ $data->total }} paket</span>
                    </div>

                    <div style="height:12px; background:#f1e8dc; border-radius:10px; overflow:hidden;">
                        <div style="height:100%; width:{{ ($data->total / $maksZona) * 100 }}%; background:#8b6749; border-radius:10px;"></div>
                    </div>
                </div>
            @empty
                <p>Belum ada data distribusi paket.</p>
            @endforelse
        </section>

    </div>

@endsection
