
@extends('layouts.admin')

@section('title', 'Setoran COD')

@section('content')
    <div class="page-heading cod-page">
        <div>
            <small class="eyebrow">PRIANGAN KILAT EXPRESS</small>
            <h1>Pencatatan Setoran COD</h1>
        </div>
    </div>

    {{-- Pesan berhasil --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan kesalahan --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Daftar paket COD yang belum disetorkan --}}
    <div class="cod-table-panel">
        <div class="cod-table-heading">
            <div>
                <h2>Paket COD Belum Disetorkan</h2>
                <p>Daftar paket COD yang sudah terkirim dan belum dicatat setorannya.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="cod-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Resi</th>
                        <th>Nama Penerima</th>
                        <th>Kota Tujuan</th>
                        <th>Nilai COD</th>
                        <th>Status Setoran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pakets as $paket)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $paket->no_resi }}</td>
                            <td>{{ $paket->nama_penerima }}</td>
                            <td>{{ $paket->kota_tujuan }}</td>
                            <td>
                                Rp {{ number_format((float) $paket->nilai_cod, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="cod-status-pending">
                                    Belum Disetor
                                </span>
                            </td>
                            <td>
                                <form
                                    action="{{ route('admin.cod.store') }}"
                                    method="POST"
                                    onsubmit="return confirm('Catat setoran COD untuk paket {{ $paket->no_resi }}?')"
                                >
                                    @csrf
                                    <input
                                        type="hidden"
                                        name="paket_id"
                                        value="{{ $paket->id }}"
                                    >

                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Setorkan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="cod-empty">
                                Belum ada paket COD yang perlu disetorkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Riwayat setoran COD --}}
    <div class="cod-table-panel cod-section">
        <div class="cod-table-heading">
            <div>
                <h2>Riwayat Setoran COD</h2>
                <p>Daftar pencatatan setoran COD yang telah dilakukan admin.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="cod-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Resi</th>
                        <th>Jumlah Setoran</th>
                        <th>Tanggal Setoran</th>
                        <th>Admin</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($setorans as $setoran)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $setoran->paket?->no_resi ?? '-' }}</td>
                            <td>
                                Rp {{ number_format((float) $setoran->jumlah_setoran, 0, ',', '.') }}
                            </td>
                            <td>
                                {{ $setoran->tanggal_setoran
                                    ? $setoran->tanggal_setoran->format('d/m/Y H:i')
                                    : '-' }}
                            </td>
                            <td>{{ $setoran->admin?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="cod-empty">
                                Belum ada riwayat setoran COD.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
