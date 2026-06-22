@extends('layout.pamdal')

@section('page_caption', 'Pamdal')
@section('page_heading', 'Dashboard Pamdal')

@section('pamdal_content')
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
    .quick-list-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Selamat Datang, {{ auth()->user()->nama_lengkap }}!</h2>
        <p class="mb-4 text-secondary text-wrap" style="max-width: 650px;">
            Pantau pengawasan ketertiban fasilitas, kelayakan penggunaan kapasitas, dan pelaporan kendala secara langsung untuk memastikan ketertiban area kampus.
        </p>
        <a href="{{ route('pamdal.monitoring') }}" class="btn btn-main d-inline-flex align-items-center justify-content-center">Buka Monitoring</a>
    </div>
</div>

<!-- Metrics Row -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Kegiatan Hari Ini</div>
            <div class="metric-value">{{ $todaySchedules }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Total Pengawasan</div>
            <div class="metric-value">{{ $totalPengawasan }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Aman Terkendali</div>
            <div class="metric-value">{{ $amanTerkendali }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-title">Ada Kendala</div>
            <div class="metric-value">{{ $adaKendala }}</div>
        </div>
    </div>
</div>

<!-- Info Card -->
<div class="quick-list-card">
    <h3 class="fs-5 fw-semibold text-main mb-3">Tugas Utama Pamdal</h3>
    <ul class="text-secondary small ps-3">
        <li class="mb-2">Memantau ketertiban dan kapasitas ruangan saat kegiatan mahasiswa berlangsung.</li>
        <li class="mb-2">Mencatat temuan lapangan dan melaporkan status pengawasan ke dalam sistem.</li>
        <li>Menjaga keamanan area sekitar fasilitas selama waktu peminjaman.</li>
    </ul>
</div>
@endsection
