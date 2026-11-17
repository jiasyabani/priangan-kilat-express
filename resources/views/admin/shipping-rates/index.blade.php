
@extends('layouts.admin')

@section('title', 'Tarif Pengiriman')

@section('content')
    <div class="page-heading">
        <div>
            <small class="eyebrow">PRIANGAN KILAT EXPRESS</small>
            <h1>Tarif Pengiriman</h1>
        </div>
    </div>

    <div class="shipping-rate-section">
        <div class="shipping-rate-header">
            <div>
                <h5>Daftar Tarif Pengiriman</h5>
                <p>Informasi tarif pengiriman berdasarkan zona tujuan.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table shipping-rate-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Zona</th>
                        <th>Nama Zona</th>
                        <th>Tarif per Kg</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tarifs as $index => $tarif)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>Zona {{ $tarif->zona }}</td>
                            <td>{{ $tarif->nama_zona }}</td>
                            <td>
                                Rp {{ number_format((float) $tarif->tarif_per_kg, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                Belum ada data tarif pengiriman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="shipping-rate-footer">
            Total {{ $tarifs->count() }} zona pengiriman
        </div>
    </div>
@endsection
