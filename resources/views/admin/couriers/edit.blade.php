
@extends('layouts.admin')

@section('title', 'Edit Kurir')

@section('content')

    {{-- Judul halaman --}}
    <div class="page-heading courier-form-heading">
        <div>
            <small class="eyebrow">PRIANGAN KILAT EXPRESS</small>
            <h1>Edit Data Kurir</h1>
        </div>
    </div>

    {{-- Form edit kurir --}}
   <div class="panel package-table-panel courier-form-panel">
        <div class="panel-heading">
            <div>
                <h3>Form Edit Kurir</h3>
                <p>Perbarui data kurir dan informasi akun.</p>
            </div>
        </div>

        <form
            action="{{ route('admin.couriers.update', $kurir->id) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Kurir</label>
                <input
                    type="text"
                    name="nama"
                    id="nama"
                    class="form-control"
                    value="{{ old('nama', $kurir->user->name) }}"
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
                    value="{{ old('email', $kurir->user->email) }}"
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
                    value="{{ old('kode_kurir', $kurir->kode_kurir) }}"
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
                    value="{{ old('no_telepon', $kurir->no_telepon) }}"
                    required
                >
                @error('no_telepon')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

           {{-- Tombol form --}}
<div class="courier-form-actions">
    <button type="submit" class="btn-courier-primary">
        Simpan Perubahan
    </button>

    <a href="{{ route('admin.couriers.index') }}" class="btn-courier-back">
        Kembali
    </a>
</div>
        </form>
    </div>

@endsection

