
@extends('layouts.manager')

@section('content')
<div class="page-content">

    <div class="page-heading">
        <div>
            <span class="eyebrow">PENGELOLAAN</span>
            <h1>Tarif Pengiriman</h1>
            <p>Kelola tarif pengiriman berdasarkan zona tujuan.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Zona</th>
                        <th>Nama Zona</th>
                        <th>Tarif per Kg</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($tarifs as $tarif)
                        <tr>
                            <td>{{ $tarif->zona }}</td>
                            <td>{{ $tarif->nama_zona }}</td>

                            <td>
                                <form
                                    id="form-tarif-{{ $tarif->id }}"
                                    action="{{ route('manager.shipping-rates.update', $tarif->id) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('PUT')

                                    <input
                                        type="number"
                                        name="tarif_per_kg"
                                        value="{{ old('tarif_per_kg', $tarif->tarif_per_kg) }}"
                                        min="1"
                                        required
                                    >
                                </form>
                            </td>

                            <td>
                                <button
                                    type="submit"
                                    form="form-tarif-{{ $tarif->id }}"
                                    class="primary-button"
                                >
                                    Simpan
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
