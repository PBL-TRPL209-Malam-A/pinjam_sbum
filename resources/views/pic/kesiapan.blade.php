@extends('layout.pic')

@section('page_caption', 'PIC · Konfirmasi Kesiapan Fasilitas')
@section('page_heading', 'Dashboard')

@section('pic_content')
<style>
    .banner-card {
        background-color: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.5rem;
    }
    .detail-card, .status-side-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
    }
    .info-row {
        margin-bottom: 0.85rem;
    }
    .info-label {
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 0.2rem;
    }
    .info-value {
        color: var(--text-main);
        font-weight: 600;
        font-size: 0.95rem;
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
    .status-badge-ready {
        background-color: #e2f0d9;
        color: #385723;
        font-weight: 600;
        padding: 0.6rem;
        border-radius: 0.75rem;
        text-align: center;
        display: block;
        width: 100%;
        border: 1px solid #c5e1b5;
        font-size: 0.95rem;
    }
    .btn-upload-photo {
        background-color: #f7f6f2;
        border: 1px solid #dfd4c8;
        border-radius: 0.75rem;
        height: 48px;
        font-weight: 500;
        color: var(--text-main);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        transition: 0.2s;
    }
    .btn-upload-photo:hover {
        background-color: #f0e9df;
    }
    .btn-confirm-ready {
        background-color: var(--primary-main);
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: none;
        width: 100%;
        transition: 0.2s;
    }
    .btn-confirm-ready:hover {
        background-color: var(--primary-dark);
    }
    .btn-report-issue {
        background-color: #c95b50;
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        height: 48px;
        border: none;
        width: 100%;
        transition: 0.2s;
    }
    .btn-report-issue:hover {
        background-color: #b34e44;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Pastikan fasilitas siap sebelum digunakan</h2>
        <p class="mb-0 text-secondary text-wrap">
            PIC memeriksa kondisi ruangan dan perlengkapan, lalu menyimpan status siap atau kendala.
        </p>
    </div>
</div>

@php
    $selectedItem = $peminjaman->first();
@endphp

<div class="row g-4 mb-4">
    <!-- Left Column: Details & Checklists -->
    <div class="col-lg-7">
        <form action="{{ route('pic.kesiapan.store') }}" method="POST" id="kesiapanForm">
            @csrf
            <input type="hidden" name="peminjaman_id" value="{{ $selectedItem ? $selectedItem->id_peminjaman : 1 }}">
            <input type="hidden" name="status_kesiapan" id="kesiapanField" value="siap">

            <div class="mb-3 fw-semibold text-secondary">Detail Jadwal Hari Ini</div>
            <div class="detail-card mb-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Kegiatan</div>
                            <div class="info-value">{{ $selectedItem ? $selectedItem->nama_kegiatan : 'Seminar Mahasiswa Baru' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Fasilitas</div>
                            <div class="info-value">{{ $selectedItem && count($selectedItem->ruangan) > 0 ? $selectedItem->ruangan->first()->nama_ruangan : 'Aula Utama Polibatam' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Waktu</div>
                            <div class="info-value">{{ $selectedItem && $selectedItem->tanggal_pengajuan ? $selectedItem->tanggal_pengajuan->format('d M Y') : '12 Apr 2026' }} · 08.00 - 12.00</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">PIC</div>
                            <div class="info-value">Petugas SBUM / PIC Aula</div>
                        </div>
                    </div>
                </div>

                <hr class="my-4" style="border-top: 1px solid var(--line);">

                <div class="mb-3 fw-semibold text-secondary">Checklist Kesiapan</div>
                <div class="checklist-item">
                    <input class="form-check-input mt-0" type="checkbox" id="check1" required>
                    <label class="form-check-label text-main fw-semibold" for="check1">Ruangan bersih dan siap pakai</label>
                </div>
                <div class="checklist-item">
                    <input class="form-check-input mt-0" type="checkbox" id="check2" required>
                    <label class="form-check-label text-main fw-semibold" for="check2">Sound system dan listrik normal</label>
                </div>
                <div class="checklist-item">
                    <input class="form-check-input mt-0" type="checkbox" id="check3" required>
                    <label class="form-check-label text-main fw-semibold" for="check3">Kursi, meja, dan akses ruangan lengkap</label>
                </div>
            </div>
        </form>
    </div>

    <!-- Right Column: Status Kesiapan, Catatan & Upload -->
    <div class="col-lg-5">
        <div class="mb-3 fw-semibold text-secondary">Status Kesiapan</div>
        <div class="status-side-card mb-4">
            <div class="mb-3">
                <span class="status-badge-ready">Siap Digunakan</span>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary small fw-semibold">Catatan PIC</label>
                <textarea form="kesiapanForm" name="catatan" class="form-control" rows="4" style="border-radius: 0.75rem; border-color: #dfd4c8; font-size: 0.9rem;">Semua perangkat berfungsi, ruangan bersih, dan akses siap dibuka untuk kegiatan.</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label text-secondary small fw-semibold">Upload Bukti</label>
                <button type="button" class="btn btn-upload-photo">
                    <i class="bi bi-camera-fill"></i> Tambah foto kondisi
                </button>
            </div>

            <div class="d-grid gap-2">
                <button type="button" onclick="submitKesiapan('siap')" class="btn btn-confirm-ready">Konfirmasi Siap</button>
                <button type="button" onclick="submitKesiapan('kendala')" class="btn btn-report-issue">Laporkan Kendala</button>
            </div>
        </div>
    </div>
</div>

<script>
    function submitKesiapan(status) {
        document.getElementById('kesiapanField').value = status;
        
        var check1 = document.getElementById('check1');
        var check2 = document.getElementById('check2');
        var check3 = document.getElementById('check3');

        if (status === 'siap') {
            if (!check1.checked || !check2.checked || !check3.checked) {
                alert('Silahkan centang semua checklist kesiapan sebelum mengonfirmasi siap.');
                return;
            }
        } else {
            check1.removeAttribute('required');
            check2.removeAttribute('required');
            check3.removeAttribute('required');
        }

        document.getElementById('kesiapanForm').submit();
    }
</script>
@endsection
