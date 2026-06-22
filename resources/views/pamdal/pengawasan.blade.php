@extends('layout.pamdal')

@section('page_caption', 'Pamdal - Pengawasan Kegiatan')
@section('page_heading', 'Dashboard')

@section('pamdal_content')
<style>
    .banner-card {
        background-color: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.5rem;
    }
    .schedule-item-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.25rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: 0.2s;
    }
    .schedule-item-card:hover {
        transform: translateY(-2px);
    }
    .status-badge-controlled {
        background-color: #e2f0d9;
        color: #385723;
        font-weight: 600;
        padding: 0.5rem 1.5rem;
        border-radius: 2rem;
        display: inline-block;
        border: 1px solid #c5e1b5;
        font-size: 0.85rem;
    }
    .checklist-box {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
    }
    .notes-box {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 2rem 1.5rem;
        height: 100%;
    }
    .checklist-bullet {
        list-style-type: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    .checklist-bullet li {
        margin-bottom: 0.75rem;
        font-weight: 500;
        color: var(--text-main);
        font-size: 0.9rem;
    }
    .checklist-bullet li::before {
        content: "• ";
        color: var(--primary-main);
        font-size: 1.25rem;
        font-weight: bold;
        display: inline-block;
        width: 1em;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Pantau kegiatan peminjaman fasilitas</h2>
        <p class="mb-0 text-secondary text-wrap">
            Pamdal melihat jadwal kegiatan, mengawasi pelaksanaan, lalu mencatat temuan lapangan.
        </p>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Schedules list & checklist -->
    <div class="col-lg-6">
        <div class="mb-3 fw-semibold text-secondary">Jadwal Hari Ini</div>

        @forelse($peminjaman as $item)
        <div class="schedule-item-card">
            <h4 class="fs-5 fw-bold text-main mb-1">{{ $item->nama_kegiatan }}</h4>
            <div class="text-secondary small mb-1">{{ count($item->ruangan) > 0 ? $item->ruangan->first()->nama_ruangan : 'Aula Utama Polibatam' }}</div>
            <div class="text-muted small">{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d M Y') : '12 Apr 2026' }} - 08.00 - 12.00</div>
        </div>
        @empty
        <!-- Fallback mock cards matching Image 1 exactly -->
        <div class="schedule-item-card">
            <h4 class="fs-5 fw-bold text-main mb-1">Seminar Mahasiswa Baru</h4>
            <div class="text-secondary small mb-1">Aula Utama Polibatam</div>
            <div class="text-muted small">12 Apr 2026 - 08.00 - 12.00</div>
        </div>
        <div class="schedule-item-card">
            <h4 class="fs-5 fw-bold text-main mb-1">Workshop UI/UX TRPL</h4>
            <div class="text-secondary small mb-1">Lab Komputer 1</div>
            <div class="text-muted small">12 Apr 2026 - 13.00 - 15.00</div>
        </div>
        @endforelse

        <div class="mb-3 mt-4 fw-semibold text-secondary">Status Pengawasan</div>
        <div class="checklist-box">
            <div class="mb-3">
                <span class="status-badge-controlled">Kegiatan Terkendali</span>
            </div>
            <ul class="checklist-bullet">
                <li>Peserta masuk sesuai kapasitas</li>
                <li>Kegiatan sesuai aturan kampus</li>
                <li>Area sekitar aman dan tertib</li>
            </ul>
        </div>
    </div>

    <!-- Right Column: Monitoring Notes -->
    <div class="col-lg-6">
        <div class="mb-3 fw-semibold text-secondary">Catatan Pengawasan</div>
        <div class="notes-box d-flex flex-column justify-content-between">
            <form action="{{ route('pamdal.monitoring.store') }}" method="POST" id="monitoringForm">
                @csrf
                <input type="hidden" name="status_pengawasan" value="terkendali">
                <textarea name="catatan" class="form-control mb-4" rows="8" style="border-radius: 0.75rem; border-color: #dfd4c8; font-size: 0.95rem; line-height: 1.6; resize: none;">Kegiatan berjalan sesuai jadwal, penggunaan ruangan tertib, dan tidak ada pelanggaran.</textarea>
                <button type="submit" class="btn btn-main w-100" style="height: 48px; border-radius: 0.75rem;">Simpan Catatan</button>
            </form>
        </div>
    </div>
</div>
@endsection
