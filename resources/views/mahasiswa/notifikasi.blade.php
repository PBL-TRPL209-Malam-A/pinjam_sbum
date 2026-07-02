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
        --success-soft:#dcebd7;
        --success-text:#557b58;
        --warning-soft:#f4e7c9;
        --warning-text:#92723c;
        --danger-soft:#f3dedd;
        --danger-text:#a4534d;
        --info-soft:#e4ece8;
        --info-text:#4f6a5c;
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

    .btn-soft{
        background:#fffdfa;
        border:1px solid #dfd4c8;
        border-radius:1rem;
        height:46px;
        color:#5f6963;
        font-weight:500;
    }

    .btn-soft:hover{
        background:#f7f2eb;
    }

    .section-title-small{
        color:#5d6762;
        font-weight:600;
        margin-bottom:1rem;
    }

    .notif-card{
        border:1px solid #e0d7cb;
        border-radius:1.25rem;
        background:#fffdfa;
        padding:1rem;
        display:flex;
        gap:1rem;
        align-items:flex-start;
        height:100%;
    }

    .notif-icon{
        width:54px;
        height:54px;
        border-radius:1rem;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:1.25rem;
        flex-shrink:0;
    }

    .notif-icon.success{
        background:var(--success-soft);
        color:var(--success-text);
    }

    .notif-icon.warning{
        background:var(--warning-soft);
        color:var(--warning-text);
    }

    .notif-icon.danger{
        background:var(--danger-soft);
        color:var(--danger-text);
    }

    .notif-icon.info{
        background:var(--info-soft);
        color:var(--info-text);
    }

    .notif-title{
        font-weight:700;
        color:#33403b;
        margin-bottom:.35rem;
    }

    .notif-text{
        color:#69746f;
        line-height:1.65;
        margin-bottom:.55rem;
        font-size:.95rem;
    }

    .notif-time{
        color:#8a938e;
        font-size:.85rem;
    }

    .notif-badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:110px;
        height:32px;
        padding:0 .9rem;
        border-radius:999px;
        font-size:.82rem;
        font-weight:600;
        border:1px solid transparent;
    }

    .notif-badge.success{
        background:var(--success-soft);
        color:var(--success-text);
        border-color:#b7d2b6;
    }

    .notif-badge.warning{
        background:var(--warning-soft);
        color:var(--warning-text);
        border-color:#e3c98b;
    }

    .notif-badge.danger{
        background:var(--danger-soft);
        color:var(--danger-text);
        border-color:#e1aba5;
    }

    .notif-badge.info{
        background:var(--info-soft);
        color:var(--info-text);
        border-color:#cfddd5;
    }

    .summary-card{
        border:1px solid #e0d7cb;
        border-radius:1.25rem;
        background:#fffdfa;
        padding:1rem;
        height:100%;
    }

    .summary-box{
        border:1px solid #e3d9cd;
        border-radius:1rem;
        background:#fffcf8;
        text-align:center;
        padding:1rem .5rem;
    }

    .summary-number{
        font-size:2rem;
        color:#33403b;
        line-height:1;
        margin-top:.4rem;
    }

    .filter-chip{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-height:40px;
        padding:.5rem 1rem;
        border-radius:999px;
        border:1px solid #ddd2c5;
        background:#fffdfa;
        color:#5f6963;
        font-size:.9rem;
        font-weight:500;
        margin:0 .5rem .5rem 0;
    }

    .logout-btn{
        background:transparent;
        border:0;
        color:#5b635f;
        padding:0;
        font-size:16px;
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
                    <a href="{{ route('mahasiswa.dashboard') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Dashboard</span>
                    </a>
                    <a href="{{ route('mahasiswa.fasilitas') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Fasilitas</span>
                    </a>
                    <a href="{{ route('mahasiswa.jadwal') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Jadwal</span>
                    </a>
                    <a href="{{ route('mahasiswa.pengajuan') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Pengajuan Saya</span>
                    </a>
                    <a href="{{ route('mahasiswa.pengembalian') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Pengembalian</span>
                    </a>
                    <a href="{{ route('mahasiswa.riwayat') }}" class="sidebar-link {{ request()->routeIs('mahasiswa.riwayat') ? 'active' : '' }}">
                        <span class="sidebar-dot"></span><span>Riwayat Peminjaman</span>
                    </a>
                    <a href="{{ route('mahasiswa.notifikasi') }}" class="sidebar-link active">
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
                        <h1 class="page-heading">Notifikasi Mahasiswa</h1>
                    </div>

                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <input type="text" class="form-control search-input" placeholder="Cari notifikasi">
                        <div class="search-dot"></div>
                    </div>
                </div>

                <div class="card intro-card shadow-none mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fs-5 fw-semibold mb-3">Pantau informasi terbaru akun mahasiswa</h2>
                        <p class="mb-4 text-secondary">
                            Halaman ini menampilkan pemberitahuan penting terkait pengajuan, persetujuan, jadwal penggunaan, pengembalian, dan aktivitas akun mahasiswa.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-main">Tandai Sudah Dibaca</button>
                            <button class="btn btn-soft">Lihat Semua</button>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <span class="filter-chip">Semua</span>
                    <span class="filter-chip">Persetujuan</span>
                    <span class="filter-chip">Jadwal</span>
                    <span class="filter-chip">Pengembalian</span>
                    <span class="filter-chip">Sistem</span>
                </div>

                <div class="row g-4">
                    <div class="col-xl-8">
                        <div class="section-title-small">Daftar Notifikasi</div>

                        <div class="d-grid gap-3">
                            @forelse($notifikasi as $notif)
                                @php
                                    $iconClass = 'info';
                                    $icon = 'bi-info-circle-fill';
                                    $title = 'Pemberitahuan';
                                    $text = 'Status pengajuan Anda telah diperbarui menjadi: ' . str_replace('_', ' ', $notif->status);
                                    
                                    if(in_array($notif->status, ['menunggu_dosen', 'menunggu_admin', 'menunggu_kepala_sbum', 'menunggu_pic'])) {
                                        $iconClass = 'warning';
                                        $icon = 'bi-hourglass-split';
                                        $title = 'Menunggu verifikasi';
                                        $text = 'Pengajuan untuk <strong>' . ($notif->jenis_peminjaman === 'ruangan' ? ($notif->ruangan->first()->nama_ruangan ?? 'Ruangan') : ($notif->barang->first()->nama_barang ?? 'Barang')) . '</strong> masih menunggu proses verifikasi.';
                                    } elseif($notif->status === 'disetujui') {
                                        $iconClass = 'success';
                                        $icon = 'bi-check-circle-fill';
                                        $title = 'Pengajuan disetujui';
                                        $text = 'Pengajuan peminjaman <strong>' . ($notif->jenis_peminjaman === 'ruangan' ? ($notif->ruangan->first()->nama_ruangan ?? 'Ruangan') : ($notif->barang->first()->nama_barang ?? 'Barang')) . '</strong> untuk kegiatan ' . $notif->nama_kegiatan . ' telah disetujui.';
                                    } elseif($notif->status === 'ditolak') {
                                        $iconClass = 'danger';
                                        $icon = 'bi-x-circle-fill';
                                        $title = 'Pengajuan ditolak';
                                        $text = 'Pengajuan <strong>' . ($notif->jenis_peminjaman === 'ruangan' ? ($notif->ruangan->first()->nama_ruangan ?? 'Ruangan') : ($notif->barang->first()->nama_barang ?? 'Barang')) . '</strong> ditolak.';
                                    } elseif($notif->status === 'selesai' || $notif->status === 'dikembalikan') {
                                        $iconClass = 'success';
                                        $icon = 'bi-arrow-repeat';
                                        $title = 'Pengembalian Selesai';
                                        $text = 'Fasilitas <strong>' . ($notif->jenis_peminjaman === 'ruangan' ? ($notif->ruangan->first()->nama_ruangan ?? 'Ruangan') : ($notif->barang->first()->nama_barang ?? 'Barang')) . '</strong> telah selesai dikembalikan.';
                                    }
                                @endphp
                                <div class="notif-card">
                                    <div class="notif-icon {{ $iconClass }}">
                                        <i class="bi {{ $icon }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                            <div>
                                                <div class="notif-title">{{ $title }}</div>
                                                <div class="notif-text">
                                                    {!! $text !!}
                                                </div>
                                            </div>
                                            <span class="notif-badge {{ $iconClass }}">{{ ucfirst($iconClass) }}</span>
                                        </div>
                                        <div class="notif-time">{{ \Carbon\Carbon::parse($notif->tanggal_pengajuan)->diffForHumans() }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="notif-card">
                                    <div class="flex-grow-1 text-center py-4 text-muted">
                                        Tidak ada notifikasi terbaru.
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="section-title-small">Ringkasan Notifikasi</div>

                        <div class="summary-card mb-4">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="summary-box">
                                        <div class="text-secondary">Belum Dibaca</div>
                                        <div class="summary-number">{{ $belumDibaca }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="summary-box">
                                        <div class="text-secondary">Hari Ini</div>
                                        <div class="summary-number">{{ $hariIni }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="summary-box">
                                        <div class="text-secondary">Persetujuan</div>
                                        <div class="summary-number">{{ $persetujuan }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="summary-box">
                                        <div class="text-secondary">Jadwal</div>
                                        <div class="summary-number">{{ $jadwal }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="summary-card">
                            <div class="fw-semibold mb-3">Catatan</div>
                            <div class="text-secondary" style="line-height:1.8;">
                                Notifikasi akan muncul otomatis saat ada perubahan status pengajuan, jadwal penggunaan, dan proses pengembalian fasilitas mahasiswa.
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection
