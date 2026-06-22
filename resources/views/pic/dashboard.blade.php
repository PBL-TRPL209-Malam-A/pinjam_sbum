@extends('layout.pic')

@section('page_caption', 'PIC Ruangan')
@section('page_heading', 'Dashboard PIC')

@section('pic_content')
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
    .custom-table {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        overflow: hidden;
    }
    .custom-table th {
        background-color: #f7f3eb;
        color: var(--text-main);
        font-weight: 600;
        border: none;
        padding: 1rem 1.5rem;
    }
    .custom-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--line);
        color: var(--text-main);
    }
    .badge-aktif {
        background-color: #e2f0d9;
        color: #385723;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 1.25rem;
        border-radius: 2rem;
        display: inline-block;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Selamat Datang, {{ auth()->user()->nama_lengkap }}!</h2>
        <p class="mb-4 text-secondary text-wrap" style="max-width: 650px;">
            Pantau kesiapan fasilitas ruangan yang Anda kelola untuk memastikan semua kegiatan mahasiswa berjalan dengan baik.
        </p>
        <a href="{{ route('pic.kesiapan') }}" class="btn btn-main d-inline-flex align-items-center justify-content-center">Buka Kesiapan</a>
    </div>
</div>

<!-- Metrics Row -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Ruangan Dikelola</div>
            <div class="metric-value">{{ count($ruangan) > 0 ? count($ruangan) : 1 }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Menunggu Konfirmasi</div>
            <div class="metric-value">{{ $peminjamanDisetujui }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Kesiapan Selesai</div>
            <div class="metric-value">{{ $kesiapanSelesai }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Kendala Dilaporkan</div>
            <div class="metric-value">{{ $kendalaDilaporkan }}</div>
        </div>
    </div>
</div>

<!-- Table: Managed Rooms -->
<div class="mb-3 fw-semibold text-secondary">Fasilitas Ruangan Anda</div>
<div class="custom-table mb-4">
    <table class="table table-borderless mb-0">
        <thead>
            <tr>
                <th>Nama Ruangan</th>
                <th>Kode Ruangan</th>
                <th>Gedung</th>
                <th>Lantai</th>
                <th>Kapasitas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ruangan as $room)
            <tr>
                <td class="fw-semibold">{{ $room->nama_ruangan }}</td>
                <td>{{ $room->kode_ruangan }}</td>
                <td>{{ $room->nama_gedung ?? 'Gedung Utama' }}</td>
                <td>Lantai {{ $room->lantai ?? '1' }}</td>
                <td>{{ $room->kapasitas }} orang</td>
                <td>
                    <span class="badge-aktif">Aktif</span>
                </td>
            </tr>
            @empty
            <!-- Fallback Mock Room -->
            <tr>
                <td class="fw-semibold">Aula Utama Polibatam</td>
                <td>R101</td>
                <td>Gedung Utama</td>
                <td>Lantai 1</td>
                <td>250 orang</td>
                <td>
                    <span class="badge-aktif">Aktif</span>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
