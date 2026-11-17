
@extends('layouts.admin')

@section('title', 'Tambah Kurir')

@section('content')

    {{-- Judul halaman --}}
    <div class="page-heading">
        <div>
            <small class="eyebrow">PRIANGAN KILAT EXPRESS</small>
            <h1>Tambah Kurir</h1>
        </div>
    </div>

    {{-- Form tambah kurir --}}
    <div class="panel package-table-panel">
        <div class="panel-heading">
            <div>
                <h3>Form Data Kurir</h3>
                <p>Masukkan data kurir dan informasi akun yang akan dibuat.</p>
            </div>
        </div>

        <form action="{{ route('admin.couriers.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Kurir</label>
                <input
                    type="text"
                    name="nama"
                    id="nama"
                    class="form-control"
                    value="{{ old('nama') }}"
                    required
                >
                @error('nama')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Akun</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="kode_kurir" class="form-label">Kode Kurir</label>
                <input
                    type="text"
                    name="kode_kurir"
                    id="kode_kurir"
                    class="form-control"
                    value="{{ old('kode_kurir') }}"
                    required
                >
                @error('kode_kurir')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="no_telepon" class="form-label">Nomor Telepon</label>
                <input
                    type="text"
                    name="no_telepon"
                    id="no_telepon"
                    class="form-control"
                    value="{{ old('no_telepon') }}"
                    required
                >
                @error('no_telepon')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Tombol form --}}
            {{-- Tombol form --}}
<div class="courier-form-actions">
    <button type="submit" class="btn-courier-primary">
        Simpan Kurir
    </button>

    <a href="{{ route('admin.couriers.index') }}" class="btn-courier-back">
        Kembali
    </a>
</div>
        </form>
    </div>

@endsection
