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
        min-width:180px;
        height:48px;
        color:#fff;
        font-weight:600;
    }

    .btn-main:hover{
        background:var(--primary-dark);
        color:#fff;
    }

    .logout-btn{
        background:transparent;
        border:0;
        color:#5b635f;
        padding:0;
        font-size:16px;
    }

    .stat-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
        height: 100%;
        transition: 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.02);
    }

    .stat-label {
        font-size: 0.95rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .stat-val {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--text-main);
    }

    @media (max-width: 991.98px){
        .sidebar-panel{
            border-right:0;
            border-bottom:1px solid var(--line);
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
                    <a href="{{ route('kepalasbum.dashboard') }}" class="sidebar-link active">
                        <span class="sidebar-dot"></span><span>Dashboard</span>
                    </a>
                    <a href="{{ route('kepalasbum.staff') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Data Staff SBUM</span>
                    </a>
                    <a href="{{ route('kepalasbum.persetujuan') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Persetujuan Akhir</span>
                    </a>
                    <a href="{{ route('kepalasbum.laporan') }}" class="sidebar-link">
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
                        <h1 class="page-heading">Dashboard</h1>
                    </div>

                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <input type="text" class="form-control search-input" placeholder="Cari data">
                        <div class="search-dot"></div>
                    </div>
                </div>

                <div class="card intro-card shadow-none mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fs-5 fw-semibold mb-3">Selamat Datang, {{ auth()->user()->nama_lengkap }}!</h2>
                        <p class="mb-4 text-secondary text-wrap" style="max-width: 650px;">
                            Sistem pengelolaan fasilitas dan peminjaman ruang SBUM siap digunakan. Sebagai Kepala SBUM, Anda memiliki wewenang penuh untuk memantau data staf, persetujuan akhir, serta melihat laporan aktivitas secara real-time.
                        </p>
                        <a href="{{ route('kepalasbum.persetujuan') }}" class="btn btn-main d-inline-flex align-items-center justify-content-center">Tinjau Pengajuan</a>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-label">Total Staf SBUM</div>
                            <div class="stat-val">{{ $totalStaff }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-label">Total Peminjaman</div>
                            <div class="stat-val">{{ $totalPeminjaman }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-label">Menunggu Persetujuan</div>
                            <div class="stat-val">{{ $pendingPersetujuan }}</div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection
