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

    .btn-detail{
        background: #eef4ee;
        color: var(--primary-dark);
        border: 1px solid #c8d8c8;
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 999px;
        padding: .55rem 1.15rem;
        transition: all 0.2s ease;
    }

    .btn-detail:hover{
        background: var(--primary-main);
        color: #fff;
        border-color: var(--primary-main);
    }

    .btn-ajukan {
        background: var(--primary-main);
        color: #fff;
        border: 1px solid var(--primary-main);
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 999px;
        padding: .55rem 1.15rem;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s ease;
    }

    .btn-ajukan:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
        color: #fff;
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

                    <a href="{{ route('mahasiswa.jadwal') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span>
                        <span>Jadwal</span>
                    </a>

                    <a href="{{ route('mahasiswa.pengajuan') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span>
                        <span>Pengajuan Saya</span>
                    </a>

                    <a href="{{ route('mahasiswa.pengembalian') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span>
                        <span>Pengembalian</span>
                    </a>

                    <a href="{{ route('mahasiswa.notifikasi') }}" class="sidebar-link">
                        <span class="sidebar-dot"></span>
                        <span>Notifikasi</span>
                    </a>

                    <a href="{{ route ('mahasiswa.profil')}}" class="sidebar-link">
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
                <form action="{{ route('mahasiswa.fasilitas') }}" method="GET">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">
                        <div>
                            <div class="page-caption">Mahasiswa</div>
                            <h1 class="page-heading">Mahasiswa · Daftar Fasilitas</h1>
                        </div>

                        <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                            <input type="text" name="search" class="form-control search-input" placeholder="Cari data" value="{{ request('search') }}">
                            <button type="submit" class="border-0 search-dot" style="display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background-color 0.2s;" title="Cari">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#33403b" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                            </button>
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
                                <select name="category" class="form-select filter-select">
                                    <option value="Semua" {{ request('category') == 'Semua' ? 'selected' : '' }}>Kategori: Semua</option>
                                    <option value="Ruangan" {{ request('category') == 'Ruangan' ? 'selected' : '' }}>Ruangan</option>
                                    <option value="Inventaris" {{ request('category') == 'Inventaris' ? 'selected' : '' }}>Inventaris</option>
                                </select>

                                <select name="location" class="form-select filter-select">
                                    <option value="Semua Gedung" {{ request('location') == 'Semua Gedung' ? 'selected' : '' }}>Lokasi: Semua Gedung</option>
                                    @foreach($buildings as $building)
                                        <option value="{{ $building }}" {{ request('location') == $building ? 'selected' : '' }}>{{ $building }}</option>
                                    @endforeach
                                </select>

                                <select name="status" class="form-select filter-select">
                                    <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Status: Semua</option>
                                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="tidak tersedia" {{ request('status') == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>

                                <button type="submit" class="btn btn-apply text-white">Terapkan</button>
                            </div>
                        </div>

                        <div class="col-xl-9">
                            <div class="section-label">Daftar Fasilitas</div>

                            <div class="row g-4">
                                @forelse($facilities as $item)
                                    <div class="col-md-6">
                                        <div class="card facility-card shadow-none h-100">
                                            <div class="card-body p-3 p-lg-4 d-flex flex-column justify-content-between" style="min-height: 320px;">
                                                <div>
                                                    @if($item->foto && file_exists(public_path($item->foto)))
                                                        <img src="{{ asset($item->foto) }}" alt="{{ $item->nama }}" class="facility-thumb w-100 object-fit-cover mb-4">
                                                    @else
                                                        <div class="facility-thumb {{ $item->kategori === 'Ruangan' ? 'thumb-1' : 'thumb-2' }} mb-4 d-flex align-items-center justify-content-center text-muted fw-semibold">
                                                            <span>{{ $item->kategori }}</span>
                                                        </div>
                                                    @endif
                                                    <h3 class="facility-title mb-3 fw-bold">{{ $item->nama }}</h3>
                                                </div>
                                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                                                    <div class="facility-meta">
                                                        <span class="fw-semibold text-dark">{{ $item->kategori }}</span> · {{ $item->detail_meta }}
                                                        @if($item->kategori === 'Ruangan' && $item->lokasi)
                                                            <br><small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $item->lokasi }}</small>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <a href="{{ route('mahasiswa.pengajuan', ['facility_id' => $item->kategori . '-' . $item->id]) }}" class="btn-ajukan text-white">Ajukan Peminjaman</a>
                                                        <button type="button" class="btn btn-sm btn-detail" data-id="{{ $item->id }}" data-category="{{ $item->kategori }}">Detail</button>
                                                        @if(strtolower($item->status) === 'tersedia')
                                                            <span class="badge-soft-success">Tersedia</span>
                                                        @elseif(strtolower($item->status) === 'terbatas')
                                                            <span class="badge-soft-warning">Terbatas</span>
                                                        @elseif(strtolower($item->status) === 'maintenance')
                                                            <span class="badge-soft-warning" style="background: #fdf2e2; color: #b7791f; border-color: #fbd38d;">Maintenance</span>
                                                        @else
                                                            <span class="badge-soft-warning" style="background: #fde8e8; color: #c53030; border-color: #feb2b2;">Tidak Tersedia</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <div class="text-muted fs-5">Fasilitas atau barang tidak ditemukan.</div>
                                    </div>
                                @endforelse
                            </div>

                            @if($facilities->hasPages())
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $facilities->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>
</div>

<!-- Modal Detail Fasilitas -->
<div class="modal fade" id="facilityDetailModal" tabindex="-1" aria-labelledby="facilityDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 1.5rem; border: 1px solid #e0d7cb; background: #fcfbf8;">
            <div class="modal-header border-bottom-0 pb-0" style="padding: 1.5rem 1.5rem 0 1.5rem;">
                <h5 class="modal-title fw-bold text-dark fs-4" id="facilityDetailModalLabel">Detail Fasilitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;">
                <div id="modalLoading" class="text-center py-5">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data...</p>
                </div>
                <div id="modalContent" class="d-none">
                    <div class="row g-4">
                        <div class="col-md-5 text-center text-md-start">
                            <img id="detailFoto" src="" alt="" class="img-fluid rounded-4 object-fit-cover w-100 mb-3" style="max-height: 250px; display: none;">
                            <div id="detailFotoFallback" class="rounded-4 w-100 mb-3 d-flex align-items-center justify-content-center text-muted fw-semibold" style="height: 200px; background:#dfe8df;">
                                <span id="detailKategoriFallback"></span>
                            </div>
                            <div class="p-3 rounded-4 text-start" style="background: #edf2ea; border: 1px solid #dfe7dc;">
                                <h6 class="fw-bold mb-2 text-dark" style="font-size: 0.95rem;">Informasi PIC</h6>
                                <p class="mb-1 text-secondary" style="font-size: 0.9rem;">
                                    <i class="bi bi-person me-2"></i>Nama: <strong class="text-dark" id="detailPic"></strong>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge" id="detailKategoriBadge" style="background: #dcebd7; color: #557b58; font-weight: 600;"></span>
                                <span class="badge text-white" id="detailStatusBadge"></span>
                            </div>
                            <h4 class="fw-bold text-dark mb-3" id="detailNama"></h4>
                            
                            <table class="table table-borderless align-middle mb-4" style="font-size: 0.95rem;">
                                <tbody>
                                    <tr id="rowKode">
                                        <td class="text-muted py-1" style="width: 35%;">Kode</td>
                                        <td class="py-1"><strong id="detailKode" class="text-dark"></strong></td>
                                    </tr>
                                    <tr id="rowGedung">
                                        <td class="text-muted py-1">Gedung</td>
                                        <td class="py-1"><strong id="detailGedung" class="text-dark"></strong></td>
                                    </tr>
                                    <tr id="rowLantai">
                                        <td class="text-muted py-1">Lantai</td>
                                        <td class="py-1"><strong id="detailLantai" class="text-dark"></strong></td>
                                    </tr>
                                    <tr id="rowKapasitas">
                                        <td class="text-muted py-1">Kapasitas</td>
                                        <td class="py-1"><strong id="detailKapasitas" class="text-dark"></strong></td>
                                    </tr>
                                    <tr id="rowStok">
                                        <td class="text-muted py-1">Stok Tersedia</td>
                                        <td class="py-1"><strong id="detailStok" class="text-dark"></strong></td>
                                    </tr>
                                    <tr id="rowStokTotal">
                                        <td class="text-muted py-1">Stok Total</td>
                                        <td class="py-1"><strong id="detailStokTotal" class="text-dark"></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <div class="mb-4">
                                <h6 class="fw-bold text-dark mb-2" style="font-size: 0.95rem;">Deskripsi / Keterangan</h6>
                                <div class="p-3 rounded-3 bg-white" style="border: 1px solid #e7ddd1; font-size: 0.9rem; color: #55615b;" id="detailDeskripsi"></div>
                            </div>
                            
                            <div id="sectionFasilitasPendukung" class="mb-2">
                                <h6 class="fw-bold text-dark mb-2" style="font-size: 0.95rem;">Fasilitas Pendukung</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped table-hover border" style="font-size: 0.875rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="px-3">Nama Fasilitas</th>
                                                <th class="text-center" style="width: 20%;">Jumlah</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="detailFasilitasList">
                                            <!-- Facilities list will be appended here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const detailButtons = document.querySelectorAll('.btn-detail');
    const modalElement = document.getElementById('facilityDetailModal');
    const modal = new bootstrap.Modal(modalElement);
    const modalLoading = document.getElementById('modalLoading');
    const modalContent = document.getElementById('modalContent');

    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const category = this.getAttribute('data-category');
            
            // Show Modal and Loading
            modalLoading.classList.remove('d-none');
            modalContent.classList.add('d-none');
            modal.show();

            // Fetch details
            fetch(`/mahasiswa/fasilitas/detail?id=${id}&kategori=${category}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data detail');
                    }
                    return response.json();
                })
                .then(res => {
                    if (res.success) {
                        const item = res.data;
                        
                        // Set basic fields
                        document.getElementById('detailNama').textContent = item.nama;
                        document.getElementById('detailKode').textContent = item.kode;
                        document.getElementById('detailPic').textContent = item.pic;
                        document.getElementById('detailKategoriBadge').textContent = res.kategori;
                        
                        // Handle Photo
                        const detailFoto = document.getElementById('detailFoto');
                        const detailFotoFallback = document.getElementById('detailFotoFallback');
                        const detailKategoriFallback = document.getElementById('detailKategoriFallback');
                        
                        if (item.foto) {
                            detailFoto.src = item.foto;
                            detailFoto.alt = item.nama;
                            detailFoto.style.display = 'block';
                            detailFotoFallback.classList.add('d-none');
                        } else {
                            detailFoto.style.display = 'none';
                            detailFotoFallback.classList.remove('d-none');
                            detailKategoriFallback.textContent = res.kategori;
                            // Set color classes
                            if (res.kategori === 'Ruangan') {
                                detailFotoFallback.style.background = '#dfe8df';
                            } else {
                                detailFotoFallback.style.background = '#e7ddd3';
                            }
                        }

                        // Set Status Badge style & text
                        const statusBadge = document.getElementById('detailStatusBadge');
                        const statusLower = item.status.toLowerCase();
                        if (statusLower === 'tersedia') {
                            statusBadge.textContent = 'Tersedia';
                            statusBadge.style.background = '#dcebd7';
                            statusBadge.style.color = '#557b58';
                            statusBadge.style.border = '1px solid #b7d2b6';
                        } else if (statusLower === 'terbatas') {
                            statusBadge.textContent = 'Terbatas';
                            statusBadge.style.background = 'var(--warning-soft)';
                            statusBadge.style.color = '#92723c';
                            statusBadge.style.border = '1px solid #e3c98b';
                        } else if (statusLower === 'maintenance') {
                            statusBadge.textContent = 'Maintenance';
                            statusBadge.style.background = '#fdf2e2';
                            statusBadge.style.color = '#b7791f';
                            statusBadge.style.border = '1px solid #fbd38d';
                        } else {
                            statusBadge.textContent = 'Tidak Tersedia';
                            statusBadge.style.background = '#fde8e8';
                            statusBadge.style.color = '#c53030';
                            statusBadge.style.border = '1px solid #feb2b2';
                        }

                        // Conditional layouts for Room vs Inventory
                        if (res.kategori === 'Ruangan') {
                            document.getElementById('rowGedung').classList.remove('d-none');
                            document.getElementById('rowLantai').classList.remove('d-none');
                            document.getElementById('rowKapasitas').classList.remove('d-none');
                            document.getElementById('rowStok').classList.add('d-none');
                            document.getElementById('rowStokTotal').classList.add('d-none');
                            document.getElementById('sectionFasilitasPendukung').classList.remove('d-none');
                            
                            document.getElementById('detailGedung').textContent = item.gedung || 'N/A';
                            document.getElementById('detailLantai').textContent = item.lantai || 'N/A';
                            document.getElementById('detailKapasitas').textContent = `${item.kapasitas} orang`;
                            document.getElementById('detailDeskripsi').textContent = item.deskripsi;
                            
                            // Load supporting facilities list
                            const fasilitasTbody = document.getElementById('detailFasilitasList');
                            fasilitasTbody.innerHTML = '';
                            if (item.fasilitas_pendukung && item.fasilitas_pendukung.length > 0) {
                                item.fasilitas_pendukung.forEach(f => {
                                    const tr = document.createElement('tr');
                                    tr.innerHTML = `
                                        <td class="px-3 text-dark fw-medium">${f.nama}</td>
                                        <td class="text-center">${f.jumlah}</td>
                                        <td class="text-secondary">${f.keterangan}</td>
                                    `;
                                    fasilitasTbody.appendChild(tr);
                                });
                            } else {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `<td colspan="3" class="text-center text-muted py-3">Tidak ada fasilitas pendukung terdaftar.</td>`;
                                fasilitasTbody.appendChild(tr);
                            }
                        } else {
                            document.getElementById('rowGedung').classList.add('d-none');
                            document.getElementById('rowLantai').classList.add('d-none');
                            document.getElementById('rowKapasitas').classList.add('d-none');
                            document.getElementById('rowStok').classList.remove('d-none');
                            document.getElementById('rowStokTotal').classList.remove('d-none');
                            document.getElementById('sectionFasilitasPendukung').classList.add('d-none');
                            
                            document.getElementById('detailStok').textContent = `${item.stok} unit`;
                            document.getElementById('detailStokTotal').textContent = `${item.stok_total} unit`;
                            document.getElementById('detailDeskripsi').textContent = item.keterangan;
                        }

                        // Hide loading, show content
                        modalLoading.classList.add('d-none');
                        modalContent.classList.remove('d-none');
                    } else {
                        alert(res.message || 'Gagal memuat data detail.');
                        modal.hide();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat menghubungi server: ' + err.message);
                    modal.hide();
                });
        });
    });
});
</script>
</div>
@endsection
