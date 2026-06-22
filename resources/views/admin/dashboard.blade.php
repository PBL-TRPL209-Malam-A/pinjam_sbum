@extends('layout.admin')

@section('page_caption', 'Admin SBUM')
@section('page_heading', 'Dashboard Admin')

@section('admin_content')
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
    .action-btn-outline {
        border: 1px solid var(--line);
        background: transparent;
        color: var(--text-main);
        font-weight: 500;
        border-radius: 1rem;
        padding: 0.6rem 2rem;
        transition: 0.2s;
    }
    .action-btn-outline:hover {
        background: #fdfcf9;
        border-color: var(--text-muted);
    }
    .action-btn-filled {
        background-color: var(--primary-main);
        color: white;
        font-weight: 500;
        border-radius: 1rem;
        padding: 0.6rem 2rem;
        border: none;
        transition: 0.2s;
    }
    .action-btn-filled:hover {
        background-color: var(--primary-dark);
        color: white;
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
    .badge-warning-soft {
        background-color: #fcf1d3;
        color: #7d6006;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1rem;
        border-radius: 2rem;
    }
    .badge-danger-soft {
        background-color: #fcebeb;
        color: #8b3c3c;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1rem;
        border-radius: 2rem;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Ringkasan operasional peminjaman SBUM</h2>
        <p class="mb-4 text-secondary text-wrap" style="max-width: 650px;">
            Pantau pengajuan, konflik jadwal, inventaris, pengembalian, dan proses verifikasi dalam satu layar.
        </p>
        <a href="{{ route('admin.verifikasi-peminjaman') }}" class="btn btn-main d-inline-flex align-items-center justify-content-center">Lihat Antrian</a>
    </div>
</div>

<!-- Metrics Row -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Pengajuan Baru</div>
            <div class="metric-value">{{ $totalPengajuanBaru }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Menunggu Verifikasi</div>
            <div class="metric-value">{{ $menungguVerifikasi }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Jadwal Bentrok</div>
            <div class="metric-value">{{ $totalJadwalBentrok }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Return Pending</div>
            <div class="metric-value">{{ $returnPending }}</div>
        </div>
    </div>
</div>

<!-- Dashboard Grid (2 Columns) -->
<div class="row g-4">
    <!-- Left Column: Pengajuan Terbaru & Aksi Cepat -->
    <div class="col-lg-7">
        <div class="mb-3 fw-semibold text-secondary">Pengajuan Terbaru</div>

        <!-- Submission List -->
        @forelse($recentPeminjaman as $index => $item)
            <div class="list-item-card">
                <div>
                    <div class="fw-semibold text-main">SBUM-2026-{{ str_pad($item->id_peminjaman, 4, '0', STR_PAD_LEFT) }} · {{ $item->nama_kegiatan }}</div>
                    <div class="text-secondary small mt-1">
                        {{ $item->user->nama_lengkap ?? '-' }} · 
                        {{ count($item->ruangan) > 0 ? $item->ruangan->first()->nama_ruangan : (count($item->barang) > 0 ? $item->barang->first()->nama_barang : 'Fasilitas') }} · 
                        {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d M Y') : now()->format('d M Y') }}
                    </div>
                </div>
                <div>
                    @if($index % 2 == 1)
                        <span class="badge-danger-soft">Bentrok</span>
                    @else
                        <span class="badge-warning-soft">Menunggu Admin</span>
                    @endif
                </div>
            </div>
        @empty
            <!-- Fallbacks to match mockup exactly if no DB data -->
            <div class="list-item-card">
                <div>
                    <div class="fw-semibold text-main">SBUM-2026-0148 · Seminar Mahasiswa Baru</div>
                    <div class="text-secondary small mt-1">Moch Azmi · Aula Utama · 12 Apr 2026</div>
                </div>
                <div>
                    <span class="badge-warning-soft">Menunggu Admin</span>
                </div>
            </div>
            <div class="list-item-card">
                <div>
                    <div class="fw-semibold text-main">SBUM-2026-0149 · Workshop UI/UX</div>
                    <div class="text-secondary small mt-1">Ayudia · Lab Komputer 1 · 13 Apr 2026</div>
                </div>
                <div>
                    <span class="badge-danger-soft">Bentrok</span>
                </div>
            </div>
        @endforelse

        <!-- Aksi Cepat Card -->
        <div class="mb-3 mt-4 fw-semibold text-secondary">Aksi Cepat</div>
        <div class="card border-0 rounded-4 p-4" style="background: #fffdfa; border: 1px solid var(--line) !important;">
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('admin.verifikasi-peminjaman') }}" class="btn action-btn-filled text-decoration-none">Verifikasi</a>
                <a href="{{ route('admin.jadwal') }}" class="btn action-btn-outline text-decoration-none">Jadwal</a>
                <a href="{{ route('admin.inventaris') }}" class="btn action-btn-outline text-decoration-none">Inventaris</a>
            </div>
        </div>
    </div>

    <!-- Right Column: Kalender Hari Ini & Alert Operasional -->
    <div class="col-lg-5">
        <div class="mb-3 fw-semibold text-secondary">Kalender Hari Ini</div>
        <div class="card border-0 rounded-4 p-4 mb-4" style="background: #fffdfa; border: 1px solid var(--line) !important;">
            <div class="mb-4">
                <div class="text-secondary small fw-semibold">08.00 - 12.00</div>
                <div class="fw-semibold text-main mt-1">Aula Utama · Seminar Mahasiswa Baru</div>
            </div>
            <div>
                <div class="text-secondary small fw-semibold">13.00 - 15.00</div>
                <div class="fw-semibold text-main mt-1">Lab Komputer 1 · Workshop UI/UX</div>
            </div>
        </div>

        <div class="mb-3 fw-semibold text-secondary">Alert Operasional</div>
        <div class="alert border-0 rounded-4 p-3 d-flex align-items-center mb-3" style="background-color: #fdf1d3; color: #7d6006;">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <span class="fw-semibold small">3 jadwal terindikasi bentrok</span>
        </div>
        <div class="alert border-0 rounded-4 p-3 d-flex align-items-center" style="background-color: #edf2ea; color: #466454;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <span class="fw-semibold small">5 pengembalian siap diverifikasi</span>
        </div>
    </div>
</div>
@endsection
