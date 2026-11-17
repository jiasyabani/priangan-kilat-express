
@extends('layouts.admin')

@section('title', 'Data Paket')

@section('content')

    {{-- Judul halaman --}}
    <div class="page-heading">
        <div>
            <small class="eyebrow">PRIANGAN KILAT EXPRESS</small>
            <h1>Pengelolaan Paket</h1>
            <p>Kelola status pengiriman dan penugasan kurir.</p>
        </div>
    </div>

    {{-- Pencarian dan filter paket --}}
    <div class="panel package-toolbar">

        <form action="{{ route('admin.packages.index') }}" method="GET"
            class="package-search">

            <input
                type="text"
                name="cari"
                value="{{ $pencarian }}"
                placeholder="Cari nomor resi..."
                aria-label="Cari nomor resi"
            >

            <button type="submit" class="search-button">
                Cari
            </button>

            <a href="{{ route('admin.packages.index') }}"
                class="filter-button {{ !$statusAktif ? 'active' : '' }}">
                Semua Status
            </a>

            <a href="{{ route('admin.packages.index', ['status' => 'Transit', 'cari' => $pencarian]) }}"
                class="filter-button transit-filter {{ $statusAktif === 'Transit' ? 'active' : '' }}">
                Transit
            </a>

            <a href="{{ route('admin.packages.index', ['status' => 'Dikirim', 'cari' => $pencarian]) }}"
                class="filter-button sent-filter {{ $statusAktif === 'Dikirim' ? 'active' : '' }}">
                Dikirim
            </a>

        </form>

        <a href="{{ route('admin.packages.create') }}" class="primary-button">
            + Input Paket
        </a>

    </div>


    {{-- Tabel data paket --}}
    <div class="panel package-table-panel">

        <div class="table-responsive">

            <table class="package-table">

                <thead>
                    <tr>
                        <th>No. Resi</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                        <th>Kurir</th>
                        <th>Gagal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($pakets as $paket)

                        <tr>

                            <td>
                                <strong>{{ $paket->no_resi }}</strong>

                                @if ($paket->perlu_verifikasi)
                                    <small class="verification-label">
                                        Perlu verifikasi berat
                                    </small>
                                @endif
                            </td>

                            <td>
                                {{ $paket->kota_tujuan }}
                            </td>

                            <td>
                                <span class="package-status
                                    @switch($paket->status)
                                        @case('Diterima') status-received @break
                                        @case('Transit') status-transit @break
                                        @case('Dikirim') status-sent @break
                                        @case('Terkirim') status-delivered @break
                                        @case('Gagal Kirim') status-failed @break
                                        @case('Retur') status-returned @break
                                    @endswitch
                                ">
                                    {{ $paket->status }}
                                </span>
                            </td>

                            <td>
                                {{ $paket->kurir?->kode_kurir ?? '—' }}
                            </td>

                            <td>
                                {{ $paket->jumlah_gagal }}
                            </td>

                            <td>
                                <span class="action-label">
                                    @if ($paket->status === 'Diterima')
                                        Proses
                                    @elseif ($paket->status === 'Transit')
                                        Tugaskan
                                    @elseif ($paket->status === 'Dikirim')
                                        Detail
                                    @elseif ($paket->status === 'Gagal Kirim')
                                        @if ($paket->jumlah_gagal < 2)
                                            Kirim ulang
                                        @else
                                            Retur
                                        @endif
                                    @else
                                        Detail
                                    @endif
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="empty-table">
                                Data paket tidak ditemukan.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

      @if ($pakets->hasPages())
    <div class="package-pagination">
        @for ($page = 1; $page <= $pakets->lastPage(); $page++)
            <a
                href="{{ $pakets->url($page) }}"
                class="{{ $pakets->currentPage() == $page ? 'active' : '' }}"
            >
                {{ $page }}
            </a>
        @endfor
    </div>
@endif


    {{-- Panel penugasan dan aturan kapasitas --}}
    <div class="package-bottom-grid">

        <div class="panel assignment-panel">

            <div class="panel-heading">
                <h3>Panel Penugasan</h3>
            </div>

            @forelse ($kurirs as $kurir)

                <div class="courier-capacity">

                    <span>{{ $kurir->kode_kurir }}</span>

                    <strong>
                        {{ $kurir->jumlah_dikirim }}/20 paket
                    </strong>

                </div>

            @empty

                <p class="empty-courier">
                    Belum ada kurir aktif.
                </p>

            @endforelse

        </div>


        <div class="panel capacity-panel">

            <div class="panel-heading">
                <h3>Aturan Kapasitas</h3>
            </div>

            <p>
                Penugasan ditolak jika kurir sudah membawa
                <strong>20 paket berstatus Dikirim</strong>.
                Kurir nonaktif tidak dapat ditugaskan.
            </p>

        </div>

    </div>

@endsection
