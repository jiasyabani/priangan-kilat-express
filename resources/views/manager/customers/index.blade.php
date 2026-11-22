
@extends('layouts.manager')

@section('title', 'Data Pelanggan')

@section('content')

    {{-- PAGE HEADING --}}
    <div class="page-heading">
        <div>
            <small class="eyebrow">MANAJEMEN DATA</small>
            <h1>Data Pelanggan</h1>
            <p>Informasi pelanggan Priangan Kilat Express.</p>
        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="stats">

        <article class="stat-card">
            <span class="stat-icon">P</span>
            <p>Total Pelanggan</p>
            <strong>
                {{ number_format($totalPelanggan, 0, ',', '.') }}
            </strong>
        </article>

        <article class="stat-card">
            <span class="stat-icon">D</span>
            <p>Data Ditampilkan</p>
            <strong>
                {{ number_format($pelanggans->total(), 0, ',', '.') }}
            </strong>
        </article>

    </div>

    {{-- TABEL PELANGGAN --}}
    <section class="panel">

        <div class="panel-heading">
            <div>
                <h3>Daftar Pelanggan</h3>
                <p>Data pelanggan yang terdaftar dalam sistem.</p>
            </div>
        </div>

        {{-- PENCARIAN --}}
        <form method="GET"
            action="{{ route('manager.customers.index') }}"
            style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:20px;">

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari nama, nomor telepon, atau kota..."
                style="flex:1; min-width:220px; padding:11px 14px; border:1px solid #eee5da; border-radius:8px;"
            >

            <button type="submit" class="primary-button">
                Cari
            </button>

            @if ($search)
                <a href="{{ route('manager.customers.index') }}"
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
                        <th>Nama Pelanggan</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Kota</th>
                        <th>Total Paket</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($pelanggans as $pelanggan)

                        <tr>
                            <td>
                                {{ $pelanggans->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>{{ $pelanggan->nama }}</strong>
                            </td>

                            <td>{{ $pelanggan->no_telepon }}</td>

                            <td>{{ $pelanggan->alamat }}</td>

                            <td>{{ $pelanggan->kota }}</td>

                            <td>
                                <strong>{{ $pelanggan->pakets_count }}</strong>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6"
                                style="text-align:center; padding:30px;">
                                @if ($search)
                                    Pelanggan dengan kata kunci tersebut tidak ditemukan.
                                @else
                                    Belum ada data pelanggan.
                                @endif
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

        {{-- PAGINATION --}}
       
@if ($pelanggans->hasPages())
    <div class="package-pagination">
        @for ($page = 1; $page <= $pelanggans->lastPage(); $page++)
            <a
                href="{{ $pelanggans->url($page) }}"
                class="{{ $pelanggans->currentPage() == $page ? 'active' : '' }}"
            >
                {{ $page }}
            </a>
        @endfor
    </div>
@endif


    </section>

@endsection
