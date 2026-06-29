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
    .brand-inline{display:flex;align-items:center;gap:.75rem;}
    .sidebar-link{color:#55615b;border-radius:1rem;padding:.95rem 1rem;text-decoration:none;display:flex;align-items:center;gap:.75rem;transition:.2s ease;}
    .sidebar-link:hover{background:#f3f6f3;color:var(--primary-dark);}
    .sidebar-link.active{background:#edf3ee;color:var(--primary-dark);font-weight:600;}
    .sidebar-dot{width:1.35rem;height:1.35rem;border-radius:50%;background:#dfe7df;flex-shrink:0;}
    .page-caption{color:var(--text-muted);font-size:1.25rem;margin-bottom:1.2rem;}
    .page-heading{font-size:1.5rem;font-weight:500;margin-bottom:0;}
    .search-input{height:3rem;border-radius:1rem;border:1px solid #ddd2c5;background:#fffdfa;}
    .search-dot{width:2.25rem;height:2.25rem;background:#cfdacd;border-radius:50%;flex-shrink:0;}
    .intro-card{background:#edf2ea;border:1px solid #dfe7dc;border-radius:1.75rem;}
    .form-card,.summary-card,.info-card{border:1px solid #e0d7cb;border-radius:1.5rem;background:#fffdfa;}
    .soft-input{
        height:50px;border-radius:1rem;border:1px solid #dfd4c8;background:#fffdfa;
    }
    textarea.soft-input{height:auto;min-height:78px;padding-top:1rem;}
    .btn-main{
        background:var(--primary-main);border:0;border-radius:1rem;height:52px;font-weight:600;color:white;
    }
    .btn-main:hover{background:var(--primary-dark);color:white;}
    .btn-soft{
        background:#fffdfa;border:1px solid #dfd4c8;border-radius:1rem;height:52px;font-weight:500;color:#5f6963;
    }
    .info-card{
        background:#f3e7c9;border-color:#e3c98b;
    }
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
                    <a href="{{ route('mahasiswa.pengajuan') }}" class="sidebar-link active">
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
                        <h1 class="page-heading">Mahasiswa · Ajukan Peminjaman</h1>
                    </div>

                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <input type="text" class="form-control search-input" placeholder="Cari data">
                        <div class="search-dot"></div>
                    </div>
                </div>
                <div class="card intro-card shadow-none mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fs-5 fw-semibold mb-3">Ajukan peminjaman fasilitas</h2>
                        <p class="mb-0 text-secondary">
                            Mahasiswa memilih fasilitas, mengisi data peminjaman, lalu submit agar diproses pihak berwenang.
                        </p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-4 border-0 mb-4 p-3" style="background-color: #fcebeb; color: #8a3c3c; border: 1px solid #f7d1d1;">
                        <ul class="mb-0 px-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger rounded-4 border-0 mb-4 p-3" style="background-color: #fcebeb; color: #8a3c3c; border: 1px solid #f7d1d1;">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success rounded-4 border-0 mb-4 p-3" style="background-color: #edf7ed; color: #2e7d32; border: 1px solid #c8e6c9;">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('mahasiswa.pengajuan.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-xl-8">
                            <div class="mb-3 fw-semibold text-secondary">Form Peminjaman</div>

                            <div class="d-grid gap-3">
                                <div>
                                    <label class="form-label text-secondary fw-semibold">Fasilitas</label>
                                    <select id="facilitySelect" name="facility_id" class="form-select soft-input" required>
                                        <option value="">-- Pilih Ruangan atau Barang --</option>
                                        <optgroup label="Ruangan">
                                            @foreach($rooms as $room)
                                                <option value="Ruangan-{{ $room->id_ruangan }}" {{ (old('facility_id') ?? $selectedFacilityId) == "Ruangan-{$room->id_ruangan}" ? 'selected' : '' }}>
                                                    {{ $room->nama_ruangan }} ({{ $room->kode_ruangan }} - {{ $room->nama_gedung }})
                                                </option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Barang Inventaris">
                                            @foreach($items as $item)
                                                <option value="Inventaris-{{ $item->id_barang }}" {{ (old('facility_id') ?? $selectedFacilityId) == "Inventaris-{$item->id_barang}" ? 'selected' : '' }}>
                                                    {{ $item->nama_barang }} ({{ $item->kode_barang }} - Stok: {{ $item->stok_tersedia }})
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label text-secondary fw-semibold">Nama Kegiatan</label>
                                    <input type="text" name="nama_kegiatan" class="form-control soft-input" value="{{ old('nama_kegiatan') ?? 'Seminar Mahasiswa Baru' }}" required>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary fw-semibold">Tanggal</label>
                                        <input type="date" name="tanggal" class="form-control soft-input" min="{{ date('Y-m-d') }}" value="{{ old('tanggal') ?? date('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label text-secondary fw-semibold">Jam Mulai</label>
                                                <select name="jam_mulai" id="jamMulai" class="form-select soft-input" required style="cursor: pointer;">
                                                    @for($h = 8; $h <= 17; $h++)
                                                        @php $time = sprintf('%02d:00', $h); @endphp
                                                        <option value="{{ $time }}" {{ old('jam_mulai', '08:00') == $time ? 'selected' : '' }}>{{ $time }}</option>
                                                     @endfor
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label text-secondary fw-semibold">Jam Selesai</label>
                                                <select name="jam_selesai" id="jamSelesai" class="form-select soft-input" required style="cursor: pointer;">
                                                    @for($h = 9; $h <= 18; $h++)
                                                        @php $time = sprintf('%02d:00', $h); @endphp
                                                        <option value="{{ $time }}" {{ old('jam_selesai', '12:00') == $time ? 'selected' : '' }}>{{ $time }}</option>
                                                     @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary fw-semibold">Jumlah Peserta</label>
                                        <input type="number" name="jumlah_peserta" class="form-control soft-input" min="1" value="{{ old('jumlah_peserta') ?? '180' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary fw-semibold">Penanggung Jawab</label>
                                        <select name="dosen_id" class="form-select soft-input" required>
                                            <option value="">-- Pilih Dosen / PIC Penanggung Jawab --</option>
                                            @foreach($staff as $member)
                                                <option value="{{ $member->id_user }}" {{ old('dosen_id') == $member->id_user ? 'selected' : '' }}>
                                                    {{ $member->nama_lengkap }} ({{ $member->roles->pluck('nama_role')->first() ?? 'Staf' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label text-secondary fw-semibold">Catatan</label>
                                    <textarea name="keterangan" class="form-control soft-input">{{ old('keterangan') ?? 'Tambahkan kebutuhan tambahan atau informasi kegiatan.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="mb-3 fw-semibold text-secondary">Ringkasan Pengajuan</div>

                            <div class="summary-card p-4 mb-4">
                                <div class="fw-semibold mb-2" id="summaryFacility">-- Pilih Fasilitas --</div>
                                <div class="text-secondary mb-2" id="summaryTime">-- Tanggal & Waktu --</div>
                                <div class="text-secondary">Perlu verifikasi dosen dan admin</div>
                            </div>

                            <div class="d-grid gap-3 mb-4">
                                <button type="submit" class="btn btn-main" style="cursor: pointer;">Submit Pengajuan</button>
                                <button type="button" class="btn btn-soft" onclick="alert('Draft berhasil disimpan (Mocked).')">Simpan Draft</button>
                            </div>

                            <div class="info-card p-4">
                                <div class="fw-semibold mb-2">Info Validasi</div>
                                <div class="text-secondary">Jika data tidak lengkap, sistem menolak submit.</div>
                            </div>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
    /* Custom premium styling for Tom Select */
    .ts-wrapper.soft-input-ts {
        border: none;
        background: transparent;
    }
    .ts-control {
        height: 50px !important;
        border-radius: 1rem !important;
        border: 1px solid #dfd4c8 !important;
        background: #fffdfa !important;
        padding: 0.65rem 1rem !important;
        font-size: 1rem;
        color: var(--text-main);
        box-shadow: none !important;
        display: flex;
        align-items: center;
    }
    .ts-dropdown {
        border-radius: 1rem !important;
        border: 1px solid #dfd4c8 !important;
        background: #fffdfa !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important;
        padding: 0.5rem;
        z-index: 1050;
    }
    .ts-dropdown .optgroup-header {
        font-weight: 700;
        color: var(--text-muted);
        padding: 0.5rem 0.75rem;
    }
    .ts-dropdown .option {
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .ts-dropdown .option:hover, .ts-dropdown .active {
        background-color: #edf3ee !important;
        color: var(--primary-dark) !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const facilitySelect = document.getElementById('facilitySelect');
    const tanggalInput = document.getElementsByName('tanggal')[0];
    const jamMulaiSelect = document.getElementById('jamMulai');
    const jamSelesaiSelect = document.getElementById('jamSelesai');
    
    const summaryFacility = document.getElementById('summaryFacility');
    const summaryTime = document.getElementById('summaryTime');

    // Initialize Tom Select
    let tomSelectInst = null;
    if (facilitySelect) {
        facilitySelect.className = "form-select soft-input-ts";
        tomSelectInst = new TomSelect(facilitySelect, {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    }
    
    function validateHours() {
        if (jamMulaiSelect && jamSelesaiSelect) {
            const start = jamMulaiSelect.value;
            const end = jamSelesaiSelect.value;
            if (start >= end) {
                const startHour = parseInt(start.split(':')[0]);
                const nextHour = startHour + 1;
                const paddedHour = nextHour < 10 ? '0' + nextHour + ':00' : nextHour + ':00';
                jamSelesaiSelect.value = paddedHour;
            }
        }
    }
    
    function updateSummary() {
        if (facilitySelect && summaryFacility) {
            const selectedOpt = facilitySelect.options[facilitySelect.selectedIndex];
            if (selectedOpt && selectedOpt.value) {
                summaryFacility.textContent = selectedOpt.text.split('(')[0].trim();
            } else {
                summaryFacility.textContent = '-- Pilih Fasilitas --';
            }
        }
        
        if (tanggalInput && summaryTime) {
            const dateVal = tanggalInput.value;
            const startVal = jamMulaiSelect ? jamMulaiSelect.value : '';
            const endVal = jamSelesaiSelect ? jamSelesaiSelect.value : '';
            if (dateVal) {
                summaryTime.textContent = `${dateVal} · ${startVal.replace(':00', '.00')} - ${endVal.replace(':00', '.00')}`;
            } else {
                summaryTime.textContent = '-- Tanggal & Waktu --';
            }
        }
    }
    
    if (tomSelectInst) tomSelectInst.on('change', updateSummary);
    if (tanggalInput) tanggalInput.addEventListener('change', updateSummary);
    
    if (jamMulaiSelect) {
        jamMulaiSelect.addEventListener('change', function() {
            validateHours();
            updateSummary();
        });
    }
    if (jamSelesaiSelect) {
        jamSelesaiSelect.addEventListener('change', function() {
            if (jamMulaiSelect && jamMulaiSelect.value >= jamSelesaiSelect.value) {
                alert('Jam selesai harus lebih besar dari jam mulai.');
                validateHours();
            }
            updateSummary();
        });
    }
    
    updateSummary();
});
</script>
@endsection
