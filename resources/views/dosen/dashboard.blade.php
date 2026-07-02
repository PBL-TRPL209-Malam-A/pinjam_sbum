@extends('layout.dosen')

@section('page_caption', 'Dosen Penanggung Jawab')
@section('page_heading', 'Dashboard Dosen')

@section('dosen_content')
<style>
    .banner-card {
        background-color: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.5rem;
    }
    .metric-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
        transition: 0.3s;
    }
    .metric-card:hover {
        transform: translateY(-2px);
    }
    .metric-title {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
    .metric-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--text-main);
    }
    .list-item-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.25rem;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .badge-verifikasi-soft {
        background-color: #fcf1d3;
        color: #7d6006;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1rem;
        border-radius: 2rem;
    }
    .badge-pending-soft {
        background-color: #f7f6f2;
        color: #55615b;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1rem;
        border-radius: 2rem;
    }
    .decision-badge-approved {
        background-color: #e2f0d9;
        color: #385723;
        border-radius: 1rem;
        padding: 0.85rem 1.25rem;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }
    .decision-badge-rejected {
        background-color: #fcebeb;
        color: #8b3c3c;
        border-radius: 1rem;
        padding: 0.85rem 1.25rem;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Verifikasi permohonan mahasiswa dengan cepat</h2>
        <p class="mb-4 text-secondary text-wrap" style="max-width: 650px;">
            Dashboard ini membantu dosen memantau pengajuan yang perlu diverifikasi, keputusan terbaru, dan jadwal kegiatan mahasiswa.
        </p>
        <a href="{{ route('dosen.verifikasi-peminjaman') }}" class="btn btn-main d-inline-flex align-items-center justify-content-center">Buka Verifikasi</a>
    </div>
</div>

<!-- Metrics Row -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Menunggu Verifikasi</div>
            <div class="metric-value">{{ $menungguVerifikasi }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Disetujui Hari Ini</div>
            <div class="metric-value">{{ $disetujuiHariIni }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Ditolak / Revisi</div>
            <div class="metric-value">{{ $ditolakRevisi }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Kegiatan Terdekat</div>
            <div class="metric-value">{{ $kegiatanTerdekat }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Antrian Verifikasi -->
    <div class="col-lg-7">
        <div class="mb-3 fw-semibold text-secondary">Antrian Verifikasi</div>

        @forelse($peminjaman as $index => $item)
            <div class="list-item-card" onclick="window.location.href='{{ route('dosen.verifikasi-peminjaman') }}?selected_id={{ $item->id_peminjaman }}'">
                <div>
                    <div class="fw-semibold text-main">SBUM-2026-{{ str_pad($item->id_peminjaman, 4, '0', STR_PAD_LEFT) }} - {{ $item->nama_kegiatan }}</div>
                    <div class="text-secondary small mt-1">
                        {{ $item->user->nama_lengkap ?? '-' }} · 
                        {{ $item->nama_fasilitas_with_type }} · 
                        {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d M Y') : now()->format('d M Y') }}
                    </div>
                </div>
                <div>
                    @if($item->status == 'menunggu_dosen')
                        <span class="badge-verifikasi-soft">Perlu Verifikasi</span>
                    @else
                        <span class="badge-pending-soft">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                    @endif
                </div>
            </div>
        @empty
            <!-- Fallbacks to match mockup exactly -->
            <div class="list-item-card">
                <div>
                    <div class="fw-semibold text-main">SBUM-2026-0148 - Seminar Mahasiswa Baru</div>
                    <div class="text-secondary small mt-1">Moch Azmi · Aula Utama · 12 Apr 2026</div>
                </div>
                <div>
                    <span class="badge-verifikasi-soft">Perlu Verifikasi</span>
                </div>
            </div>
            <div class="list-item-card">
                <div>
                    <div class="fw-semibold text-main">SBUM-2026-0149 - Workshop UI/UX</div>
                    <div class="text-secondary small mt-1">Ayudia · Lab Komputer 1 · 13 Apr 2026</div>
                </div>
                <div>
                    <span class="badge-pending-soft">Pending</span>
                </div>
            </div>
            <div class="list-item-card">
                <div>
                    <div class="fw-semibold text-main">SBUM-2026-0150 - Rapat Organisasi</div>
                    <div class="text-secondary small mt-1">Danudenta · Ruang Rapat SBUM · 14 Apr 2026</div>
                </div>
                <div>
                    <span class="badge-verifikasi-soft">Perlu Verifikasi</span>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Right Column: Riwayat Keputusan & Jadwal -->
    <div class="col-lg-5">
        <div class="mb-3 fw-semibold text-secondary">Riwayat Keputusan</div>
        <div class="decision-badge-approved">
            Disetujui · Projector Epson - 10 Apr
        </div>
        <div class="decision-badge-rejected">
            Ditolak · Lab Komputer 2 - 09 Apr
        </div>

        <div class="mb-3 mt-4 fw-semibold text-secondary">Jadwal Kegiatan Mahasiswa</div>
        <div class="card border-0 rounded-4 p-4" style="background: #fffdfa; border: 1px solid var(--line) !important;">
            <div class="mb-4">
                <div class="text-secondary small fw-semibold">12 Apr · 08.00 - 12.00</div>
                <div class="fw-semibold text-main mt-1">Aula Utama · Seminar Mahasiswa Baru</div>
            </div>
            <div>
                <div class="text-secondary small fw-semibold">13 Apr · 09.00 - 11.00</div>
                <div class="fw-semibold text-main mt-1">Lab Komputer 1 · Workshop UI/UX</div>
            </div>
        </div>
    </div>
</div>
@endsection
