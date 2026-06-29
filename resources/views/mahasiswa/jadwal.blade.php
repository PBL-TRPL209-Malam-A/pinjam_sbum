@extends('layout.app')

@section('content')
<!-- Tailwind CSS v3 CDN with Preflight disabled to prevent style overrides -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    corePlugins: {
      preflight: false,
    }
  }
</script>
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
                    /* Ensure headers and slots align perfectly */
                    .calendar-grid, .slot-row {
                        display: grid;
                        gap: 1rem;
                    }
                    .slot-box {
                        transition: all 0.3s ease;
                    }
                </style>

                <div class="filter-card p-4 mb-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Fasilitas</label>
                            <select id="facilitySelect" class="form-select soft-input-ts" required>
                                <option value="">-- Pilih Ruangan atau Barang --</option>
                                <optgroup label="Ruangan">
                                    @foreach($rooms as $room)
                                        <option value="Ruangan-{{ $room->id_ruangan }}">
                                            {{ $room->nama_ruangan }} ({{ $room->kode_ruangan }} - {{ $room->nama_gedung }})
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Barang Inventaris">
                                    @foreach($items as $item)
                                        <option value="Inventaris-{{ $item->id_barang }}">
                                            {{ $item->nama_barang }} ({{ $item->kode_barang }} - Stok: {{ $item->stok_tersedia }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary">Tanggal</label>
                            <input type="date" id="dateInput" class="form-control soft-input" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <div class="section-label">Kalender Ketersediaan</div>

                <div class="timeline-card p-4 mb-5" style="background: #fffdfa; border: 1px solid var(--line) !important;">
                    <div id="calendarSlotsContainer" class="flex flex-col gap-4">
                        <!-- Loader -->
                        <div id="calendarLoader" class="text-center py-5 d-none">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted text-sm">Memuat data jadwal...</p>
                        </div>

                        <!-- Slots Grid Container -->
                        <div id="slotsGrid" class="d-none">
                            <!-- Rendered Slots will go here -->
                        </div>

                        <div id="noDataPlaceholder" class="text-center py-4 text-secondary d-none">
                            <i class="bi bi-info-circle fs-4 d-block mb-2"></i>
                            Pilih fasilitas dan tanggal terlebih dahulu untuk melihat ketersediaan.
                        </div>
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

<!-- Event Detail Modal (Tailwind CSS) -->
<div id="eventDetailModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden border border-solid border-[#e7ddd1] transform transition-all scale-95 duration-300 ease-out" id="eventDetailModalContent">
        <!-- Modal Header -->
        <div class="bg-[#edf2ea] px-6 py-4 flex items-center justify-between border-b border-solid border-[#dfe7dc]">
            <h3 class="text-lg font-bold text-[#33403b] flex items-center gap-2 m-0">
                <i class="bi bi-bookmark-fill text-[#5d7d6b]"></i> Detail Acara
            </h3>
            <button type="button" onclick="closeEventDetailModal()" class="text-gray-500 hover:text-gray-800 bg-transparent border-0 text-xl font-bold cursor-pointer">&times;</button>
        </div>
        <!-- Modal Body -->
        <div class="p-6 space-y-4 text-left">
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Judul Acara</span>
                <p id="modalEventTitle" class="text-base font-bold text-[#33403b] mt-1 mb-0">-</p>
            </div>
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Keterangan / Deskripsi</span>
                <div id="modalEventDescription" class="text-sm text-gray-600 mt-1 bg-[#fffdfa] border border-solid border-[#e7ddd1] rounded-2xl p-4 min-h-[80px] whitespace-pre-line text-left">-</div>
            </div>
            <div id="modalEventStatusContainer" class="flex items-center justify-between pt-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</span>
                <span id="modalEventStatus" class="px-3 py-1 text-xs font-bold rounded-full">-</span>
            </div>
        </div>
        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-gray-50 flex justify-end border-t border-solid border-gray-100">
            <button type="button" onclick="closeEventDetailModal()" class="px-5 py-2 bg-[#5d7d6b] hover:bg-[#496454] text-white font-semibold rounded-xl border-0 transition duration-200 cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const facilitySelect = document.getElementById('facilitySelect');
    const dateInput = document.getElementById('dateInput');
    const calendarLoader = document.getElementById('calendarLoader');
    const slotsGrid = document.getElementById('slotsGrid');
    const noDataPlaceholder = document.getElementById('noDataPlaceholder');

    // Initialize Tom Select
    const tomSelectInst = new TomSelect(facilitySelect, {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });

    function fetchAvailability() {
        const facilityVal = facilitySelect.value;
        const dateVal = dateInput.value;

        if (!facilityVal || !dateVal) {
            slotsGrid.innerHTML = '';
            slotsGrid.classList.add('d-none');
            noDataPlaceholder.classList.remove('d-none');
            return;
        }

        noDataPlaceholder.classList.add('d-none');
        calendarLoader.classList.remove('d-none');
        slotsGrid.classList.add('d-none');

        // Fetch slot data from API
        fetch(`/mahasiswa/jadwal/slots?facility_id=${facilityVal}&tanggal=${dateVal}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal mengambil data jadwal.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.slots && data.slots.length > 0) {
                    slotsGrid.innerHTML = '';
                    const colCount = data.slots.length;

                    // Grid container matching the Admin layout precisely
                    const container = document.createElement('div');
                    container.className = `grid grid-cols-2 md:grid-cols-7 gap-4 w-full`;

                    data.slots.forEach(slot => {
                        const slotCol = document.createElement('div');
                        slotCol.className = 'flex flex-col items-center gap-2.5 p-3 rounded-2xl bg-white border border-solid border-[#e7ddd1] hover:border-[#5d7d6b] transition duration-200 relative group';

                        // Time Header Label
                        const timeHeader = document.createElement('div');
                        timeHeader.className = 'text-base font-semibold text-[#33403b]';
                        timeHeader.textContent = slot.label;
                        slotCol.appendChild(timeHeader);

                        // Time range details
                        const timeRange = document.createElement('div');
                        timeRange.className = 'text-xs text-[#7b8681]';
                        timeRange.textContent = `${slot.jam_mulai.substring(0, 5)} - ${slot.jam_selesai.substring(0, 5)}`;
                        slotCol.appendChild(timeRange);

                        // Colored Status Card
                        const statusCard = document.createElement('div');
                        statusCard.className = 'w-full py-2.5 px-4 rounded-xl text-sm font-bold text-center slot-block';

                        const statusLower = slot.status.toLowerCase();
                        if (statusLower === 'tersedia') {
                            statusCard.classList.add('bg-[#e2f0d9]', 'text-[#385723]', 'border', 'border-solid', 'border-[#c5e1b5]');
                            statusCard.textContent = 'Tersedia';
                        } else if (statusLower === 'dipinjam' || statusLower === 'dipakai') {
                            statusCard.classList.add('bg-[#fcebeb]', 'text-[#8b3c3c]', 'border', 'border-solid', 'border-[#f7d1d1]');
                            statusCard.textContent = 'Dipakai';
                        } else {
                            statusCard.classList.add('bg-[#fcf1d3]', 'text-[#7d6006]', 'border', 'border-solid', 'border-[#f9e2ae]');
                            statusCard.textContent = 'Menunggu';
                        }
                        slotCol.appendChild(statusCard);

                        // Associated Booking Name & Detail button if any
                        if (slot.peminjaman) {
                            const bookingLabel = document.createElement('div');
                            bookingLabel.className = 'text-[11px] text-center text-secondary truncate w-full max-w-[120px] mb-1';
                            bookingLabel.title = slot.peminjaman.nama_kegiatan;
                            bookingLabel.innerHTML = `<i class="bi bi-bookmark-fill text-muted"></i> ${slot.peminjaman.nama_kegiatan}`;
                            slotCol.appendChild(bookingLabel);

                            if (statusLower === 'dipinjam' || statusLower === 'dipakai') {
                                const detailBtn = document.createElement('button');
                                detailBtn.type = 'button';
                                detailBtn.className = 'mt-1 text-[10px] text-blue-600 hover:text-blue-800 underline border-0 bg-transparent cursor-pointer font-medium';
                                detailBtn.textContent = 'Detail Acara';
                                detailBtn.addEventListener('click', function(e) {
                                    e.stopPropagation();
                                    showBookingDetailModal({
                                        nama_kegiatan: slot.peminjaman.nama_kegiatan,
                                        keterangan: slot.peminjaman.keterangan,
                                        status: slot.status
                                    });
                                });
                                slotCol.appendChild(detailBtn);
                            }
                        }

                        container.appendChild(slotCol);
                    });

                    slotsGrid.appendChild(container);
                    slotsGrid.classList.remove('d-none');
                } else {
                    slotsGrid.innerHTML = '';
                    slotsGrid.classList.add('d-none');
                    noDataPlaceholder.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error(error);
                slotsGrid.innerHTML = '';
                slotsGrid.classList.add('d-none');
                noDataPlaceholder.classList.remove('d-none');
                noDataPlaceholder.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-triangle-fill fs-4 d-block mb-2"></i>Terjadi kesalahan saat memuat data jadwal.</span>`;
            })
            .finally(() => {
                calendarLoader.classList.add('d-none');
            });
    }

    // Set first option as selected on load
    if (facilitySelect.options.length > 1) {
        const firstVal = facilitySelect.options[1].value;
        tomSelectInst.setValue(firstVal);
    }

    facilitySelect.addEventListener('change', fetchAvailability);
    dateInput.addEventListener('change', fetchAvailability);

    // Modal controls attached to window for global access
    window.showBookingDetailModal = function(booking) {
        const modal = document.getElementById('eventDetailModal');
        const modalContent = document.getElementById('eventDetailModalContent');
        
        document.getElementById('modalEventTitle').textContent = booking.nama_kegiatan || 'Tidak ada judul';
        document.getElementById('modalEventDescription').textContent = booking.keterangan || 'Tidak ada deskripsi/keterangan.';
        
        const statusEl = document.getElementById('modalEventStatus');
        const displayStatus = booking.status.toLowerCase() === 'dipinjam' ? 'dipakai' : booking.status;
        statusEl.textContent = displayStatus.toUpperCase();
        
        statusEl.className = 'px-3 py-1 text-xs font-bold rounded-full';
        if (booking.status.toLowerCase() === 'disetujui' || booking.status.toLowerCase() === 'dipinjam' || booking.status.toLowerCase() === 'dipakai') {
            statusEl.classList.add('bg-[#e2f0d9]', 'text-[#385723]');
        } else if (booking.status.toLowerCase() === 'pending') {
            statusEl.classList.add('bg-[#fcf1d3]', 'text-[#7d6006]');
        } else {
            statusEl.classList.add('bg-gray-100', 'text-gray-600');
        }
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    };

    window.closeEventDetailModal = function() {
        const modal = document.getElementById('eventDetailModal');
        const modalContent = document.getElementById('eventDetailModalContent');
        
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    };

    // Initial load
    fetchAvailability();
});
</script>
@endsection
