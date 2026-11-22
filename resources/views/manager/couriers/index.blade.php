
@extends('layouts.manager')

@section('title', 'Data Kurir')

@section('content')

    {{-- JUDUL HALAMAN --}}
    <div class="page-heading">
        <div>
            <small class="eyebrow">MANAJEMEN DATA</small>
            <h1>Data Kurir</h1>
            <p>Informasi kurir Priangan Kilat Express.</p>
        </div>
    </div>

    {{-- STATISTIK KURIR --}}
    <div class="stats">

        <article class="stat-card">
            <span class="stat-icon">K</span>
            <p>Total Kurir</p>
            <strong>{{ number_format($totalKurir, 0, ',', '.') }}</strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">A</span>
            <p>Kurir Aktif</p>
            <strong>{{ number_format($kurirAktif, 0, ',', '.') }}</strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">N</span>
            <p>Kurir Nonaktif</p>
            <strong>{{ number_format($kurirNonaktif, 0, ',', '.') }}</strong>
        </article>

    </div>

    {{-- TABEL DATA KURIR --}}
    <section class="panel">

        <div class="panel-heading">
            <div>
                <h3>Daftar Kurir</h3>
                <p>Data kurir yang terdaftar dalam sistem.</p>
            </div>

            <span class="customer-total">
                {{ $kurirs->total() }} Kurir
            </span>
        </div>

        {{-- PENCARIAN --}}
        <form method="GET"
            action="{{ route('manager.couriers.index') }}"
            style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:20px;">

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari nama, kode, atau nomor telepon kurir..."
                style="flex:1; min-width:220px; padding:11px 14px; border:1px solid #eee5da; border-radius:8px;"
            >

            <button type="submit" class="primary-button">
                Cari
            </button>

            @if ($search)
                <a href="{{ route('manager.couriers.index') }}"
                    class="filter-button">
                    Reset
                </a>
            @endif

        </form>

        {{-- TABEL --}}
        <div class="table-responsive">

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Kurir</th>
                        <th>Kode Kurir</th>
                        <th>No. Telepon</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($kurirs as $index => $kurir)

                        <tr>
                            <td>
                                {{ $kurirs->firstItem() + $index }}
                            </td>

                            <td>
                                <strong>
                                    {{ $kurir->user->name ?? 'Nama tidak tersedia' }}
                                </strong>
                            </td>

                            <td>{{ $kurir->kode_kurir }}</td>

                            <td>{{ $kurir->no_telepon }}</td>

                            <td>
                                @if ($kurir->aktif)
                                    <span class="pill success">Aktif</span>
                                @else
                                    <span class="pill transit">Nonaktif</span>
                                @endif
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5"
                                style="text-align:center; padding:30px;">
                                @if ($search)
                                    Data kurir tidak ditemukan.
                                @else
                                    Belum ada data kurir.
                                @endif
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

        {{-- PAGINATION SAMA SEPERTI ADMIN --}}
        @if ($kurirs->hasPages())
            <div class="package-pagination">
                @for ($page = 1; $page <= $kurirs->lastPage(); $page++)
                    <a
                        href="{{ $kurirs->url($page) }}"
                        class="{{ $kurirs->currentPage() == $page ? 'active' : '' }}"
                    >
                        {{ $page }}
                    </a>
                @endfor
            </div>
        @endif

    </section>

@endsection
