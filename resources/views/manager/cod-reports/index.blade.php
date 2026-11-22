
@extends('layouts.manager')

@section('title', 'Rekap COD')

@section('content')

    <div class="page-heading">
        <div>
            <small class="eyebrow">MONITORING KEUANGAN</small>
            <h1>Rekap COD</h1>
            <p>Pantau setoran COD dari seluruh kurir Priangan Kilat Express.</p>
        </div>
    </div>

    {{-- Ringkasan COD --}}
    <div class="stats">

        <article class="stat-card">
            <span class="stat-icon">COD</span>
            <p>Total COD Terkirim</p>
            <strong>
                Rp{{ number_format($totalCodTerkirim, 0, ',', '.') }}
            </strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">✓</span>
            <p>COD Sudah Disetor</p>
            <strong>
                Rp{{ number_format($totalCodDisetor, 0, ',', '.') }}
            </strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">!</span>
            <p>COD Belum Disetor</p>
            <strong>
                Rp{{ number_format($totalCodBelumDisetor, 0, ',', '.') }}
            </strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">P</span>
            <p>Jumlah Paket COD</p>
            <strong>{{ number_format($jumlahPaketCod, 0, ',', '.') }}</strong>
        </article>

    </div>

    {{-- Grafik Perbandingan COD --}}
    <section class="panel" style="margin-bottom:22px;">

        <div class="panel-heading">
            <div>
                <h3>Perbandingan Setoran COD</h3>
                <p>Perbandingan nilai COD yang sudah dan belum disetorkan.</p>
            </div>
        </div>

        @php
            $maksCod = max(1, $totalCodTerkirim);
            $persenDisetor = ($totalCodDisetor / $maksCod) * 100;
            $persenBelumDisetor = ($totalCodBelumDisetor / $maksCod) * 100;
        @endphp

        <div style="margin-bottom:22px;">

            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:12px;">
                <strong>Sudah Disetor</strong>
                <span>Rp{{ number_format($totalCodDisetor, 0, ',', '.') }}</span>
            </div>

            <div style="height:16px; background:#f1e8dc; border-radius:10px; overflow:hidden;">
                <div style="
                    width:{{ $persenDisetor }}%;
                    height:100%;
                    background:#548b68;
                    border-radius:10px;
                "></div>
            </div>

        </div>

        <div>

            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:12px;">
                <strong>Belum Disetor</strong>
                <span>Rp{{ number_format($totalCodBelumDisetor, 0, ',', '.') }}</span>
            </div>

            <div style="height:16px; background:#f1e8dc; border-radius:10px; overflow:hidden;">
                <div style="
                    width:{{ $persenBelumDisetor }}%;
                    height:100%;
                    background:#d98a35;
                    border-radius:10px;
                "></div>
            </div>

        </div>

    </section>

    {{-- Rekap COD per Kurir --}}
    <section class="panel">

        <div class="panel-heading">
            <div>
                <h3>Rincian COD per Kurir</h3>
                <p>Ringkasan tanggung jawab setoran COD setiap kurir.</p>
            </div>
        </div>

        <div class="table-responsive">

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Kurir</th>
                        <th>Jumlah Paket</th>
                        <th>Total COD</th>
                        <th>Sudah Disetor</th>
                        <th>Belum Disetor</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($rekapKurir as $item)

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <strong>
                                    {{ $item['kurir']->user->name ?? 'Kurir tidak ditemukan' }}
                                </strong>
                            </td>

                            <td>{{ $item['jumlah_paket'] }}</td>

                            <td>
                                Rp{{ number_format($item['total_cod'], 0, ',', '.') }}
                            </td>

                            <td>
                                Rp{{ number_format($item['cod_disetor'], 0, ',', '.') }}
                            </td>

                            <td>
                                Rp{{ number_format($item['cod_belum_disetor'], 0, ',', '.') }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" style="text-align:center; padding:30px;">
                                Belum ada data COD yang berhasil terkirim.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

    </section>

@endsection
