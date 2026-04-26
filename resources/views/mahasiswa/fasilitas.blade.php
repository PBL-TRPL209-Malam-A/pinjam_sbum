@extends('layout.app')

@section('content')
<style>
    :root{
        --page-bg: #f5f2ec;
        --panel-bg: #fcfbf8;
        --soft-bg: #eef4ee;
        --line: #e7ddd1;
        --text-main: #33403b;
        --text-muted: #7b8681;
        --primary-soft: #e5efe6;
        --primary-main: #5d7d6b;
        --primary-dark: #496454;
        --warning-soft: #f4e7c9;
    }

    body{
        background: var(--page-bg);
        color: var(--text-main);
    }

    .fasilitas-shell{
        background: var(--panel-bg);
        border: 1px solid var(--line);
        border-radius: 2rem;
        overflow: hidden;
        min-height: calc(100vh - 3rem);
    }

    .sidebar-panel{
        min-height: 100%;
        border-right: 1px solid var(--line);
        background: rgba(255,255,255,.25);
    }

    .logo-box img{
        width: 110px;
        height: auto;
        object-fit: contain;
    }

    .sidebar-link{
        color: #55615b;
        border-radius: 1rem;
        padding: .95rem 1rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: .75rem;
        transition: .2s ease;
    }

    .sidebar-link:hover{
        background: #f3f6f3;
        color: var(--primary-dark);
    }

    .sidebar-link.active{
        background: #edf3ee;
        color: var(--primary-dark);
        font-weight: 600;
    }

    .sidebar-dot{
        width: 1.35rem;
        height: 1.35rem;
        border-radius: 50%;
        background: #dfe7df;
        flex-shrink: 0;
    }

    .page-caption{
        color: var(--text-muted);
        font-size: 1.25rem;
        margin-bottom: 1.2rem;
    }

    .page-heading{
        font-size: 1.5rem;
        font-weight: 500;
        margin-bottom: 0;
    }

    .search-input{
        height: 3rem;
        border-radius: 1rem;
        border: 1px solid #ddd2c5;
        background: #fffdfa;
    }

    .search-dot{
        width: 2.25rem;
        height: 2.25rem;
        background: #cfdacd;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .intro-card{
        background: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.75rem;
    }

    .section-label{
        font-size: 1rem;
        font-weight: 600;
        color: #5c6761;
        margin-bottom: 1rem;
    }

    .filter-select{
        height: 3rem;
        border-radius: .95rem;
        border-color: #dfd4c8;
        background-color: #fffdfa;
        color: #727d77;
    }

    .btn-apply{
        background: var(--primary-main);
        border: 0;
        border-radius: .95rem;
        height: 3rem;
        font-weight: 600;
    }

    .btn-apply:hover{
        background: var(--primary-dark);
    }

    .facility-card{
        border: 1px solid #e0d7cb;
        border-radius: 1.5rem;
        background: #fffdfa;
    }

    .facility-thumb{
        height: 8rem;
        border-radius: 1.15rem;
    }

    .thumb-1{ background:#dfe8df; }
    .thumb-2{ background:#e7ddd3; }
    .thumb-3{ background:#dfe1ea; }
    .thumb-4{ background:#e5dfec; }

    .facility-title{
        font-size: 1.05rem;
        color: #4c5752;
    }

    .facility-meta{
        color: #5f6963;
        font-size: .95rem;
    }

    .badge-soft-success{
        background: #dcebd7;
        color: #557b58;
        border: 1px solid #b7d2b6;
        font-weight: 600;
        border-radius: 999px;
        padding: .55rem 1.15rem;
    }

    .badge-soft-warning{
        background: var(--warning-soft);
        color: #92723c;
        border: 1px solid #e3c98b;
        font-weight: 600;
        border-radius: 999px;
        padding: .55rem 1.15rem;
    }

    @media (max-width: 991.98px){
        .sidebar-panel{
            border-right: 0;
            border-bottom: 1px solid var(--line);
        }
    }
</style>

<div class="container-fluid py-3 py-lg-4 px-2 px-lg-4">
    <div class="fasilitas-shell">
        <div class="row g-0">
            <aside class="col-lg-3 col-xl-2 sidebar-panel p-3 p-lg-4">
                <div class="logo-box mb-4">
                    <img src="{{ asset('assets/images/logo-sbum-icon.png') }}" alt="SBUM">
                </div>

                <nav class="nav flex-column gap-2">
                    <a href="{{ route('mahasiswa.dashboard') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('mahasiswa.fasilitas') }}" class="sidebar-link active">
                        <span class="sidebar-dot"></span>
                        <span>Fasilitas</span>
                    </a>

                    <a href="#" class="sidebar-link">
                        <span class="sidebar-dot"></span>
                        <span>Jadwal</span>
                    </a>

                    <a href="#" class="sidebar-link">
                        <span class="sidebar-dot"></span>
                        <span>Pengajuan Saya</span>
                    </a>

                    <a href="#" class="sidebar-link">
                        <span class="sidebar-dot"></span>
                        <span>Pengembalian</span>
                    </a>

                    <a href="#" class="sidebar-link">
                        <span class="sidebar-dot"></span>
                        <span>Notifikasi</span>
                    </a>

                    <a href="#" class="sidebar-link">
                        <span class="sidebar-dot"></span>
                        <span>Profil</span>
                    </a>
                </nav>

                <div class="mt-auto pt-5 pt-lg-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none text-muted px-0">Logout</button>
                    </form>
                </div>
            </aside>

            <main class="col-lg-9 col-xl-10 p-3 p-md-4 p-xl-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">
                    <div>
                        <div class="page-caption">Mahasiswa</div>
                        <h1 class="page-heading">Mahasiswa · Daftar Fasilitas</h1>
                    </div>

                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <input type="text" class="form-control search-input" placeholder="Cari data">
                        <div class="search-dot"></div>
                    </div>
                </div>

                <div class="card intro-card shadow-none mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fs-5 fw-semibold mb-3">Lihat daftar fasilitas yang tersedia</h2>
                        <p class="mb-0 text-secondary">
                            Mahasiswa membuka menu fasilitas dan sistem menampilkan ruangan serta barang inventaris yang bisa dipinjam.
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-xl-3">
                        <div class="section-label">Filter</div>

                        <div class="d-grid gap-3">
                            <select class="form-select filter-select">
                                <option>Kategori: Semua</option>
                                <option>Ruangan</option>
                                <option>Inventaris</option>
                            </select>

                            <select class="form-select filter-select">
                                <option>Lokasi: Semua Gedung</option>
                                <option>Gedung A</option>
                                <option>Gedung B</option>
                                <option>Gedung C</option>
                            </select>

                            <select class="form-select filter-select">
                                <option>Status: Tersedia</option>
                                <option>Tersedia</option>
                                <option>Terbatas</option>
                            </select>

                            <button class="btn btn-apply text-white">Terapkan</button>
                        </div>
                    </div>

                    <div class="col-xl-9">
                        <div class="section-label">Daftar Fasilitas</div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card facility-card shadow-none h-100">
                                    <div class="card-body p-3 p-lg-4">
                                        <div class="facility-thumb thumb-1 mb-4"></div>
                                        <h3 class="facility-title mb-3">Aula Utama Polibatam</h3>
                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                                            <div class="facility-meta">Ruangan · Kapasitas 250 orang</div>
                                            <span class="badge-soft-success">Tersedia</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card facility-card shadow-none h-100">
                                    <div class="card-body p-3 p-lg-4">
                                        <div class="facility-thumb thumb-2 mb-4"></div>
                                        <h3 class="facility-title mb-3">Projector Epson</h3>
                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                                            <div class="facility-meta">Inventaris · Gudang SBUM</div>
                                            <span class="badge-soft-warning">Terbatas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card facility-card shadow-none h-100">
                                    <div class="card-body p-3 p-lg-4">
                                        <div class="facility-thumb thumb-3 mb-4"></div>
                                        <h3 class="facility-title mb-3">Ruang Rapat SBUM</h3>
                                        <div class="facility-meta">Ruangan · Kapasitas 20 orang</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card facility-card shadow-none h-100">
                                    <div class="card-body p-3 p-lg-4">
                                        <div class="facility-thumb thumb-4 mb-4"></div>
                                        <h3 class="facility-title mb-3">Sound System</h3>
                                        <div class="facility-meta">Inventaris · Unit Audio</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection
