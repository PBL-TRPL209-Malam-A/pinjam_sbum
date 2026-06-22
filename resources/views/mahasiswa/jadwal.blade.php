@extends('layout.app')

@section('content')
<style>
    :root{
        --page-bg:#f5f2ec;
        --panel-bg:#fcfbf8;
        --soft-bg:#eef4ee;
        --line:#e7ddd1;
        --text-main:#33403b;
        --text-muted:#7b8681;
        --primary-main:#5d7d6b;
        --primary-dark:#496454;
        --warning-soft:#f4e7c9;
        --danger-soft:#f3dedd;
        --danger-text:#a4534d;
    }

    body{background:var(--page-bg);color:var(--text-main);}
    .app-shell{background:var(--panel-bg);border:1px solid var(--line);border-radius:2rem;overflow:hidden;min-height:calc(100vh - 3rem);}
    .sidebar-panel{min-height:100%;border-right:1px solid var(--line);background:rgba(255,255,255,.25);}
    .logo-box img{width:110px;height:auto;object-fit:contain;}
    .brand-inline{display:flex;align-items:center;gap:.75rem;}
    .brand-inline strong{font-size:2rem;color:#59635f;}
    .sidebar-link{color:#55615b;border-radius:1rem;padding:.95rem 1rem;text-decoration:none;display:flex;align-items:center;gap:.75rem;transition:.2s ease;}
    .sidebar-link:hover{background:#f3f6f3;color:var(--primary-dark);}
    .sidebar-link.active{background:#edf3ee;color:var(--primary-dark);font-weight:600;}
    .sidebar-dot{width:1.35rem;height:1.35rem;border-radius:50%;background:#dfe7df;flex-shrink:0;}
    .page-caption{color:var(--text-muted);font-size:1.25rem;margin-bottom:1.2rem;}
    .page-heading{font-size:1.5rem;font-weight:500;margin-bottom:0;}
    .search-input{height:3rem;border-radius:1rem;border:1px solid #ddd2c5;background:#fffdfa;}
    .search-dot{width:2.25rem;height:2.25rem;background:#cfdacd;border-radius:50%;flex-shrink:0;}
    .intro-card{background:#edf2ea;border:1px solid #dfe7dc;border-radius:1.75rem;}
    .section-label{font-size:1rem;font-weight:600;color:#5c6761;margin-bottom:1rem;}
    .filter-card,.note-card,.timeline-card{border:1px solid #e0d7cb;border-radius:1.5rem;background:#fffdfa;}
    .soft-pill{
        display:inline-flex;align-items:center;justify-content:center;
        min-width:180px;height:2.3rem;border-radius:999px;
        border:1px solid #d6ddd5;background:#edf3ee;color:#7d8682;
        font-size:.95rem;
    }
    .calendar-bar{
        background:#e6e2dc;border-radius:1.2rem;padding:1.25rem 1rem;
    }
    .calendar-grid{
        display:grid;
        grid-template-columns:repeat(7,1fr);
        gap:1rem;
        text-align:center;
        color:#626c67;
    }
    .slot-row{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:1rem;
    }
    .slot-box{
        height:58px;border-radius:1rem;display:flex;align-items:center;justify-content:center;
        font-weight:500;border:1px solid transparent;
    }
    .slot-available{background:#dcebd7;border-color:#b7d2b6;color:#557b58;}
    .slot-used{background:#f3dedd;border-color:#e1aba5;color:#a4534d;}
    .slot-pending{background:#f4e7c9;border-color:#e3c98b;color:#92723c;}
    .logout-btn{background:transparent;border:0;color:#5b635f;padding:0;font-size:16px;}

    @media (max-width: 991.98px){
        .sidebar-panel{border-right:0;border-bottom:1px solid var(--line);}
    }

    @media (max-width: 767.98px){
        .calendar-grid{grid-template-columns:repeat(4,1fr);}
        .slot-row{grid-template-columns:1fr;}
    }
</style>

<div class="container-fluid py-3 py-lg-4 px-2 px-lg-4">
    <div class="app-shell">
        <div class="row g-0">
            <aside class="col-lg-3 col-xl-2 sidebar-panel p-3 p-lg-4 d-flex flex-column">
                <div class="logo-box mb-4">
                    <img src="{{ asset('assets/images/logo-sbum-icon.png') }}" alt="SBUM">
                </div>

                <nav class="nav flex-column gap-2">
                    <a href="{{ route('mahasiswa.dashboard') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Dashboard</span>
                    </a>
                    <a href="{{ route('mahasiswa.fasilitas') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Fasilitas</span>
                    </a>
                    <a href="{{ route('mahasiswa.jadwal') }}" class="sidebar-link active">
                        <span class="sidebar-dot"></span><span>Jadwal</span>
                    </a>
                    <a href="{{ route('mahasiswa.pengajuan') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Pengajuan Saya</span>
                    </a>
                    <a href="{{ route('mahasiswa.pengembalian') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Pengembalian</span>
                    </a>
                    <a href="{{ route('mahasiswa.notifikasi') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Notifikasi</span>
                    </a>
                    <a href="{{ route('mahasiswa.profil') }}" class="sidebar-link">
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
                        <div class="page-caption">Mahasiswa</div>
                        <h1 class="page-heading">Mahasiswa · Jadwal Ketersediaan</h1>
                    </div>

                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <input type="text" class="form-control search-input" placeholder="Cari data">
                        <div class="search-dot"></div>
                    </div>
                </div>

                <div class="card intro-card shadow-none mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fs-5 fw-semibold mb-3">Lihat jadwal ketersediaan fasilitas</h2>
                        <p class="mb-0 text-secondary">
                            Mahasiswa membuka menu jadwal dan sistem menampilkan slot pemakaian agar tidak terjadi bentrok.
                        </p>
                    </div>
                </div>

                <div class="filter-card p-4 mb-5">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary">Fasilitas</label>
                            <div class="soft-pill w-100">Aula Utama</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary">Tanggal</label>
                            <div class="soft-pill w-100">12 Apr 2026</div>
                        </div>
                    </div>
                </div>

                <div class="section-label">Kalender Ketersediaan</div>

                <div class="timeline-card p-4 mb-5">
                    <div class="calendar-bar mb-5">
                        <div class="calendar-grid">
                            <div>08.00</div>
                            <div>09.00</div>
                            <div>10.00</div>
                            <div>11.00</div>
                            <div>12.00</div>
                            <div>13.00</div>
                            <div>14.00</div>
                        </div>
                    </div>

                    <div class="slot-row">
                        <div class="slot-box slot-available">Tersedia</div>
                        <div class="slot-box slot-used">Dipakai</div>
                        <div class="slot-box slot-available">Tersedia</div>
                        <div class="slot-box slot-pending">Menunggu</div>
                    </div>
                </div>

                <div class="note-card p-4">
                    <h3 class="fs-5 fw-semibold mb-3">Catatan Jadwal</h3>
                    <p class="mb-0 text-secondary">
                        Slot merah sudah dipakai, slot kuning masih dalam proses persetujuan, slot hijau bisa diajukan.
                    </p>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection
