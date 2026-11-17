
@extends('layouts.admin')

@section('title', 'Penugasan Kurir')

@section('content')

<div class="page-heading">
    <div>
        <small class="eyebrow">PRIANGAN KILAT EXPRESS</small>
        <h1>Penugasan & Pengiriman Ulang</h1>
        <p>Kelola penugasan paket kepada kurir dan pantau kapasitas pengiriman.</p>
    </div>
</div>

{{-- Pesan berhasil --}}
@if(session('success'))
    <div class="assignment-alert success-alert">
        {{ session('success') }}
    </div>
@endif

{{-- Pesan kesalahan --}}
@if($errors->any())
    <div class="assignment-alert error-alert">
        {{ $errors->first() }}
    </div>
@endif

{{-- DAFTAR PAKET --}}
<div class="panel assignment-panel">

    <div class="panel-heading">
        <div>
            <h3>Daftar Paket</h3>
            <p>Paket yang menunggu penugasan, dalam pengiriman, atau perlu dikirim ulang.</p>
        </div>

        <span class="assignment-total">
            {{ $pakets->total() }} Paket
        </span>
    </div>

    <div class="assignment-table-wrapper">
        <table class="assignment-table">
            <thead>
                <tr>
                    <th>No. Resi</th>
                    <th>Tujuan</th>
                    <th>Status</th>
                    <th>Kurir</th>
                    <th>Gagal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pakets as $paket)
                    @php
                        $bisaDitugaskan = $paket->status === 'Transit';

                        $bisaDikirimUlang = $paket->status === 'Gagal Kirim'
                            && $paket->jumlah_gagal < 2;

                        $bisaMemilihKurir = $bisaDitugaskan || $bisaDikirimUlang;
                    @endphp

                    <tr>
                        <td>
                            <strong>{{ $paket->no_resi }}</strong>
                        </td>

                        <td>{{ $paket->kota_tujuan }}</td>

                        <td>
                            @if($paket->status === 'Transit')
                                <span class="assignment-status transit-status">
                                    Transit
                                </span>
                            @elseif($paket->status === 'Gagal Kirim')
                                <span class="assignment-status failed-status">
                                    Gagal Kirim
                                </span>
                            @else
                                <span class="assignment-status sent-status">
                                    Dikirim
                                </span>
                            @endif
                        </td>

                        <td>
                            {{ $paket->kurir?->kode_kurir ?? '—' }}
                        </td>

                        <td>{{ $paket->jumlah_gagal }}</td>

                        <td>
                            @if($bisaMemilihKurir)
                                <form
                                    action="{{ route('admin.assignments.store', $paket->id) }}"
                                    method="POST"
                                    class="assignment-form"
                                >
                                    @csrf

                                    <select name="kurir_id" required>
                                        <option value="">Pilih kurir</option>

                                        @foreach($kurirs as $kurir)
                                            @if($kurir->jumlah_dikirim < 20)
                                                <option value="{{ $kurir->id }}">
                                                    {{ $kurir->kode_kurir }}
                                                    ({{ $kurir->jumlah_dikirim }}/20)
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                    <button type="submit" class="assign-button">
                                        {{ $bisaDikirimUlang ? 'Kirim Ulang' : 'Tugaskan' }}
                                    </button>
                                </form>
                            @elseif($paket->status === 'Gagal Kirim')
                                <span class="assignment-detail">
                                    Batas gagal tercapai
                                </span>
                            @else
                                <span class="assignment-detail">
                                    Sedang dikirim
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="assignment-empty">
                            Belum ada paket yang perlu ditugaskan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($pakets->hasPages())
        <div class="package-pagination">
            @for($page = 1; $page <= $pakets->lastPage(); $page++)
                <a
                    href="{{ $pakets->url($page) }}"
                    class="{{ $pakets->currentPage() == $page ? 'active' : '' }}"
                >
                    {{ $page }}
                </a>
            @endfor
        </div>
    @endif

</div>

{{-- PANEL KAPASITAS DAN ATURAN --}}
<div class="assignment-bottom">

    <div class="panel courier-capacity-panel">
        <div class="panel-heading">
            <div>
                <h3>Kapasitas Kurir</h3>
                <p>Pantau jumlah paket yang sedang dibawa kurir aktif.</p>
            </div>
        </div>

        @forelse($kurirs as $kurir)
            <div class="courier-capacity-item">

                <div class="courier-capacity-info">
                    <strong>{{ $kurir->kode_kurir }}</strong>
                    <span>
                        {{ $kurir->jumlah_dikirim }}/20 paket
                    </span>
                </div>

                <div class="capacity-bar">
                    <div
                        class="capacity-progress"
                        style="width: {{ min(($kurir->jumlah_dikirim / 20) * 100, 100) }}%"
                    ></div>
                </div>

                <small>
                    @if($kurir->jumlah_dikirim >= 20)
                        Kapasitas penuh
                    @else
                        Tersedia {{ 20 - $kurir->jumlah_dikirim }} slot
                    @endif
                </small>

            </div>
        @empty
            <p class="assignment-empty">Belum ada kurir aktif.</p>
        @endforelse
    </div>

    <div class="panel capacity-rule-panel">
        <h3>Aturan Kapasitas</h3>

        <div class="rule-icon">!</div>

        <p>
            Penugasan ditolak jika kurir sudah membawa
            <strong>20 paket berstatus Dikirim</strong>.
            Kurir nonaktif tidak dapat dipilih untuk menerima paket.
        </p>

        <div class="rule-note">
            Paket Gagal Kirim hanya dapat dikirim ulang
            selama jumlah kegagalannya belum mencapai 2 kali.
        </div>
    </div>

</div>

@endsection
