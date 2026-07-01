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
        --orange-badge-bg:#fdf5e6;
        --orange-badge-text:#b8860b;
        --orange-badge-line:#f5deb3;
        --red-badge-bg:#fbebeb;
        --red-badge-text:#c05c5c;
        --red-badge-line:#f8d7d7;
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

    .stat-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
        height: 100%;
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

    .custom-table {
        width: 100%;
        margin-top: 1.5rem;
        border-collapse: separate;
        border-spacing: 0 0.5rem;
    }

    .custom-table th {
        color: var(--text-muted);
        font-weight: 500;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--line);
    }

    .custom-table td {
        padding: 1.25rem 1rem;
        background: #fffdfa;
        border-top: 1px solid var(--line);
        border-bottom: 1px solid var(--line);
    }

    .custom-table tr td:first-child {
        border-left: 1px solid var(--line);
        border-top-left-radius: 1rem;
        border-bottom-left-radius: 1rem;
    }

    .custom-table tr td:last-child {
        border-right: 1px solid var(--line);
        border-top-right-radius: 1rem;
        border-bottom-right-radius: 1rem;
    }

    .badge-condition-good {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 130px;
        height: 28px;
        border-radius: 999px;
        background: #e5eee5;
        color: #557b58;
        border: 1px solid #d2dfd2;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .badge-condition-check {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 130px;
        height: 28px;
        border-radius: 999px;
        background: var(--orange-badge-bg);
        color: var(--orange-badge-text);
        border: 1px solid var(--orange-badge-line);
        font-size: 0.85rem;
        font-weight: 500;
    }

    .badge-condition-late {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 130px;
        height: 28px;
        border-radius: 999px;
        background: var(--red-badge-bg);
        color: var(--red-badge-text);
        border: 1px solid var(--red-badge-line);
        font-size: 0.85rem;
        font-weight: 500;
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
                    <a href="{{ route('kepalasbum.dashboard') }}" class="sidebar-link">
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
                    <a href="{{ route('kepalasbum.laporan-pengembalian') }}" class="sidebar-link active">
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
                        <h1 class="page-heading">Laporan Pengembalian Fasilitas</h1>
                    </div>

                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <input type="text" class="form-control search-input" placeholder="Cari data">
                        <div class="search-dot"></div>
                    </div>
                </div>

                <div class="card intro-card shadow-none mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fs-5 fw-semibold mb-3">Laporan pengembalian dan kondisi fasilitas</h2>
                        <p class="mb-0 text-secondary">
                            Kepala SBUM melihat jumlah pengembalian, kondisi fasilitas setelah dipakai, dan item yang perlu tindak lanjut.
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
                                <label class="text-secondary fw-semibold mb-1" style="font-size:0.8rem;">Kondisi</label>
                                <button class="btn btn-soft-filter d-flex align-items-center justify-content-between">
                                    <span>Semua</span>
                                    <i class="bi bi-chevron-down ms-2"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn btn-dark-action d-inline-flex align-items-center justify-content-center" onclick="alert('Laporan berhasil diunduh (Mockup)')">Unduh Excel</button>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-label">Total Pengembalian</div>
                            <div class="stat-val">{{ $totalPengembalian ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-label">Kondisi Baik</div>
                            <div class="stat-val">{{ $kondisiBaik ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-label">Perlu Tindak Lanjut</div>
                            <div class="stat-val">{{ $tindakLanjut ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-label">Terlambat</div>
                            <div class="stat-val">{{ $terlambat ?? 0 }}</div>
                        </div>
                    </div>
                </div>

                <h2 class="fs-5 fw-bold mb-3 text-secondary">Daftar Pengembalian</h2>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Fasilitas</th>
                                <th>Peminjam</th>
                                <th>Tanggal</th>
                                <th style="text-align: right; padding-right: 2rem;">Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengembalian as $p)
                            <tr>
                                <td class="fw-semibold">
                                    @if($p->peminjaman->ruangan->isNotEmpty())
                                        {{ $p->peminjaman->ruangan->first()->nama_ruangan }}
                                    @elseif($p->peminjaman->barang->isNotEmpty())
                                        {{ $p->peminjaman->barang->first()->nama_barang }}
                                    @else
                                        Fasilitas
                                    @endif
                                </td>
                                <td>{{ $p->peminjaman->user->nama_lengkap ?? '-' }}</td>
                                <td>{{ date('d M Y', strtotime($p->tanggal_kembali)) }}</td>
                                <td style="text-align: right; padding-right: 1.5rem;">
                                    @if($p->kondisi_kembali == 'baik')
                                        <span class="badge-condition-good">Baik</span>
                                    @elseif($p->kondisi_kembali == 'hilang' || $p->kondisi_kembali == 'rusak')
                                        <span class="badge-condition-late">Terlambat</span>
                                    @else
                                        <span class="badge-condition-check">Perlu Pemeriksaan</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection
