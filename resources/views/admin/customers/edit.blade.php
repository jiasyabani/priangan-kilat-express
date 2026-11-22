
@extends('layouts.admin')

@section('title', 'Edit Pelanggan')

@section('content')

<div class="page-heading">
    <div>
        <small class="eyebrow">PRIANGAN KILAT EXPRESS</small>
        <h1>Edit Pelanggan</h1>
    </div>
</div>

<div class="panel package-table-panel">
    <div class="panel-heading">
        <div>
            <h3>Perbarui Data Pelanggan</h3>
            <p>Ubah informasi pelanggan yang tersimpan dalam sistem.</p>
        </div>
    </div>

    <form
        action="{{ route('admin.customers.update', $pelanggan->id) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        <div class="p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Data belum berhasil diperbarui.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Pelanggan</label>
                <input
                    type="text"
                    name="nama"
                    id="nama"
                    class="form-control"
                    value="{{ old('nama', $pelanggan->nama) }}"
                    maxlength="150"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="no_telepon" class="form-label">Nomor Telepon</label>
                <input
                    type="text"
                    name="no_telepon"
                    id="no_telepon"
                    class="form-control"
                    value="{{ old('no_telepon', $pelanggan->no_telepon) }}"
                    maxlength="20"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea
                    name="alamat"
                    id="alamat"
                    class="form-control"
                    rows="3"
                >{{ old('alamat', $pelanggan->alamat) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="kota" class="form-label">Kota</label>
                <input
                    type="text"
                    name="kota"
                    id="kota"
                    class="form-control"
                    value="{{ old('kota', $pelanggan->kota) }}"
                    maxlength="100"
                >
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 p-4 pt-0">
            <a href="{{ route('admin.customers.index') }}"
               class="btn btn-secondary">
                Batal
            </a>

            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection
