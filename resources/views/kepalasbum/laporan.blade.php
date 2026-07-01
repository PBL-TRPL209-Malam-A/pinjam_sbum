@extends('layout.app')

@section('content')
<style>
    :root{
        --page-bg:#f5f2ec;
        --panel-bg:#fcfbf8;
        --soft-bg:#edf2ea;
        --line:#e7ddd1;
        --text-main:#33403b;
        --text-muted:#7b8681;
        --primary-main:#5d7d6b;
        --primary-dark:#496454;
        --soft-green:#dfe9df;
        --dark-filter-btn:#54645c;
        --dark-filter-btn-hover:#3f4d46;
    }

    body{
        background:var(--page-bg);
        color:var(--text-main);
        font-family: Arial, Helvetica, sans-serif;
    }

    .app-shell{
        background:var(--panel-bg);
        border:1px solid var(--line);
        border-radius:2rem;
        overflow:hidden;
        min-height:calc(100vh - 3rem);
    }

    .sidebar-panel{
        min-height:100%;
        border-right:1px solid var(--line);
        background:rgba(255,255,255,.25);
    }

    .logo-box{
        display:flex;
        align-items:center;
        gap:.75rem;
    }

    .logo-box img{
        width:48px;
        height:auto;
        object-fit:contain;
    }

    .logo-text{
        font-size:1.2rem;
        font-weight:700;
        color:#55615b;
    }

    .sidebar-link{
        color:#55615b;
        border-radius:1rem;
        padding:.95rem 1rem;
        text-decoration:none;
        display:flex;
        align-items:center;
        gap:.75rem;
        transition:.2s ease;
    }

    .sidebar-link:hover{
        background:#f3f6f3;
        color:var(--primary-dark);
    }

    .sidebar-link.active{
        background:#edf3ee;
        color:var(--primary-dark);
        font-weight:600;
    }

    .sidebar-dot{
        width:1.35rem;
        height:1.35rem;
        border-radius:50%;
        background:#dfe7df;
        flex-shrink:0;
    }

    .page-caption{
        color:var(--text-muted);
        font-size:1.25rem;
        margin-bottom:1.2rem;
    }

    .page-heading{
        font-size:1.35rem;
        font-weight:500;
        margin-bottom:0;
    }

    .search-input{
        height:3rem;
        border-radius:1rem;
        border:1px solid #ddd2c5;
        background:#fffdfa;
    }

    .search-dot{
        width:2.25rem;
        height:2.25rem;
        background:#cfdacd;
        border-radius:50%;
        flex-shrink:0;
    }

    .intro-card{
        background:var(--soft-bg);
        border:1px solid #dfe7dc;
        border-radius:1.75rem;
    }

    .btn-main{
        background:var(--primary-main);
        border:0;
        border-radius:1rem;
        min-width:140px;
        height:44px;
        color:#fff;
        font-weight:600;
        transition: 0.2s;
    }

    .btn-main:hover{
        background:var(--primary-dark);
        color:#fff;
    }

    .btn-soft-filter{
        background:#fffdfa;
        border:1px solid #dfd4c8;
        border-radius:1rem;
        height:40px;
        min-width:150px;
        color:#5f6963;
        font-weight:500;
        transition: 0.2s;
        text-align: left;
        padding-left: 1rem;
    }

    .btn-soft-filter:hover{
        background:#f7f2eb;
    }

    .btn-dark-action {
        background: var(--dark-filter-btn);
        border: 0;
        border-radius: 1rem;
        height: 40px;
        min-width: 140px;
        color: #fff;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-dark-action:hover {
        background: var(--dark-filter-btn-hover);
        color: #fff;
    }

    .logout-btn{
        background:transparent;
        border:0;
        color:#5b635f;
        padding:0;
        font-size:16px;
    }

    .stat-card-row {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-item {
        border-right: 1px solid var(--line);
        padding: 0 1rem;
    }

    .stat-item:last-child {
        border-right: 0;
    }

    .stat-item-label {
        font-size: 0.9rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .stat-item-val {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.2;
    }

    .stat-item-desc {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .chart-panel, .list-panel {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
        height: 100%;
    }

    .panel-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 1.5rem;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table th {
        color: var(--text-muted);
        font-weight: 500;
        padding: 0.75rem 0.5rem;
        border-bottom: 1px solid var(--line);
        text-align: left;
    }

    .custom-table td {
        padding: 1rem 0.5rem;
        border-bottom: 1px solid var(--line);
        color: var(--text-main);
    }

    .custom-table tr:last-child td {
        border-bottom: 0;
    }

    @media (max-width: 991.98px){
        .sidebar-panel{
            border-right:0;
            border-bottom:1px solid var(--line);
        }
        .stat-item {
            border-right: 0;
            border-bottom: 1px solid var(--line);
            padding: 1rem 0;
        }
        .stat-item:last-child {
            border-bottom: 0;
        }
    }
</style>

<div class="container-fluid py-3 py-lg-4 px-2 px-lg-4">
    <div class="app-shell">
        <div class="row g-0">
            <aside class="col-lg-3 col-xl-2 sidebar-panel p-3 p-lg-4 d-flex flex-column">
                <div class="logo-box mb-4">
                    <img src="{{ asset('assets/images/logo-sbum-icon.png') }}" alt="SBUM">
                    <div class="logo-text">SBUM</div>
                </div>

                <nav class="nav flex-column gap-2">
                    <a href="{{ route('kepalasbum.dashboard') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Dashboard</span>
                    </a>
                    <a href="{{ route('kepalasbum.staff') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Data Staff SBUM</span>
                    </a>
                    <a href="{{ route('kepalasbum.persetujuan') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Persetujuan Akhir</span>
                    </a>
                    <a href="{{ route('kepalasbum.laporan') }}" class="sidebar-link active">
                        <span class="sidebar-dot"></span><span>Laporan Peminjaman</span>
                    </a>
                    <a href="{{ route('kepalasbum.laporan-pengembalian') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Laporan Pengembalian</span>
                    </a>
                    <a href="{{ route('kepalasbum.profil') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Profil</span>
                    </a>
                </nav>

                <div class="mt-auto pt-5 pt-lg-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </aside>

            <main class="col-lg-9 col-xl-10 p-3 p-md-4 p-xl-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">
                    <div>
                        <div class="page-caption">Kepala SBUM</div>
                        <h1 class="page-heading">Laporan Peminjaman Fasilitas</h1>
                    </div>

                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <input type="text" class="form-control search-input" placeholder="Cari data">
                        <div class="search-dot"></div>
                    </div>
                </div>

                <div class="card intro-card shadow-none mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fs-5 fw-semibold mb-3">Laporan peminjaman fasilitas per periode</h2>
                        <p class="mb-0 text-secondary">
                            Kepala SBUM melihat ringkasan statistik, tren peminjaman, dan rekap fasilitas yang paling sering digunakan.
                        </p>
                    </div>
                </div>

                <div class="card p-3 mb-4 shadow-none border-0" style="background:#fffdfa; border: 1px solid var(--line) !important; border-radius: 1.5rem;">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="d-flex flex-column">
                                <label class="text-secondary fw-semibold mb-1" style="font-size:0.8rem;">Periode</label>
                                <button class="btn btn-soft-filter d-flex align-items-center justify-content-between">
                                    <span>April 2026</span>
                                    <i class="bi bi-chevron-down ms-2"></i>
                                </button>
                            </div>
                            <div class="d-flex flex-column">
                                <label class="text-secondary fw-semibold mb-1" style="font-size:0.8rem;">Jenis</label>
                                <button class="btn btn-soft-filter d-flex align-items-center justify-content-between">
                                    <span>Semua Fasilitas</span>
                                    <i class="bi bi-chevron-down ms-2"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn btn-dark-action d-inline-flex align-items-center justify-content-center" onclick="alert('Laporan berhasil diunduh (Mockup)')">Unduh PDF</button>
                    </div>
                </div>

                <div class="stat-card-row">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-item-label">Total Peminjaman</div>
                                <div class="stat-item-val">{{ $totalPeminjaman }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-item-label">Disetujui</div>
                                <div class="stat-item-val text-success">{{ $disetujui }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-item-label">Ditolak</div>
                                <div class="stat-item-val text-danger">{{ $ditolak }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-item-label">Fasilitas Terbanyak</div>
                                <div class="stat-item-val" style="font-size: 1.25rem;">{{ $ruangTerbanyak }}</div>
                                <div class="stat-item-desc mt-1">{{ $ruangTerbanyakCount }} Transaksi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="chart-panel">
                            <h3 class="panel-title">Grafik Tren Peminjaman</h3>
                            
                            <div class="pt-3">
                                <svg viewBox="0 0 500 200" style="width: 100%; height: auto;">
                                    <!-- grid lines -->
                                    <line x1="0" y1="50" x2="500" y2="50" stroke="#f1ece4" stroke-width="1"/>
                                    <line x1="0" y1="100" x2="500" y2="100" stroke="#f1ece4" stroke-width="1"/>
                                    <line x1="0" y1="150" x2="500" y2="150" stroke="#f1ece4" stroke-width="1"/>
                                    
                                    <!-- line -->
                                    <path d="M 30,150 L 100,120 L 170,130 L 240,70 L 310,90 L 380,40 L 450,60" fill="none" stroke="#5d7d6b" stroke-width="3" stroke-linecap="round"/>
                                    
                                    <!-- dots -->
                                    <circle cx="30" cy="150" r="5" fill="#496454"/>
                                    <circle cx="100" cy="120" r="5" fill="#496454"/>
                                    <circle cx="170" cy="130" r="5" fill="#496454"/>
                                    <circle cx="240" cy="70" r="5" fill="#496454"/>
                                    <circle cx="310" cy="90" r="5" fill="#496454"/>
                                    <circle cx="380" cy="40" r="5" fill="#496454"/>
                                    <circle cx="450" cy="60" r="5" fill="#496454"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="list-panel">
                            <h3 class="panel-title">Ringkasan Fasilitas</h3>
                            
                            <div class="table-responsive">
                                <table class="custom-table">
                                    <thead>
                                        <tr>
                                            <th>Fasilitas</th>
                                            <th style="text-align: right;">Jumlah Peminjaman</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ringkasanFasilitas as $fasilitas => $count)
                                        <tr>
                                            <td class="fw-semibold">{{ $fasilitas }}</td>
                                            <td style="text-align: right;" class="text-secondary fw-semibold">{{ $count }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-secondary py-3">Belum ada data fasilitas yang dipinjam.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection
