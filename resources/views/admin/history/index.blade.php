
@extends('layouts.admin')

@section('title', 'Riwayat Status Paket')

@section('content')

<div class="page-heading">
    <div>
        <small class="eyebrow">PRIANGAN KILAT EXPRESS</small>
        <h1>Riwayat Status Paket</h1>
        <p>Daftar perubahan status paket yang tercatat dalam sistem.</p>
    </div>
</div>

<div class="panel assignment-panel">

    <div class="panel-heading">
        <div>
            <h3>Daftar Riwayat</h3>
            <p>Informasi perubahan status dan aktivitas pengiriman paket.</p>
        </div>

        <span class="assignment-total">
            {{ $riwayats->total() }} Riwayat
        </span>
    </div>

    <div class="p-3">
        <form method="GET" action="{{ route('admin.history.index') }}">
            <div class="d-flex gap-2">
                <input
                    type="text"
                    name="cari"
                    class="form-control"
                    placeholder="Cari nomor resi..."
                    value="{{ request('cari') }}"
                >

                <button type="submit" class="btn btn-primary">
                    Cari
                </button>

                @if(request('cari'))
                    <a
                        href="{{ route('admin.history.index') }}"
                        class="btn btn-secondary"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="assignment-table-wrapper">
        <table class="assignment-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No. Resi</th>
                    <th>Status Sebelumnya</th>
                    <th>Status Baru</th>
                    <th>Pelaku</th>
                    <th>Catatan</th>
                    <th>Waktu Perubahan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($riwayats as $index => $riwayat)
                    <tr>
                        <td>
                            {{ $riwayats->firstItem() + $index }}
                        </td>

                        <td>
                            <strong>
                                {{ $riwayat->paket?->no_resi ?? '—' }}
                            </strong>
                        </td>

                        <td>
                            {{ $riwayat->status_sebelumnya ?? '—' }}
                        </td>

                        <td>
                            <strong>{{ $riwayat->status_baru }}</strong>
                        </td>

                        <td>
                            {{ $riwayat->pelaku }}
                        </td>

                        <td>
                            {{ $riwayat->catatan ?? '—' }}
                        </td>

                        <td>
                            {{ $riwayat->waktu_perubahan?->format('d/m/Y H:i') ?? '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="assignment-empty">
                            Belum ada riwayat perubahan status paket.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($riwayats->hasPages())
        <div class="package-pagination">
            @for($page = 1; $page <= $riwayats->lastPage(); $page++)
                <a
                    href="{{ $riwayats->url($page) }}"
                    class="{{ $riwayats->currentPage() == $page ? 'active' : '' }}"
                >
                    {{ $page }}
                </a>
            @endfor
        </div>
    @endif

</div>

@endsection
