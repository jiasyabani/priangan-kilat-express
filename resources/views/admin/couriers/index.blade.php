
@extends('layouts.admin')

@section('title', 'Data Kurir')

@section('content')

    {{-- Judul halaman --}}
    <div class="page-heading">
        <div>
            <small class="eyebrow">PRIANGAN KILAT EXPRESS</small>
            <h1>Data Kurir & Akun Kurir</h1>
        </div>
    </div>

    {{-- Tabel data kurir --}}
    <div class="panel package-table-panel">

        <div class="panel-heading">
            <div>
                <h3>Daftar Kurir</h3>
                <p>Data kurir dan akun yang terdaftar dalam sistem.</p>
            </div>

            {{-- Tombol tambah kurir --}}
           <a href="{{ route('admin.couriers.create') }}" class="btn-courier-primary">
    + Tambah Kurir
</a>
        </div>

        {{-- Notifikasi berhasil --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Notifikasi gagal --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Password sementara --}}
        @if (session('password_sementara'))
            <div class="alert alert-success">
                <strong>Password sementara:</strong>
                {{ session('password_sementara') }}
            </div>
        @endif

        <div class="table-responsive">

            <table class="package-table">

                {{-- Header tabel --}}
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Kurir</th>
                        <th>Nama Kurir</th>
                        <th>No. Telepon</th>
                        <th>Email Akun</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    {{-- Menampilkan data kurir dari database --}}
                    @forelse ($kurirs as $index => $kurir)

                        <tr>
                            <td>
                                {{ $kurirs->firstItem() + $index }}
                            </td>

                            <td>
                                <strong>{{ $kurir->kode_kurir }}</strong>
                            </td>

                            <td>
                                {{ $kurir->user->name }}
                            </td>

                            <td>
                                {{ $kurir->no_telepon }}
                            </td>

                            <td>
                                {{ $kurir->user->email }}
                            </td>

                            <td>
                                @if ($kurir->aktif)
                                    <span class="courier-status active">
                                        Aktif
                                    </span>
                                @else
                                    <span class="courier-status inactive">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- Tombol pengelolaan kurir --}}
                            <td>
                                <div class="action-buttons">

                                    <a
                                        href="{{ route('admin.couriers.edit', $kurir->id) }}"
                                        class="btn-edit"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.couriers.reset-password', $kurir->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Reset password akun kurir ini?')"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn-reset">
                                            Reset Password
                                        </button>
                                    </form>

                                    @if ($kurir->aktif)
                                        <form
                                            action="{{ route('admin.couriers.deactivate', $kurir->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Nonaktifkan kurir ini?')"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn-delete">
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>

                    @empty

                        {{-- Pesan jika data kurir belum tersedia --}}
                        <tr>
                            <td colspan="7" class="empty-table">
                                Data kurir belum tersedia.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination nomor halaman --}}
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

    </div>

@endsection
