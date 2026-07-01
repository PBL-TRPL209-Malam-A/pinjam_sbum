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
    }

    body{background:var(--page-bg);color:var(--text-main);}
    .app-shell{background:var(--panel-bg);border:1px solid var(--line);border-radius:2rem;overflow:hidden;min-height:calc(100vh - 3rem);}
    .sidebar-panel{min-height:100%;border-right:1px solid var(--line);background:rgba(255,255,255,.25);}
    .logo-box img{width:110px;height:auto;object-fit:contain;}
    .sidebar-link{color:#55615b;border-radius:1rem;padding:.95rem 1rem;text-decoration:none;display:flex;align-items:center;gap:.75rem;transition:.2s ease;}
    .sidebar-link:hover{background:#f3f6f3;color:var(--primary-dark);}
    .sidebar-link.active{background:#edf3ee;color:var(--primary-dark);font-weight:600;}
    .sidebar-dot{width:1.35rem;height:1.35rem;border-radius:50%;background:#dfe7df;flex-shrink:0;}
    .page-caption{color:var(--text-muted);font-size:1.25rem;margin-bottom:1.2rem;}
    .page-heading{font-size:1.5rem;font-weight:500;margin-bottom:0;}
    .search-input{height:3rem;border-radius:1rem;border:1px solid #ddd2c5;background:#fffdfa;}
    .search-dot{width:2.25rem;height:2.25rem;background:#cfdacd;border-radius:50%;flex-shrink:0;}
    
    .btn-main{background:var(--primary-main);border:0;border-radius:0.75rem;padding: 0.5rem 1rem;font-weight:600;color:white;text-decoration:none;}
    .btn-main:hover{background:var(--primary-dark);color:white;}
    .logout-btn{background:transparent;border:0;color:#5b635f;padding:0;font-size:16px;}
    
    .history-card { border:1px solid var(--line); border-radius: 1.5rem; background: #fff; padding: 1.5rem; margin-bottom: 1rem; }
    .status-badge { padding: 0.35rem 0.75rem; border-radius: 2rem; font-size: 0.85rem; font-weight: 600; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-approved { background: #d4edda; color: #155724; }
    .status-rejected { background: #f8d7da; color: #721c24; }
    .status-done { background: #e2e3e5; color: #383d41; }

    @media (max-width: 991.98px){
        .sidebar-panel{border-right:0;border-bottom:1px solid var(--line);}
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
                    <a href="{{ route('mahasiswa.jadwal') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Jadwal</span>
                    </a>
                    <a href="{{ route('mahasiswa.pengajuan') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Pengajuan Saya</span>
                    </a>
                    <a href="{{ route('mahasiswa.pengembalian') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Pengembalian</span>
                    </a>
                    <a href="{{ route('mahasiswa.riwayat') }}" class="sidebar-link active">
                        <span class="sidebar-dot"></span><span>Riwayat Peminjaman</span>
                    </a>
                    <a href="{{ route('mahasiswa.notifikasi') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span><span>Notifikasi</span>
                    </a>
                    <a href="{{ route ('mahasiswa.profil')}}" class="sidebar-link">
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
                        <h1 class="page-heading">Riwayat Peminjaman & Bukti</h1>
                    </div>

                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <input type="text" class="form-control search-input" placeholder="Cari data">
                        <div class="search-dot"></div>
                    </div>
                </div>

                @if($peminjaman->isEmpty())
                    <div class="text-center py-5 text-secondary">
                        Belum ada riwayat peminjaman.
                    </div>
                @else
                    <div class="row">
                        @foreach($peminjaman as $pjm)
                            @php
                                $facilityName = $pjm->ruangan->isNotEmpty() ? $pjm->ruangan->first()->nama_ruangan : ($pjm->barang->isNotEmpty() ? $pjm->barang->first()->nama_barang : 'Fasilitas');
                                
                                $statusClass = 'status-pending';
                                $statusLabel = str_replace('_', ' ', Str::title($pjm->status));
                                
                                if(in_array($pjm->status, ['ditolak'])) {
                                    $statusClass = 'status-rejected';
                                } elseif(in_array($pjm->status, ['selesai', 'dibatalkan'])) {
                                    $statusClass = 'status-done';
                                } elseif(in_array($pjm->status, ['siap_digunakan', 'disetujui'])) {
                                    $statusClass = 'status-approved';
                                }
                            @endphp
                            <div class="col-md-6 mb-3">
                                <div class="history-card">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="fw-bold fs-5 text-dark">{{ $facilityName }}</div>
                                        <div class="status-badge {{ $statusClass }}">{{ $statusLabel }}</div>
                                    </div>
                                    <div class="mb-2"><strong>ID:</strong> SBUM-2026-{{ str_pad($pjm->id_peminjaman, 4, '0', STR_PAD_LEFT) }}</div>
                                    <div class="mb-2"><strong>Kegiatan:</strong> {{ $pjm->nama_kegiatan }}</div>
                                    <div class="mb-2 text-muted">
                                        <i class="bi bi-calendar"></i> {{ $pjm->tanggal_pengajuan ? \Carbon\Carbon::parse($pjm->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }}<br>
                                        <i class="bi bi-clock"></i> {{ $pjm->jam_mulai ? substr($pjm->jam_mulai, 0, 5) : '-' }} - {{ $pjm->jam_selesai ? substr($pjm->jam_selesai, 0, 5) : '-' }}
                                    </div>
                                    
                                    <div class="mt-4 pt-3 border-top text-end">
                                        @if(!in_array($pjm->status, ['ditolak', 'menunggu_dosen']))
                                            <a href="{{ route('mahasiswa.riwayat.pdf', $pjm->id_peminjaman) }}" target="_blank" class="btn-main">
                                                <i class="bi bi-file-earmark-pdf"></i> Cetak Bukti (PDF)
                                            </a>
                                        @else
                                            <span class="text-muted small"><i class="bi bi-info-circle"></i> Bukti cetak belum tersedia untuk status ini</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>
@endsection
