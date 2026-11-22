
@extends('layouts.admin')

@section('title', 'Data Pelanggan')

@section('content')

<div class="page-heading">
    <div>
        <small class="eyebrow">PRIANGAN KILAT EXPRESS</small>
        <h1>Data Pelanggan</h1>
    </div>

    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
        + Tambah Pelanggan
    </a>
</div>

<div class="panel package-table-panel">

    <div class="panel-heading">
        <div>
            <h3>Daftar Pelanggan</h3>
            <p>Data pelanggan yang tersimpan dalam sistem.</p>
        </div>

        <span class="customer-total">
            {{ $pelanggans->total() }} Pelanggan
        </span>
    </div>

    <div class="p-3">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

    </div>

    <div class="table-responsive">
        <table class="package-table">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pelanggan</th>
                    <th>No. Telepon</th>
                    <th>Alamat</th>
                    <th>Kota</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($pelanggans as $index => $pelanggan)
                    <tr>
                        <td>
                            {{ $pelanggans->firstItem() + $index }}
                        </td>

                        <td>
                            <strong>{{ $pelanggan->nama }}</strong>
                        </td>

                        <td>
                            {{ $pelanggan->no_telepon }}
                        </td>

                        <td>
                            {{ $pelanggan->alamat ?? '—' }}
                        </td>

                        <td>
                            {{ $pelanggan->kota ?? '—' }}
                        </td>

                        <td>
                            <a
                                href="{{ route('admin.customers.edit', $pelanggan->id) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-table">
                            Data pelanggan belum tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

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

</div>

@endsection
