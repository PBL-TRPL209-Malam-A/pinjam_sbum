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
    .intro-card{background:#edf2ea;border:1px solid #dfe7dc;border-radius:1.75rem;}
    .summary-card{border:1px solid #e0d7cb;border-radius:1.5rem;background:#fffdfa;}
    .soft-input{
        height:50px;border-radius:1rem;border:1px solid #dfd4c8;background:#fffdfa;
    }
    textarea.soft-input{height:auto;min-height:78px;padding-top:1rem;}
    .btn-main{
        background:var(--primary-main);border:0;border-radius:1rem;height:52px;font-weight:600;color:white;
    }
    .btn-main:hover{background:var(--primary-dark);color:white;}
    .status-choice{
        height:48px;border-radius:1rem;border:1px solid transparent;font-weight:500;
    }
    .status-good{background:#dcebd7;border-color:#b7d2b6;color:#557b58;}
    .status-note{background:#f4e7c9;border-color:#e3c98b;color:#92723c;}
    .logout-btn{background:transparent;border:0;color:#5b635f;padding:0;font-size:16px;}

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
                    <a href="{{ route('mahasiswa.pengembalian') }}" class="sidebar-link active">
                        <span class="sidebar-dot"></span><span>Pengembalian</span>
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
                        <h1 class="page-heading">Mahasiswa · Ajukan Pengembalian</h1>
                    </div>

                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <input type="text" class="form-control search-input" placeholder="Cari data">
                        <div class="search-dot"></div>
                    </div>
                </div>

                <div class="card intro-card shadow-none mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fs-5 fw-semibold mb-3">Ajukan pengembalian fasilitas</h2>
                        <p class="mb-0 text-secondary">
                            Mahasiswa membuka menu pengembalian, mengisi data, lalu submit agar admin dapat memverifikasi.
                        </p>
                    </div>
                </div>
                <form action="{{ route('mahasiswa.pengembalian.store') }}" method="POST" enctype="multipart/form-data" class="row g-4">
                    @csrf
                    <div class="col-xl-8">
                        <div class="mb-3 fw-semibold text-secondary">Form Pengembalian</div>
 
                        <div class="d-grid gap-3">
                            <div>
                                <label class="form-label text-secondary fw-semibold">ID Peminjaman</label>
                                <select name="peminjaman_id" id="peminjamanSelect" class="form-select soft-input" style="padding-left: 1rem; border-radius: 1rem; border-color: #dfd4c8; background-color: #fffdfa;" required>
                                    <option value="">Pilih PJM Aktif</option>
                                    @foreach($peminjaman as $p)
                                        @php
                                            $facilityName = $p->ruangan->isNotEmpty() ? $p->ruangan->first()->nama_ruangan : ($p->barang->isNotEmpty() ? $p->barang->first()->nama_barang : 'Fasilitas');
                                        @endphp
                                        <option value="{{ $p->id_peminjaman }}" 
                                            data-fasilitas="{{ $facilityName }}"
                                            data-kegiatan="{{ $p->nama_kegiatan }}"
                                            data-waktu="{{ $p->tanggal_pengajuan ? \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }} · {{ $p->jam_mulai ? substr($p->jam_mulai, 0, 5) : '08:00' }} - {{ $p->jam_selesai ? substr($p->jam_selesai, 0, 5) : '12:00' }}">
                                            [SBUM-2026-{{ str_pad($p->id_peminjaman, 4, '0', STR_PAD_LEFT) }}] - {{ $p->nama_kegiatan }} ({{ $facilityName }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
 
                            <div>
                                <label class="form-label text-secondary fw-semibold">Fasilitas</label>
                                <input type="text" id="facilityInput" class="form-control soft-input" style="background-color: #f7f3eb;" readonly value="-">
                            </div>
 
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary fw-semibold">Tanggal Selesai Aktual</label>
                                    <input type="date" name="tanggal_selesai_aktual" class="form-control soft-input" value="{{ now()->format('Y-m-d') }}" required style="border-radius: 1rem; border-color: #dfd4c8;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary fw-semibold">Jam Selesai Aktual</label>
                                    <input type="time" name="jam_selesai_aktual" class="form-control soft-input" value="{{ now()->format('H:i') }}" required style="border-radius: 1rem; border-color: #dfd4c8;">
                                </div>
                            </div>
 
                            <div>
                                <label class="form-label text-secondary fw-semibold">Kondisi Fasilitas</label>
                                <input type="hidden" name="kondisi" id="kondisiInput" value="baik">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="status-choice status-good d-flex align-items-center justify-content-center" style="border: 2px solid #557b58; cursor: pointer;">Baik</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="status-choice status-note d-flex align-items-center justify-content-center" style="border: 1px solid transparent; cursor: pointer;">Ada Catatan</div>
                                    </div>
                                </div>
                            </div>
 
                            <div>
                                <label class="form-label text-secondary fw-semibold">Catatan Pengembalian</label>
                                <textarea name="catatan" class="form-control soft-input" placeholder="Masukkan catatan pengembalian (opsional)"></textarea>
                            </div>
 
                            <div>
                                <label class="form-label text-secondary fw-semibold">Upload Foto Kondisi Fasilitas (Format: .jpg, .jpeg, .png, maks 5MB)</label>
                                <input type="file" name="foto_kondisi" class="form-control" accept="image/png, image/jpeg, image/jpg" required style="border-radius: 1rem; border-color: #dfd4c8; padding: 0.75rem 1rem; height: auto;">
                            </div>
 
                            <div>
                                <label class="form-label text-secondary fw-semibold">Upload Dokumen Administrasi Pasca-Pakai (Format: .pdf, maks 10MB)</label>
                                <input type="file" name="dokumen_administrasi" class="form-control" accept="application/pdf" required style="border-radius: 1rem; border-color: #dfd4c8; padding: 0.75rem 1rem; height: auto;">
                            </div>
                        </div>
                    </div>
 
                    <div class="col-xl-4">
                        <div class="mb-3 fw-semibold text-secondary">Ringkasan Pengembalian</div>
 
                        <div class="summary-card p-4 mb-4">
                            <div class="text-secondary mb-2">Peminjaman Aktif</div>
                            <div class="fw-semibold mb-2" id="summaryKegiatan">-</div>
                            <div class="text-secondary" id="summaryWaktu">-</div>
                        </div>
 
                        <div class="d-grid">
                            <button type="submit" class="btn btn-main">Submit Pengembalian</button>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const peminjamanSelect = document.getElementById('peminjamanSelect');
        if (peminjamanSelect) {
            peminjamanSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    const facility = selectedOption.getAttribute('data-fasilitas');
                    const kegiatan = selectedOption.getAttribute('data-kegiatan');
                    const waktu = selectedOption.getAttribute('data-waktu');
                    
                    document.getElementById('facilityInput').value = facility;
                    document.getElementById('summaryKegiatan').innerText = kegiatan;
                    document.getElementById('summaryWaktu').innerText = facility + ' · ' + waktu;
                } else {
                    document.getElementById('facilityInput').value = '-';
                    document.getElementById('summaryKegiatan').innerText = '-';
                    document.getElementById('summaryWaktu').innerText = '-';
                }
            });
        }

        const choiceGood = document.querySelector('.status-good');
        const choiceNote = document.querySelector('.status-note');
        const kondisiInput = document.getElementById('kondisiInput');

        if (choiceGood && choiceNote && kondisiInput) {
            choiceGood.addEventListener('click', function() {
                kondisiInput.value = 'baik';
                choiceGood.style.border = '2px solid #557b58';
                choiceNote.style.border = '1px solid transparent';
            });

            choiceNote.addEventListener('click', function() {
                kondisiInput.value = 'ada_catatan';
                choiceNote.style.border = '2px solid #92723c';
                choiceGood.style.border = '1px solid transparent';
            });
        }
    });
</script>
@endsection
