@extends('layout.admin')

@section('page_caption', 'Verifikasi Pengembalian')
@section('page_heading', 'Admin SBUM')

@section('admin_content')
<style>
    .banner-card {
        background-color: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.5rem;
    }
    .detail-card, .decision-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
    }
    .info-row {
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    .info-label {
        width: 160px;
        color: var(--text-muted);
        font-weight: 500;
        font-size: 0.9rem;
    }
    .info-value {
        color: var(--text-main);
        font-weight: 600;
        font-size: 0.9rem;
    }
    .checklist-item {
        background-color: #f7f6f2;
        border: 1px solid #dfd4c8;
        border-radius: 0.75rem;
        padding: 0.85rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    .badge-menunggu {
        background-color: #fcf1d3;
        color: #7d6006;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.5rem 1.5rem;
        border-radius: 2rem;
        display: inline-block;
        width: 100%;
        text-align: center;
    }
    .btn-verify-done {
        background-color: var(--primary-main);
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: none;
        width: 100%;
        transition: 0.2s;
    }
    .btn-verify-done:hover {
        background-color: var(--primary-dark);
    }
    .btn-verify-check {
        background-color: #fcf1d3;
        color: #7d6006;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: 1px solid #f9e2ae;
        width: 100%;
        transition: 0.2s;
    }
    .btn-verify-check:hover {
        background-color: #fce8bc;
    }
    .btn-verify-alert {
        background-color: #c95b50;
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: none;
        width: 100%;
        transition: 0.2s;
    }
    .btn-verify-alert:hover {
        background-color: #b34e44;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Admin memverifikasi fasilitas yang dikembalikan</h2>
        <p class="mb-0 text-secondary text-wrap">
            Admin memeriksa apakah fasilitas sesuai data peminjaman dan apakah kondisi akhir dinyatakan baik atau perlu tindak lanjut.
        </p>
    </div>
</div>

@php
    $selectedId = request()->query('selected_id');
    $kategori = request()->query('kategori', 'ruangan');
    $selectedItem = null;
    if ($selectedId) {
        $selectedItem = $pengembalian->first(function($item) use ($selectedId, $kategori) {
            return $item->id_pengembalian == $selectedId && $item->kategori == $kategori;
        });
    }
    if (!$selectedItem) {
        $selectedItem = $pengembalian->first();
    }
@endphp

<div class="row g-4">
    <!-- Left Column: Details & Checklist -->
    <div class="col-lg-8">
        <form action="{{ route('admin.verifikasi-pengembalian.verifikasi', $selectedItem ? $selectedItem->id_pengembalian : 1) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="kategori" value="{{ $selectedItem ? $selectedItem->kategori : 'ruangan' }}">
            <input type="hidden" name="status_keputusan" id="keputusanField" value="disetujui">

            <div class="mb-3 fw-semibold text-secondary">Detail Pengembalian</div>
            <div class="detail-card mb-4">
                <div class="info-row">
                    <div class="info-label">ID Pengembalian</div>
                    <div class="info-value">: {{ $selectedItem ? 'RET-2026-'.str_pad($selectedItem->id_pengembalian, 4, '0', STR_PAD_LEFT) : 'RET-2026-0062' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Peminjam</div>
                    <div class="info-value">: {{ $selectedItem && $selectedItem->peminjaman && $selectedItem->peminjaman->user ? $selectedItem->peminjaman->user->nama_lengkap : 'Moch Azmi Aris Sandita' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fasilitas</div>
                    <div class="info-value">: {{ $selectedItem && $selectedItem->peminjaman ? $selectedItem->peminjaman->nama_fasilitas : 'Aula Utama Polibatam' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Waktu Pengembalian</div>
                    <div class="info-value">: {{ $selectedItem && $selectedItem->tanggal_kembali ? $selectedItem->tanggal_kembali : '12 Apr 2026 · 12.10 WIB' }}</div>
                </div>

                <hr class="my-4" style="border-top: 1px solid var(--line);">

                <div class="mb-3 fw-semibold text-secondary">Checklist Verifikasi</div>
                <div class="checklist-item">
                    <input class="form-check-input mt-0" type="checkbox" id="check1" required>
                    <label class="form-check-label text-main fw-semibold" for="check1">Fasilitas kembali sesuai jumlah</label>
                </div>
                <div class="checklist-item">
                    <input class="form-check-input mt-0" type="checkbox" id="check2" required>
                    <label class="form-check-label text-main fw-semibold" for="check2">Tidak ada kerusakan mayor</label>
                </div>
                <div class="checklist-item">
                    <input class="form-check-input mt-0" type="checkbox" id="check3" required>
                    <label class="form-check-label text-main fw-semibold" for="check3">Catatan mahasiswa sesuai pemeriksaan</label>
                </div>

                <div class="mt-4">
                    <label class="form-label text-secondary fw-semibold">Catatan Admin</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: fasilitas kembali lengkap dan kondisi dinyatakan baik." style="border-radius: 0.75rem; border-color: #dfd4c8;"></textarea>
                </div>
            </div>
        </form>
    </div>

    <!-- Right Column: Decisions -->
    <div class="col-lg-4">
        <div class="mb-3 fw-semibold text-secondary">Keputusan Verifikasi</div>
        <div class="decision-card">
            <div class="mb-3">
                <span class="badge-menunggu">Menunggu Verifikasi</span>
            </div>
            
            <div class="d-grid gap-2">
                <button type="button" onclick="submitDecision('disetujui')" class="btn btn-verify-done">Verifikasi Selesai</button>
                <button type="button" onclick="submitDecision('perlu_pemeriksaan')" class="btn btn-verify-check">Perlu Pemeriksaan</button>
                <button type="button" onclick="submitDecision('bermasalah')" class="btn btn-verify-alert">Tandai Bermasalah</button>
            </div>

            <div class="text-muted small mt-4 text-center">
                Hasil verifikasi akan memperbarui status pengembalian agar riwayat fasilitas tetap terdokumentasi.
            </div>
        </div>
    </div>
</div>

<script>
    function submitDecision(status) {
        document.getElementById('keputusanField').value = status;
        
        var check1 = document.getElementById('check1');
        var check2 = document.getElementById('check2');
        var check3 = document.getElementById('check3');

        // Allow submitting without checking all boxes if marking as problematic/checks
        if (status === 'disetujui') {
            if (!check1.checked || !check2.checked || !check3.checked) {
                alert('Silahkan centang semua checklist verifikasi sebelum menyetujui pengembalian.');
                return;
            }
        } else {
            check1.removeAttribute('required');
            check2.removeAttribute('required');
            check3.removeAttribute('required');
        }

        document.getElementById('decisionForm') || document.querySelector('form').submit();
    }
</script>
@endsection
