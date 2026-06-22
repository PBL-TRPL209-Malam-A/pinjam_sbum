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
        --yellow-bg:#fdf5e6;
        --yellow-text:#b8860b;
        --yellow-line:#f5deb3;
        --red-main:#b85c5c;
        --red-dark:#9c4a4a;
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

    .logout-btn{
        background:transparent;
        border:0;
        color:#5b635f;
        padding:0;
        font-size:16px;
    }

    .proposal-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
    }

    .badge-pending-final {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 130px;
        height: 32px;
        border-radius: 999px;
        background: var(--yellow-bg);
        color: var(--yellow-text);
        border: 1px solid var(--yellow-line);
        font-size: 0.85rem;
        font-weight: 500;
    }

    .btn-action-detail {
        background: #fffdfa;
        border: 1px solid #dfd4c8;
        border-radius: 0.75rem;
        height: 40px;
        min-width: 90px;
        color: #5f6963;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-action-detail:hover {
        background: #f7f2eb;
    }

    .btn-action-approve {
        background: var(--primary-main);
        border: 0;
        border-radius: 0.75rem;
        height: 40px;
        min-width: 90px;
        color: #fff;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-action-approve:hover {
        background: var(--primary-dark);
    }

    .btn-action-reject {
        background: var(--red-main);
        border: 0;
        border-radius: 0.75rem;
        height: 40px;
        min-width: 90px;
        color: #fff;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-action-reject:hover {
        background: var(--red-dark);
    }

    .decision-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .soft-textarea {
        width: 100%;
        border-radius: 1rem;
        border: 1px solid #dfd4c8;
        background: #fffdfa;
        padding: 1rem;
        font-size: 0.95rem;
        color: var(--text-main);
        outline: none;
        resize: none;
    }

    .soft-textarea:focus {
        border-color: var(--primary-main);
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
                    <a href="{{ route('kepalasbum.persetujuan') }}" class="sidebar-link active">
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
                        <h1 class="page-heading">Persetujuan Akhir Peminjaman</h1>
                    </div>

                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <input type="text" class="form-control search-input" placeholder="Cari data">
                        <div class="search-dot"></div>
                    </div>
                </div>

                <div class="card intro-card shadow-none mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fs-5 fw-semibold mb-3">Final approval untuk pengajuan yang sudah diverifikasi</h2>
                        <p class="mb-0 text-secondary">
                            Kepala SBUM memberikan persetujuan akhir setelah verifikasi dosen dan admin selesai.
                        </p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success border-0 mb-4" style="background:#edf5ee; color:#496454; border-radius:1rem;">
                        {{ session('success') }}
                    </div>
                @endif

                <h2 class="fs-5 fw-bold mb-3 text-secondary">Daftar Pengajuan Final</h2>

                @forelse($peminjaman as $p)
                <div class="proposal-card">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="fw-bold text-secondary">SBUM-2026-0{{ 180 + $p->id_peminjaman }}</span>
                                <span class="badge-pending-final">Menunggu Final</span>
                            </div>
                            <h3 class="fs-5 fw-bold mb-2">{{ $p->nama_kegiatan }}</h3>
                            <p class="mb-1 text-secondary" style="font-size: 0.95rem;">
                                Peminjam: <strong class="text-dark">{{ $p->user->nama_lengkap ?? '-' }}</strong> - 
                                <span>
                                    @if($p->ruangan->isNotEmpty())
                                        {{ $p->ruangan->first()->nama_ruangan }}
                                    @elseif($p->barang->isNotEmpty())
                                        {{ $p->barang->first()->nama_barang }}
                                    @else
                                        Fasilitas
                                    @endif
                                </span>
                            </p>
                            <p class="mb-0 text-secondary" style="font-size: 0.9rem;">
                                {{ date('d M Y', strtotime($p->tanggal_pengajuan)) }} - 08.00 - 12.00 - 
                                <span class="text-success fw-medium">Sudah diverifikasi dosen dan admin</span>
                            </p>
                        </div>

                        <div class="d-flex gap-2 align-self-md-center">
                            <button class="btn-action-detail">Detail</button>
                            <form action="{{ route('kepalasbum.verifikasi', $p->id_peminjaman) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status_pengajuan" value="disetujui_kepala">
                                <input type="hidden" name="catatan" id="catatan_setuju_{{ $p->id_peminjaman }}" value="">
                                <button type="submit" class="btn-action-approve" onclick="document.getElementById('catatan_setuju_{{ $p->id_peminjaman }}').value = document.getElementById('catatan_keputusan').value">Setujui</button>
                            </form>
                            <form action="{{ route('kepalasbum.verifikasi', $p->id_peminjaman) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status_pengajuan" value="ditolak">
                                <input type="hidden" name="catatan" id="catatan_tolak_{{ $p->id_peminjaman }}" value="">
                                <button type="submit" class="btn-action-reject" onclick="document.getElementById('catatan_tolak_{{ $p->id_peminjaman }}').value = document.getElementById('catatan_keputusan').value">Tolak</button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="card p-5 text-center text-secondary border-0" style="background:#fffdfa; border-radius:1.5rem; border: 1px solid var(--line) !important;">
                    Tidak ada pengajuan yang membutuhkan persetujuan akhir.
                </div>
                @endforelse

                <div class="decision-card">
                    <h3 class="fs-6 fw-bold mb-1 text-secondary">Catatan Keputusan</h3>
                    <p class="mb-3 text-secondary" style="font-size: 0.9rem;">
                        Tambahkan alasan persetujuan atau penolakan agar proses audit dan histori keputusan jelas.
                    </p>
                    <textarea id="catatan_keputusan" rows="3" class="soft-textarea" placeholder="Contoh: Disetujui karena sesuai agenda kampus dan kapasitas ruangan mencukupi."></textarea>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection
