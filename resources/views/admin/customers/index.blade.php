
@extends('layouts.admin')

@section('title', 'Data Pelanggan')

@section('content')

    {{-- Judul halaman Data Pelanggan --}}
    <div class="page-heading">
        <div>
            <small class="eyebrow">PRIANGAN KILAT EXPRESS</small>
            <h1>Data Pelanggan</h1>
        </div>
    </div>

    {{-- Panel utama tabel pelanggan --}}
    <div class="panel package-table-panel">

        {{-- Header tabel --}}
        <div class="panel-heading">
            <div>
                <h3>Daftar Pelanggan</h3>
                <p>Data pelanggan yang tersimpan dalam sistem.</p>
            </div>

            {{-- Menampilkan jumlah seluruh pelanggan --}}
            <span class="customer-total">
                {{ $pelanggans->total() }} Pelanggan
            </span>
        </div>

        {{-- Membungkus tabel agar responsif --}}
        <div class="table-responsive">

            <table class="package-table">

                {{-- Kepala tabel --}}
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Pelanggan</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Kota</th>
                    </tr>
                </thead>

                {{-- Isi tabel --}}
                <tbody>

                    {{-- Mengulang data pelanggan dari database --}}
                    @forelse ($pelanggans as $index => $pelanggan)

                        <tr>

                            {{-- Nomor urut data --}}
                            <td>
                                {{ $pelanggans->firstItem() + $index }}
                            </td>

                            {{-- Nama pelanggan --}}
                            <td>
                                <strong>{{ $pelanggan->nama }}</strong>
                            </td>

                            {{-- Nomor telepon --}}
                            <td>
                                {{ $pelanggan->no_telepon }}
                            </td>

                            {{-- Alamat pelanggan --}}
                            <td>
                                {{ $pelanggan->alamat ?? '—' }}
                            </td>

                            {{-- Kota pelanggan --}}
                            <td>
                                {{ $pelanggan->kota ?? '—' }}
                            </td>

                        </tr>

                    @empty

                        {{-- Pesan jika data pelanggan kosong --}}
                        <tr>
                            <td colspan="5" class="empty-table">
                                Data pelanggan belum tersedia.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination mengikuti desain halaman Data Paket --}}
        @if ($pelanggans->hasPages())

            <div class="package-pagination">

                {{-- Menampilkan nomor halaman --}}
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

    </div>

@endsection
