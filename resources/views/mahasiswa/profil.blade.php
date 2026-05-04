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

    .profile-avatar{
        width:140px;
        height:180px;
        background:#d7e1d7;
        border-radius:50%;
        margin:0 auto 1rem;
    }

    .status-badge-soft{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:150px;
        height:32px;
        border-radius:999px;
        background:#e5eee5;
        color:#557b58;
        border:1px solid #d2dfd2;
        font-size:.9rem;
    }

    .profile-left p,
    .profile-left div,
    .profile-right label,
    .activity-title{
        color:#5d6762;
    }

    .line-separator{
        border-bottom:1px solid #e2d9ce;
        margin:.75rem 0 1.5rem;
    }

    .soft-input{
        height:50px;
        border-radius:1rem;
        border:1px solid #dfd4c8;
        background:#fffdfa;
    }

    .soft-input[readonly]{
        background:#fffdfa;
        color:#4d5852;
    }

    .btn-soft{
        background:#fffdfa;
        border:1px solid #dfd4c8;
        border-radius:1rem;
        height:46px;
        min-width:170px;
        color:#5f6963;
        font-weight:500;
    }

    .btn-soft:hover{
        background:#f7f2eb;
    }

    .activity-card{
        border:1px solid #e0d7cb;
        border-radius:1.25rem;
        background:#fffdfa;
        padding:1rem;
        text-align:center;
        min-height:86px;
    }

    .activity-number{
        font-size:2rem;
        line-height:1;
        color:#33403b;
        margin-top:.5rem;
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
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Notifikasi</span>
                    </a>
                    <a href="{{ route('mahasiswa.profil') }}" class="sidebar-link active">
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
                        <h1 class="page-heading">Profil Mahasiswa</h1>
                    </div>

                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <input type="text" class="form-control search-input" placeholder="Cari data">
                        <div class="search-dot"></div>
                    </div>
                </div>

                <div class="card intro-card shadow-none mb-5">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fs-5 fw-semibold mb-3">Kelola biodata dan akun mahasiswa</h2>
                        <p class="mb-4 text-secondary">
                            Halaman ini menampilkan informasi profil, data akademik, kontak, dan ringkasan aktivitas peminjaman mahasiswa.
                        </p>
                        <button class="btn btn-main">Edit Profil</button>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-xl-3">
                        <div class="profile-left">
                            <div class="text-center mb-4">
                                <div class="profile-avatar"></div>
                                <div class="fw-semibold mb-2">{{ auth()->user()->nama_lengkap }}</div>
                                <div class="text-secondary mb-3">Mahasiswa TRPL</div>
                                <span class="status-badge-soft">Aktif</span>
                            </div>

                            <div class="mb-4">
                                <div class="text-secondary mb-2">NIM</div>
                                <div>{{ auth()->user()->nim ?? '4342511024' }}</div>
                                <div class="line-separator"></div>
                            </div>

                            <div class="mb-4">
                                <div class="text-secondary mb-2">Program Studi</div>
                                <div>Teknologi Rekayasa Perangkat Lunak</div>
                                <div class="line-separator"></div>
                            </div>

                            <div class="mb-4">
                                <div class="text-secondary mb-2">Email</div>
                                <div>{{ auth()->user()->email }}</div>
                                <div class="line-separator"></div>
                            </div>

                            <div class="mb-4">
                                <div class="text-secondary mb-2">Nomor HP</div>
                                <div>08xx-xxxx-xxxx</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-5">
                        <div class="mb-4 fw-semibold text-secondary">Data Diri</div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control soft-input" value="{{ auth()->user()->nama_lengkap }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-semibold">NIM</label>
                                <input type="text" class="form-control soft-input" value="{{ auth()->user()->nim ?? '4342511024' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-semibold">Tempat, Tanggal Lahir</label>
                                <input type="text" class="form-control soft-input" value="Batam, 12 April 2004" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-semibold">Jenis Kelamin</label>
                                <input type="text" class="form-control soft-input" value="Laki-laki" readonly>
                            </div>
                        </div>

                        <div class="mb-4 fw-semibold text-secondary">Kontak & Keamanan</div>

                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Email</label>
                            <input type="text" class="form-control soft-input" value="{{ auth()->user()->email }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold">Password</label>
                            <input type="password" class="form-control soft-input" value="123456789" readonly>
                        </div>

                        <button class="btn btn-soft">Ubah Password</button>
                    </div>

                    <div class="col-xl-4">
                        <div class="mb-4 fw-semibold text-secondary">Ringkasan Aktivitas</div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="activity-card">
                                    <div class="activity-title">Pengajuan</div>
                                    <div class="activity-number">8</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="activity-card">
                                    <div class="activity-title">Disetujui</div>
                                    <div class="activity-number">5</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="activity-card">
                                    <div class="activity-title">Pengembalian</div>
                                    <div class="activity-number">4</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="activity-card">
                                    <div class="activity-title">Notifikasi</div>
                                    <div class="activity-number">3</div>
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
