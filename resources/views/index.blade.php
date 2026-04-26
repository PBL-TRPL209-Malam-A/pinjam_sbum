@extends('layout.app')

@section('content')
<style>
    :root {
        --sbum-primary: #1f5f4d;
        --sbum-primary-dark: #184b3c;
        --sbum-primary-soft: #e8f1ec;
        --sbum-bg: #f4f0ea;
        --sbum-card: #ffffff;
        --sbum-text: #24332d;
        --sbum-muted: #6e7b75;
        --sbum-border: #dde6df;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background: #1f1f1f;
        color: var(--sbum-text);
    }

    .landing-page {
        background: #1f1f1f;
        min-height: 100vh;
        padding: 24px;
    }

    .landing-shell {
        position: relative;
        isolation: isolate;
        background: rgba(244, 240, 234, 0.90);
        border-radius: 26px;
        overflow: hidden;
        max-width: 1400px;
        margin: 0 auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
    }

    .landing-shell::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 0;
        background:
            linear-gradient(
                rgba(246, 241, 233, 0.84),
                rgba(246, 241, 233, 0.92)
            ),
            url('{{ asset('assets/images/gedungpoli.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        opacity: 1;
    }

    .landing-shell > * {
        position: relative;
        z-index: 1;
    }

    .navbar {
        padding: 18px 0;
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.03);
    }

    .brand-mark {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        background: linear-gradient(160deg, #f1f6f2, #d8e8de);
        color: var(--sbum-primary);
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        line-height: 1;
        box-shadow: inset 0 0 0 1px rgba(31, 95, 77, 0.08);
    }

    .brand-text {
        line-height: 1.05;
    }

    .brand-title {
        font-size: 1.45rem;
        font-weight: 800;
        color: var(--sbum-primary);
        letter-spacing: 0.02em;
    }

    .brand-subtitle {
        font-size: 0.72rem;
        color: var(--sbum-muted);
    }

    .nav-link {
        color: #44524c;
        font-weight: 600;
        font-size: 0.96rem;
        padding: 0.6rem 0.95rem !important;
    }

    .nav-link:hover,
    .nav-link.active {
        color: var(--sbum-primary);
    }

    .btn-outline-sbum {
        border: 1px solid rgba(31, 95, 77, 0.28);
        color: var(--sbum-primary);
        background: rgba(255, 255, 255, 0.92);
        font-weight: 700;
        padding: 0.72rem 1.2rem;
        border-radius: 12px;
    }

    .btn-outline-sbum:hover {
        background: #f3f8f5;
        color: var(--sbum-primary);
        border-color: rgba(31, 95, 77, 0.45);
    }

    .btn-sbum {
        background: var(--sbum-primary);
        border: 0;
        color: #fff;
        font-weight: 700;
        padding: 0.78rem 1.25rem;
        border-radius: 12px;
        box-shadow: 0 12px 20px rgba(31, 95, 77, 0.18);
    }

    .btn-sbum:hover {
        background: var(--sbum-primary-dark);
        color: #fff;
    }

    .hero {
        position: relative;
        overflow: hidden;
        padding: 32px 0 12px;
    }

    .hero::before,
    .hero::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        background: rgba(31, 95, 77, 0.06);
        z-index: 0;
    }

    .hero::before {
        width: 320px;
        height: 320px;
        top: -150px;
        left: -120px;
    }

    .hero::after {
        width: 420px;
        height: 420px;
        right: -130px;
        top: -60px;
    }

    .section-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.6rem 0.9rem;
        border-radius: 999px;
        background: #e4efe8;
        color: var(--sbum-primary);
        font-size: 0.77rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .hero-title {
        font-size: clamp(2rem, 4vw, 3.6rem);
        line-height: 1.08;
        font-weight: 800;
        margin: 1rem 0 0.95rem;
        max-width: 650px;
    }

    .hero-desc {
        font-size: 1.02rem;
        line-height: 1.8;
        color: #5f6e67;
        max-width: 610px;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 1.75rem;
    }

    .dashboard-card {
        position: relative;
        z-index: 1;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(31, 95, 77, 0.08);
        border-radius: 24px;
        box-shadow: 0 20px 45px rgba(34, 44, 39, 0.08);
        overflow: hidden;
        backdrop-filter: blur(2px);
    }

    .dashboard-top {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(31, 95, 77, 0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 78px 1fr;
        min-height: 390px;
    }

    .dashboard-sidebar {
        background: linear-gradient(180deg, #edf4ef, #f7faf8);
        border-right: 1px solid rgba(31, 95, 77, 0.08);
        padding: 1rem 0.75rem;
    }

    .dashboard-menu {
        display: grid;
        gap: 0.55rem;
    }

    .dashboard-menu-item {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid rgba(31, 95, 77, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--sbum-primary);
        font-size: 1rem;
    }

    .dashboard-content {
        padding: 1.1rem;
        background: linear-gradient(180deg, rgba(251, 252, 251, 0.95), rgba(244, 247, 245, 0.94));
    }

    .welcome-box {
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(239, 246, 241, 0.96), rgba(255, 255, 255, 0.97));
        border: 1px solid rgba(31, 95, 77, 0.08);
        padding: 1rem 1rem 0.9rem;
        margin-bottom: 1rem;
    }

    .welcome-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.4rem 0.7rem;
        background: rgba(31, 95, 77, 0.08);
        color: var(--sbum-primary);
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .dashboard-mini-actions {
        display: flex;
        gap: 0.65rem;
        flex-wrap: wrap;
        margin-top: 0.8rem;
    }

    .mini-action {
        border-radius: 10px;
        padding: 0.6rem 0.8rem;
        border: 1px solid rgba(31, 95, 77, 0.12);
        background: #fff;
        color: #365448;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.8rem;
    }

    .dashboard-stat {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(31, 95, 77, 0.08);
        border-radius: 16px;
        padding: 0.9rem;
    }

    .dashboard-stat-label {
        font-size: 0.72rem;
        color: #7a8882;
        margin-bottom: 0.2rem;
    }

    .dashboard-stat-value {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--sbum-text);
    }

    .schedule-box {
        margin-top: 0.9rem;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(31, 95, 77, 0.08);
        padding: 0.95rem;
    }

    .stats-section {
        margin-top: 18px;
    }

    .stats-card {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(31, 95, 77, 0.08);
        border-radius: 20px;
        padding: 1.2rem 1.1rem;
        height: 100%;
        display: flex;
        gap: 0.95rem;
        align-items: center;
        backdrop-filter: blur(2px);
    }

    .stats-icon,
    .feature-icon,
    .persona-icon,
    .timeline-step-badge {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        background: #e4efe8;
        color: var(--sbum-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .stats-number {
        font-size: 2rem;
        line-height: 1;
        font-weight: 800;
    }

    .section-title {
        font-size: 1.55rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .section-subtitle {
        color: var(--sbum-muted);
        line-height: 1.8;
        max-width: 720px;
    }

    .feature-card,
    .persona-card,
    .cta-box,
    .facility-card,
    .timeline-card {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(31, 95, 77, 0.08);
        border-radius: 20px;
        height: 100%;
        box-shadow: 0 10px 25px rgba(24, 43, 35, 0.04);
        backdrop-filter: blur(2px);
    }

    .feature-card {
        padding: 1.4rem;
    }

    .feature-card h3,
    .persona-card h3,
    .cta-box h3 {
        font-size: 1.1rem;
        font-weight: 800;
        margin: 1rem 0 0.7rem;
    }

    .feature-card p,
    .persona-card p,
    .cta-box p,
    .timeline-card p {
        color: var(--sbum-muted);
        line-height: 1.75;
        margin-bottom: 0;
    }

    .side-stack {
        display: grid;
        gap: 1rem;
    }

    .persona-card,
    .cta-box {
        padding: 1.3rem;
    }

    .persona-list,
    .contact-list,
    .footer-list {
        padding-left: 1rem;
        margin-bottom: 0;
        color: var(--sbum-muted);
        line-height: 1.8;
    }

    .timeline-card {
        padding: 1rem;
    }

    .timeline-arrow {
        color: #89a397;
        font-size: 1.2rem;
        display: none;
    }

    .facility-card {
        overflow: hidden;
    }

    .facility-image {
        height: 170px;
        position: relative;
    }

    .facility-image.image-1 {
        background: linear-gradient(135deg, #7b5330, #d8b17a);
    }

    .facility-image.image-2 {
        background: linear-gradient(135deg, #435d6d, #9fc3d7);
    }

    .facility-image.image-3 {
        background: linear-gradient(135deg, #72592f, #d9b782);
    }

    .facility-image.image-4 {
        background: linear-gradient(135deg, #6aa05b, #b8dfa2);
    }

    .facility-image::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.02), rgba(0, 0, 0, 0.28));
    }

    .facility-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 1;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.92);
        color: var(--sbum-primary);
        font-size: 0.75rem;
        font-weight: 800;
        padding: 0.36rem 0.7rem;
    }

    .facility-body {
        padding: 1rem;
    }

    .facility-meta {
        display: flex;
        gap: 0.85rem;
        flex-wrap: wrap;
        color: var(--sbum-muted);
        font-size: 0.85rem;
        margin: 0.85rem 0 1rem;
    }

    .footer {
        background: linear-gradient(135deg, rgba(24, 75, 60, 0.96), rgba(31, 95, 77, 0.96));
        color: rgba(255, 255, 255, 0.92);
        margin-top: 2rem;
        padding: 2.4rem 0 1rem;
    }

    .footer small,
    .footer p,
    .footer a,
    .footer li {
        color: rgba(255, 255, 255, 0.72);
        text-decoration: none;
    }

    .footer a:hover {
        color: #fff;
    }

    .social-link {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.22);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }

    .social-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    @media (min-width: 992px) {
        .timeline-arrow {
            display: inline-flex;
            align-self: center;
            justify-content: center;
        }
    }

    @media (max-width: 1199.98px) {
        .dashboard-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 991.98px) {
        .landing-page {
            padding: 14px;
        }

        .landing-shell {
            border-radius: 20px;
        }

        .hero {
            padding-top: 22px;
        }

        .hero::after {
            width: 280px;
            height: 280px;
            right: -110px;
            top: -30px;
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-sidebar {
            display: none;
        }

        .dashboard-card {
            margin-top: 1rem;
        }

        .stats-card,
        .feature-card,
        .persona-card,
        .cta-box,
        .timeline-card,
        .facility-card {
            border-radius: 18px;
        }
    }

    @media (max-width: 767.98px) {
        .navbar {
            padding: 14px 0;
        }

        .landing-page {
            padding: 8px;
        }

        .landing-shell {
            border-radius: 16px;
        }

        .hero {
            padding-top: 14px;
        }

        .hero-actions {
            flex-direction: column;
        }

        .hero-actions .btn {
            width: 100%;
        }

        .dashboard-stats {
            grid-template-columns: 1fr 1fr;
        }

        .dashboard-top {
            flex-direction: column;
            align-items: flex-start;
        }

        .section-title {
            font-size: 1.35rem;
        }

        .stats-number {
            font-size: 1.6rem;
        }

        .facility-image {
            height: 155px;
        }
    }

    @media (max-width: 575.98px) {
        .dashboard-stats {
            grid-template-columns: 1fr;
        }

        .stats-card {
            align-items: flex-start;
        }

        .brand-title {
            font-size: 1.2rem;
        }

        .brand-mark {
            width: 48px;
            height: 48px;
            font-size: 1.2rem;
        }
    }
</style>

<div class="landing-page">
    <div class="landing-shell">
        <nav class="navbar navbar-expand-lg">
            <div class="container px-3 px-lg-4">
                <a class="navbar-brand d-flex align-items-center gap-3" href="#beranda">
                    <span class="brand-mark">SBM</span>
                    <span class="brand-text d-none d-sm-block">
                        <span class="d-block brand-title">SBUM</span>
                        <span class="d-block brand-subtitle">Sistem Booking umum &amp; Manajemen Fasilitas</span>
                    </span>
                </a>

                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav mx-auto mb-3 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" href="#beranda">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link" href="#fasilitas">Fasilitas</a></li>
                        <li class="nav-item"><a class="nav-link" href="#alur">Alur Peminjaman</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                        <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                    </ul>

                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-sbum">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-sbum">Daftar</a>
                    </div>
                </div>
            </div>
        </nav>

        <section class="hero" id="beranda">
            <div class="container px-3 px-lg-4 position-relative" style="z-index:1;">
                <div class="row align-items-center g-4 g-xl-5">
                    <div class="col-lg-6">
                        <span class="section-badge"><i class="bi bi-shield-check"></i> Sistem Booking umum &amp; Manajemen Fasilitas</span>
                        <h1 class="hero-title">Solusi Mudah untuk Peminjaman Fasilitas Kampus</h1>
                        <p class="hero-desc">
                            SBUM membantu mahasiswa dan admin dalam mengelola pengajuan fasilitas kampus secara cepat,
                            transparan, dan efisien. Mulai dari aula, laboratorium, ruang seminar, hingga lapangan dapat
                            diajukan dalam satu dashboard yang rapi.
                        </p>
                        <div class="hero-actions">
                            <a href="#fasilitas" class="btn btn-sbum px-4">Ajukan Peminjaman</a>
                            <a href="#fitur" class="btn btn-outline-sbum px-4">Lihat Fasilitas</a>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="dashboard-card">
                            <div class="dashboard-top">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="brand-mark" style="width:44px;height:44px;font-size:1rem;border-radius:14px;">SBM</span>
                                    <div>
                                        <div class="fw-bold">Dashboard Mahasiswa</div>
                                        <div class="text-muted small">Ringkasan aktivitas peminjaman fasilitas</div>
                                    </div>
                                </div>
                                <div class="small text-muted"><i class="bi bi-person-circle me-1"></i> Mahasiswa</div>
                            </div>
                            <div class="dashboard-grid">
                                <div class="dashboard-sidebar">
                                    <div class="dashboard-menu">
                                        <div class="dashboard-menu-item"><i class="bi bi-grid"></i></div>
                                        <div class="dashboard-menu-item"><i class="bi bi-building"></i></div>
                                        <div class="dashboard-menu-item"><i class="bi bi-calendar-event"></i></div>
                                        <div class="dashboard-menu-item"><i class="bi bi-chat-square-text"></i></div>
                                        <div class="dashboard-menu-item"><i class="bi bi-bell"></i></div>
                                        <div class="dashboard-menu-item"><i class="bi bi-person"></i></div>
                                    </div>
                                </div>
                                <div class="dashboard-content">
                                    <div class="welcome-box">
                                        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                            <div>
                                                <div class="fw-bold mb-1">Halo, PoliBatam 👋</div>
                                                <div class="text-muted small">Kelola peminjaman ruangan dan fasilitas kampus dari satu dashboard.</div>
                                            </div>
                                            <span class="welcome-pill"><i class="bi bi-check-circle-fill"></i> Aktif</span>
                                        </div>
                                        <div class="dashboard-mini-actions">
                                            <span class="mini-action">Ajukan Peminjaman</span>
                                            <span class="mini-action">Lihat Jadwal</span>
                                            <span class="mini-action">Riwayat</span>
                                        </div>
                                    </div>

                                    <div class="dashboard-stats">
                                        <div class="dashboard-stat">
                                            <div class="dashboard-stat-label">Pengajuan Aktif</div>
                                            <div class="dashboard-stat-value">2</div>
                                        </div>
                                        <div class="dashboard-stat">
                                            <div class="dashboard-stat-label">Menunggu Persetujuan</div>
                                            <div class="dashboard-stat-value">1</div>
                                        </div>
                                        <div class="dashboard-stat">
                                            <div class="dashboard-stat-label">Riwayat Selesai</div>
                                            <div class="dashboard-stat-value">5</div>
                                        </div>
                                        <div class="dashboard-stat">
                                            <div class="dashboard-stat-label">Notifikasi Baru</div>
                                            <div class="dashboard-stat-value">3</div>
                                        </div>
                                    </div>

                                    <div class="schedule-box">
                                        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-2">
                                            <div class="fw-bold">Jadwal Terdekat</div>
                                            <span class="badge text-bg-success-subtle text-success-emphasis">Disetujui</span>
                                        </div>
                                        <div class="small text-muted">Aula Utama Polibatam</div>
                                        <div class="small mt-1">12 Apr 2026 · 08.00 - 12.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="stats-section">
            <div class="container px-3 px-lg-4">
                <div class="row g-3">
                    <div class="col-sm-6 col-xl-3">
                        <div class="stats-card">
                            <span class="stats-icon"><i class="bi bi-building"></i></span>
                            <div>
                                <div class="stats-number">120+</div>
                                <div class="fw-bold">Fasilitas Tersedia</div>
                                <div class="text-muted small">Aula, lab, ruang kelas, dan lapangan</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stats-card">
                            <span class="stats-icon"><i class="bi bi-journal-check"></i></span>
                            <div>
                                <div class="stats-number">350+</div>
                                <div class="fw-bold">Pengajuan Diproses</div>
                                <div class="text-muted small">Pencatatan permohonan lebih terstruktur</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stats-card">
                            <span class="stats-icon"><i class="bi bi-check2-circle"></i></span>
                            <div>
                                <div class="stats-number">95%</div>
                                <div class="fw-bold">Pengajuan Selesai Tepat Waktu</div>
                                <div class="text-muted small">Persetujuan dan penggunaan lebih cepat</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stats-card">
                            <span class="stats-icon"><i class="bi bi-clock-history"></i></span>
                            <div>
                                <div class="stats-number">24/7</div>
                                <div class="fw-bold">Akses Sistem Online</div>
                                <div class="text-muted small">Bisa diajukan kapan saja tanpa antre</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5" id="fitur">
            <div class="container px-3 px-lg-4">
                <div class="row g-4">
                    <div class="col-xl-9">
                        <div class="mb-4">
                            <h2 class="section-title">Fitur Utama</h2>
                            <p class="section-subtitle">Dirancang untuk mahasiswa dan admin agar proses peminjaman fasilitas kampus jadi lebih cepat, jelas, dan mudah dipantau.</p>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6 col-xl-3">
                                <div class="feature-card">
                                    <span class="feature-icon"><i class="bi bi-search"></i></span>
                                    <h3>Cari Fasilitas</h3>
                                    <p>Lihat daftar fasilitas kampus lengkap dengan informasi kapasitas, lokasi, dan status ketersediaan.</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="feature-card">
                                    <span class="feature-icon"><i class="bi bi-clipboard-plus"></i></span>
                                    <h3>Ajukan Peminjaman</h3>
                                    <p>Ajukan peminjaman fasilitas dengan formulir online yang cepat dan praktis.</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="feature-card">
                                    <span class="feature-icon"><i class="bi bi-graph-up-arrow"></i></span>
                                    <h3>Pantau Status</h3>
                                    <p>Pantau status pengajuan mulai dari menunggu, disetujui, hingga selesai digunakan.</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="feature-card">
                                    <span class="feature-icon"><i class="bi bi-bell"></i></span>
                                    <h3>Notifikasi Otomatis</h3>
                                    <p>Dapatkan informasi terbaru melalui notifikasi terkait pengajuan, jadwal, dan perubahan status.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3">
                        <div class="side-stack">
                            <div class="persona-card">
                                <h3>Untuk Siapa Sistem Ini?</h3>
                                <div class="d-flex gap-3 mt-3 mb-3 align-items-start">
                                    <span class="persona-icon"><i class="bi bi-mortarboard"></i></span>
                                    <div>
                                        <div class="fw-bold">Mahasiswa</div>
                                        <ul class="persona-list">
                                            <li>Melihat fasilitas</li>
                                            <li>Mengajukan peminjaman</li>
                                            <li>Melihat riwayat &amp; status</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="d-flex gap-3 align-items-start">
                                    <span class="persona-icon"><i class="bi bi-person-gear"></i></span>
                                    <div>
                                        <div class="fw-bold">Admin</div>
                                        <ul class="persona-list">
                                            <li>Verifikasi pengajuan</li>
                                            <li>Kelola data fasilitas</li>
                                            <li>Monitor jadwal &amp; laporan</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="cta-box">
                                <h3>Mulai kelola peminjaman fasilitas kampus dengan lebih mudah dan terstruktur.</h3>
                                <p class="mb-3">Cocok untuk sistem akademik modern yang butuh proses cepat, rapi, dan transparan.</p>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('login') }}" class="btn btn-sbum">Masuk Sekarang</a>
                                    <a href="{{ route('register') }}" class="btn btn-outline-sbum">Daftar Akun</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pb-2" id="alur">
            <div class="container px-3 px-lg-4">
                <div class="mb-4">
                    <h2 class="section-title">Alur Peminjaman</h2>
                    <p class="section-subtitle">Empat langkah sederhana untuk meminjam fasilitas kampus tanpa proses yang membingungkan.</p>
                </div>

                <div class="row g-3 align-items-stretch">
                    <div class="col-lg-3 col-md-6">
                        <div class="timeline-card h-100 d-flex gap-3">
                            <span class="timeline-step-badge">1</span>
                            <div>
                                <h6 class="fw-bold mb-2">Pilih Fasilitas</h6>
                                <p>Pilih fasilitas yang ingin digunakan sesuai kebutuhan acara atau kegiatan Anda.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-auto d-none d-lg-flex timeline-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="timeline-card h-100 d-flex gap-3">
                            <span class="timeline-step-badge">2</span>
                            <div>
                                <h6 class="fw-bold mb-2">Ajukan Peminjaman</h6>
                                <p>Isi form peminjaman dengan data dan jadwal penggunaan secara lengkap.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-auto d-none d-lg-flex timeline-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="timeline-card h-100 d-flex gap-3">
                            <span class="timeline-step-badge">3</span>
                            <div>
                                <h6 class="fw-bold mb-2">Verifikasi Admin</h6>
                                <p>Admin akan memverifikasi pengajuan dan memeriksa ketersediaan fasilitas.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-auto d-none d-lg-flex timeline-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="timeline-card h-100 d-flex gap-3">
                            <span class="timeline-step-badge">4</span>
                            <div>
                                <h6 class="fw-bold mb-2">Gunakan Fasilitas</h6>
                                <p>Pengajuan disetujui dan Anda dapat menggunakan fasilitas sesuai jadwal.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5" id="fasilitas">
            <div class="container px-3 px-lg-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
                    <div>
                        <h2 class="section-title mb-2">Fasilitas Unggulan</h2>
                        <p class="section-subtitle mb-0">Beberapa fasilitas favorit yang paling sering diajukan mahasiswa untuk kegiatan akademik dan organisasi.</p>
                    </div>
                    <a href="#" class="fw-bold text-decoration-none" style="color: var(--sbum-primary);">
                        Lihat Semua Fasilitas <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6 col-xl-3">
                        <div class="facility-card h-100">
                            <div class="facility-image image-1">
                                <span class="facility-badge">Tersedia</span>
                            </div>
                            <div class="facility-body">
                                <h6 class="fw-bold mb-2">Aula Utama Polibatam</h6>
                                <div class="facility-meta">
                                    <span><i class="bi bi-people me-1"></i> Kapasitas 200 Orang</span>
                                    <span><i class="bi bi-geo-alt me-1"></i> Gedung A Lt. 2</span>
                                </div>
                                <a href="#" class="btn btn-outline-sbum w-100">Detail</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="facility-card h-100">
                            <div class="facility-image image-2">
                                <span class="facility-badge">Tersedia</span>
                            </div>
                            <div class="facility-body">
                                <h6 class="fw-bold mb-2">Laboratorium Komputer 1</h6>
                                <div class="facility-meta">
                                    <span><i class="bi bi-pc-display me-1"></i> Kapasitas 40 Orang</span>
                                    <span><i class="bi bi-geo-alt me-1"></i> Gedung B Lt. 3</span>
                                </div>
                                <a href="#" class="btn btn-outline-sbum w-100">Detail</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="facility-card h-100">
                            <div class="facility-image image-3">
                                <span class="facility-badge">Tersedia</span>
                            </div>
                            <div class="facility-body">
                                <h6 class="fw-bold mb-2">Ruang Seminar</h6>
                                <div class="facility-meta">
                                    <span><i class="bi bi-mic me-1"></i> Kapasitas 80 Orang</span>
                                    <span><i class="bi bi-geo-alt me-1"></i> Gedung C Lt. 1</span>
                                </div>
                                <a href="#" class="btn btn-outline-sbum w-100">Detail</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="facility-card h-100">
                            <div class="facility-image image-4">
                                <span class="facility-badge">Tersedia</span>
                            </div>
                            <div class="facility-body">
                                <h6 class="fw-bold mb-2">Lapangan Serbaguna</h6>
                                <div class="facility-meta">
                                    <span><i class="bi bi-dribbble me-1"></i> Kapasitas 100 Orang</span>
                                    <span><i class="bi bi-geo-alt me-1"></i> Area Outdoor</span>
                                </div>
                                <a href="#" class="btn btn-outline-sbum w-100">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="footer" id="tentang">
            <div class="container px-3 px-lg-4">
                <div class="row g-4">
                    <div class="col-lg-4" id="kontak">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="brand-mark" style="background: rgba(255,255,255,0.14); color: #fff; box-shadow:none;">SBM</span>
                            <div>
                                <div class="fw-bold fs-4 text-white">SBUM</div>
                                <div class="small">Sistem Booking Uang Milik</div>
                            </div>
                        </div>
                        <p>Platform manajemen dan peminjaman fasilitas kampus yang terintegrasi, transparan, dan mudah digunakan.</p>
                    </div>

                    <div class="col-sm-6 col-lg-2">
                        <h6 class="fw-bold text-white mb-3">Link Cepat</h6>
                        <ul class="list-unstyled footer-list ps-0">
                            <li><a href="#beranda">Beranda</a></li>
                            <li><a href="#fasilitas">Fasilitas</a></li>
                            <li><a href="#alur">Alur Peminjaman</a></li>
                            <li><a href="#tentang">Tentang</a></li>
                        </ul>
                    </div>

                    <div class="col-sm-6 col-lg-2">
                        <h6 class="fw-bold text-white mb-3">Bantuan</h6>
                        <ul class="list-unstyled footer-list ps-0">
                            <li><a href="#">Panduan Pengguna</a></li>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">Kebijakan Privasi</a></li>
                            <li><a href="#">Syarat &amp; Ketentuan</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-2">
                        <h6 class="fw-bold text-white mb-3">Kontak</h6>
                        <ul class="list-unstyled footer-list ps-0">
                            <li>SBUM</li>
                            <li>Politeknik Negeri Batam</li>
                            <li>Batam, Kepulauan Riau</li>
                            <li>(0778) 1234 5678</li>
                        </ul>
                    </div>

                    <div class="col-lg-2">
                        <h6 class="fw-bold text-white mb-3">Ikuti Kami</h6>
                        <div class="d-flex gap-2">
                            <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>
                </div>

                <div class="border-top border-light border-opacity-10 mt-4 pt-3 text-center small text-white-50">
                    © 2026 SBUM · PBL TRPL 209. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
</div>
@endsection
