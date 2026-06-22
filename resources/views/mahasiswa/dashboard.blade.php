@extends('layout.app')

@section('content')
<style>
    :root {
        --bg-page: #f5f2ec;
        --bg-panel: #fcfbf8;
        --bg-soft: #eef4ee;
        --bg-soft-2: #f4f7f4;
        --line: #e6ddd2;
        --text: #33403b;
        --muted: #7b8681;
        --primary: #587a68;
        --primary-dark: #466454;
        --success-soft: #dfeedd;
        --warning-soft: #f3e7c8;
    }

    body {
        margin: 0;
        background: var(--bg-page);
        font-family: Arial, Helvetica, sans-serif;
        color: var(--text);
    }

    .dash-wrap {
        min-height: 100vh;
        padding: 24px;
        background: var(--bg-page);
    }

    .dash-shell {
        max-width: 1440px;
        margin: 0 auto;
        background: var(--bg-panel);
        border: 1px solid var(--line);
        border-radius: 32px;
        overflow: hidden;
        display: grid;
        grid-template-columns: 270px 1fr;
        min-height: calc(100vh - 48px);
    }

    .dash-sidebar {
        border-right: 1px solid var(--line);
        padding: 22px 18px;
        display: flex;
        flex-direction: column;
        background: rgba(255,255,255,.25);
    }

    .brand-box {
        padding: 6px 8px 20px;
    }

    .brand-logo {
        width: 110px;
        max-width: 100%;
        height: auto;
        object-fit: contain;
    }

    .side-menu {
        display: grid;
        gap: 10px;
        margin-top: 12px;
    }

    .side-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 14px;
        border-radius: 16px;
        color: #54615b;
        text-decoration: none;
        font-size: 15px;
        transition: .2s ease;
    }

    .side-link:hover {
        background: #f3f7f3;
        color: var(--primary-dark);
    }

    .side-link.active {
        background: #edf3ee;
        color: var(--primary-dark);
        font-weight: 700;
    }

    .side-icon {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #dfe9e1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        flex-shrink: 0;
    }

    .logout-box {
        margin-top: auto;
        padding: 14px;
    }

    .logout-btn {
        background: transparent;
        border: 0;
        color: #5b635f;
        padding: 0;
        font-size: 16px;
    }

    .dash-main {
        padding: 26px 24px 28px;
    }

    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 22px;
    }

    .page-title {
        font-size: 19px;
        color: #7d8781;
        margin-bottom: 22px;
    }

    .page-subtitle {
        font-size: 28px;
        font-weight: 400;
        margin: 0 0 24px;
        color: var(--text);
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .search-input {
        width: 250px;
        height: 48px;
        border: 1px solid #ddd2c5;
        border-radius: 16px;
        padding: 0 18px;
        outline: none;
        background: #fffdfa;
        color: var(--text);
    }

    .search-dot {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #ccd8cc;
        flex-shrink: 0;
    }

    .content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 290px;
        gap: 28px;
    }

    .welcome-card {
        background: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 28px;
        padding: 30px 34px;
        min-height: 176px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .welcome-title {
        font-size: 31px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .welcome-text {
        color: #55635d;
        line-height: 1.8;
        max-width: 700px;
        margin-bottom: 24px;
    }

    .hero-actions {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
    }

    .btn-main,
    .btn-soft {
        min-width: 190px;
        height: 50px;
        border-radius: 16px;
        font-weight: 700;
        border: 1px solid #d8cfc2;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .btn-main {
        background: var(--primary);
        color: #fff;
        border-color: transparent;
    }

    .btn-main:hover {
        background: var(--primary-dark);
        color: #fff;
    }

    .btn-soft {
        background: #fffdfa;
        color: #6b756f;
    }

    .btn-soft:hover {
        background: #f8f4ee;
        color: #4f5954;
    }

    .mini-title {
        font-size: 15px;
        font-weight: 700;
        margin: 4px 0 14px;
        color: #52605a;
    }

    .schedule-card,
    .status-card,
    .note-card,
    .quick-card {
        background: #fffdfa;
        border: 1px solid #e0d7cb;
        border-radius: 22px;
    }

    .schedule-card {
        padding: 22px 22px 18px;
    }

    .schedule-name {
        font-size: 16px;
        font-weight: 400;
        margin-bottom: 12px;
        color: #5a615e;
    }

    .schedule-time {
        font-size: 15px;
        color: #4d5953;
        margin-bottom: 14px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 116px;
        padding: 9px 18px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 700;
        border: 1px solid transparent;
    }

    .status-pill.approved {
        background: #dcebd7;
        color: #557b58;
        border-color: #b7d2b6;
    }

    .status-pill.pending {
        background: var(--warning-soft);
        color: #92723c;
        border-color: #e3c98b;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin: 26px 0 32px;
    }

    .stat-box {
        padding: 10px 4px;
    }

    .stat-label {
        font-size: 15px;
        color: #8a948e;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 64px;
        line-height: 1;
        font-weight: 400;
        color: #31413a;
    }

    .bottom-grid {
        display: grid;
        grid-template-columns: 420px minmax(0, 1fr);
        gap: 34px;
    }

    .quick-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .quick-card {
        padding: 28px 26px;
        min-height: 116px;
    }

    .quick-title {
        font-size: 16px;
        color: #5e6762;
        margin-bottom: 12px;
    }

    .quick-desc {
        font-size: 15px;
        color: #8a928d;
        line-height: 1.6;
    }

    .status-list {
        display: grid;
        gap: 18px;
    }

    .status-card {
        padding: 20px 22px;
    }

    .status-top {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: center;
        margin-bottom: 12px;
    }

    .status-code {
        font-size: 16px;
        color: #707975;
        margin-bottom: 10px;
    }

    .status-name {
        font-size: 15px;
        color: #4e5953;
        margin-bottom: 8px;
    }

    .progress-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        align-items: center;
        margin-top: 8px;
    }

    .progress-line {
        height: 8px;
        border-radius: 999px;
        background: #d9d4cc;
        position: relative;
        overflow: hidden;
    }

    .progress-line.active::before {
        content: "";
        position: absolute;
        inset: 0;
        background: #557a67;
    }

    .progress-labels {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-top: 6px;
        font-size: 13px;
        color: #7a847e;
    }

    .note-card {
        padding: 18px 22px;
    }

    .note-title {
        font-size: 16px;
        color: #5e6662;
        margin-bottom: 8px;
    }

    .note-text {
        color: #7c8580;
        line-height: 1.5;
        margin: 0;
    }

    .hello-name {
        font-weight: 700;
    }

    @media (max-width: 1200px) {
        .content-grid,
        .bottom-grid {
            grid-template-columns: 1fr;
        }

        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .search-input {
            width: 220px;
        }
    }

    @media (max-width: 991.98px) {
        .dash-shell {
            grid-template-columns: 1fr;
        }

        .dash-sidebar {
            border-right: 0;
            border-bottom: 1px solid var(--line);
        }

        .logout-box {
            margin-top: 12px;
        }
    }

    @media (max-width: 767.98px) {
        .dash-wrap {
            padding: 10px;
        }

        .dash-main {
            padding: 18px 16px 22px;
        }

        .topbar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            width: 100%;
        }

        .search-input {
            width: 100%;
        }

        .stats-row,
        .quick-grid {
            grid-template-columns: 1fr;
        }

        .status-top {
            flex-direction: column;
            align-items: flex-start;
        }

        .welcome-card {
            padding: 24px 20px;
        }

        .btn-main,
        .btn-soft {
            width: 100%;
            min-width: 0;
        }

        .hero-actions {
            flex-direction: column;
        }
    }
</style>

<div class="dash-wrap">
    <div class="dash-shell">
        <aside class="dash-sidebar">
            <div class="brand-box">
                <img src="{{ asset('assets/images/logo-sbum-icon.png') }}" alt="SBUM" class="brand-logo">
            </div>

            <nav class="side-menu">
                <a href="#" class="side-link active">
                    <span class="side-icon"></span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('mahasiswa.fasilitas') }}" class="side-link">
                    <span class="side-icon"></span>
                    <span>Fasilitas</span>
                </a>
                <a href="{{ route('mahasiswa.jadwal') }}" class="side-link">
                    <span class="side-icon"></span>
                    <span>Jadwal</span>
                </a>
                <a href="{{ route('mahasiswa.pengajuan') }}" class="side-link">
                    <span class="side-icon"></span>
                    <span>Pengajuan Saya</span>
                </a>
                <a href="{{ route('mahasiswa.pengembalian') }}" class="side-link">
                    <span class="side-icon"></span>
                    <span>Pengembalian</span>
                </a>
                <a href="{{ route('mahasiswa.notifikasi') }}" class="side-link">
                    <span class="side-icon"></span>
                    <span>Notifikasi</span>
                </a>
                <a href="{{ route ('mahasiswa.profil')}}" class="side-link">
                    <span class="side-icon"></span>
                    <span>Profil</span>
                </a>
            </nav>

            <div class="logout-box">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </aside>

        <main class="dash-main">
            @if(session('success'))
                <div class="alert alert-success rounded-4 border-0 mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="topbar">
                <div>
                    <div class="page-title">Mahasiswa</div>
                    <h1 class="page-subtitle">Dashboard Mahasiswa</h1>
                </div>

                <div class="search-box">
                    <input type="text" class="search-input" placeholder="Cari fasilitas atau ID">
                    <div class="search-dot"></div>
                </div>
            </div>

            <div class="content-grid">
                <div class="welcome-card">
                    <div class="welcome-title">Halo, <span class="hello-name">{{ auth()->user()->nama_lengkap }}</span> 👋</div>
                    <div class="welcome-text">
                        Kelola peminjaman ruangan dan fasilitas kampus dari satu dashboard yang sederhana dan mudah dipantau.
                    </div>
                    <div class="hero-actions">
                        <a href="#" class="btn-main">Ajukan Peminjaman</a>
                        <a href="#" class="btn-soft">Lihat Jadwal</a>
                    </div>
                </div>

                <div>
                    <div class="mini-title">Jadwal Terdekat</div>
                    <div class="schedule-card">
                        <div class="schedule-name">Aula Utama Polibatam</div>
                        <div class="schedule-time">12 Apr 2026 · 08.00 - 12.00</div>
                        <span class="status-pill approved">Disetujui</span>
                    </div>
                </div>
            </div>

            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-label">Pengajuan Aktif</div>
                    <div class="stat-value">0</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Menunggu Persetujuan</div>
                    <div class="stat-value">0</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Riwayat Selesai</div>
                    <div class="stat-value">0</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Notifikasi Baru</div>
                    <div class="stat-value">0</div>
                </div>
            </div>

            <div class="bottom-grid">
                <div>
                    <div class="mini-title">Aksi Cepat</div>
                    <div class="quick-grid">
                        <div class="quick-card">
                            <div class="quick-title">Ajukan Peminjaman</div>
                            <div class="quick-desc">Buat pengajuan baru</div>
                        </div>
                        <div class="quick-card">
                            <div class="quick-title">Cek Ketersediaan</div>
                            <div class="quick-desc">Lihat slot fasilitas</div>
                        </div>
                        <div class="quick-card">
                            <div class="quick-title">Status Pengajuan</div>
                            <div class="quick-desc">Pantau progres verifikasi</div>
                        </div>
                        <div class="quick-card">
                            <div class="quick-title">Ajukan Pengembalian</div>
                            <div class="quick-desc">Submit return fasilitas</div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="mini-title">Status Pengajuan Terbaru</div>
                    <div class="status-list">
                        <div class="status-card">
                            <div class="status-top">
                                <div>
                                    <div class="status-code">SBUM-2026-0148 · Aula Utama Polibatam</div>
                                    <div class="status-name">Seminar Mahasiswa Baru · 12 Apr 2026</div>
                                </div>
                                <span class="status-pill pending">Menunggu Admin</span>
                            </div>

                            <div class="progress-row">
                                <div class="progress-line active"></div>
                                <div class="progress-line active"></div>
                                <div class="progress-line"></div>
                            </div>
                            <div class="progress-labels">
                                <span>Diajukan</span>
                                <span>Dosen</span>
                                <span>Admin</span>
                            </div>
                        </div>

                        <div class="status-card">
                            <div class="status-top">
                                <div>
                                    <div class="status-code">SBUM-2026-0136 · LCD Projector Epson</div>
                                    <div class="status-name">Presentasi Kelas · 10 Apr 2026</div>
                                </div>
                                <span class="status-pill approved">Disetujui</span>
                            </div>

                            <div class="progress-row">
                                <div class="progress-line active"></div>
                                <div class="progress-line active"></div>
                                <div class="progress-line active"></div>
                            </div>
                        </div>

                        <div class="note-card">
                            <div class="note-title">Catatan Dashboard</div>
                            <p class="note-text">
                                Versi ini lebih fokus ke aksi cepat, jadwal terdekat, dan status pengajuan agar mahasiswa lebih cepat memahami apa yang harus dilakukan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
