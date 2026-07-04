@extends('layout.app_tailwind')



@section('content')
            <div class="flex flex-col md:flex-row justify-between md:items-start gap-4 mb-6">
                <div>
                    <div class="text-[#7b8681] text-[20px] mb-1">Mahasiswa</div>
                    <h1 class="text-[24px] font-medium m-0">Mahasiswa · Jadwal Ketersediaan</h1>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <input type="text" class="h-12 px-4 rounded-2xl border border-[#ddd2c5] bg-[#fffdfa] focus:outline-none focus:border-[#466454] w-full md:w-64 transition" placeholder="Cari data">
                    <div class="w-12 h-12 bg-[#cfdacd] rounded-full flex shrink-0"></div>
                </div>
            </div>

            <div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[28px] p-6 lg:p-8 mb-6 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-xl font-semibold mb-2">Lihat jadwal ketersediaan fasilitas</h2>
                    <p class="mb-0 text-[#5f6963]">
                        Mahasiswa membuka menu jadwal dan sistem menampilkan slot pemakaian agar tidak terjadi bentrok.
                    </p>
                </div>
                <!-- Decorative shapes -->
                <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/40 rounded-full blur-2xl"></div>
                <div class="absolute right-20 -bottom-10 w-32 h-32 bg-[#d6e5d6]/60 rounded-full blur-xl"></div>
            </div>

            <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
            <style>
                /* Custom premium styling for Tom Select */
                .ts-wrapper.soft-input-ts { border: none; background: transparent; }
                .ts-control { height: 50px !important; border-radius: 1rem !important; border: 1px solid #dfd4c8 !important; background: #fffdfa !important; padding: 0.65rem 1rem !important; font-size: 1rem; color: #33403b; box-shadow: none !important; display: flex; align-items: center; }
                .ts-dropdown { border-radius: 1rem !important; border: 1px solid #dfd4c8 !important; background: #fffdfa !important; box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important; padding: 0.5rem; z-index: 1050; }
                .ts-dropdown .optgroup-header { font-weight: 700; color: #7b8681; padding: 0.5rem 0.75rem; }
                .ts-dropdown .option { padding: 0.5rem 0.75rem; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s ease; }
                .ts-dropdown .option:hover, .ts-dropdown .active { background-color: #edf3ee !important; color: #496454 !important; }
            </style>

            <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-semibold text-[#5c6761] mb-2">Fasilitas</label>
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
                    <div>
                        <label class="block font-semibold text-[#5c6761] mb-2">Tanggal</label>
                        <input type="date" id="dateInput" class="h-[50px] w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>

            <div class="text-[16px] font-semibold text-[#5c6761] mb-4">Kalender Ketersediaan</div>

            <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6 mb-8 min-h-[200px]">
                <div id="calendarSlotsContainer" class="flex flex-col gap-4">
                    <!-- Loader -->
                    <div id="calendarLoader" class="text-center py-10 d-none">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-[#7b8681] text-sm">Memuat data jadwal...</p>
                    </div>

                    <!-- Slots Grid Container -->
                    <div id="slotsGrid" class="d-none">
                        <!-- Rendered Slots will go here -->
                    </div>

                    <div id="noDataPlaceholder" class="text-center py-10 text-[#7b8681] d-none">
                        <i class="bi bi-info-circle text-2xl block mb-2"></i>
                        Pilih fasilitas dan tanggal terlebih dahulu untuk melihat ketersediaan.
                    </div>
                </div>
            </div>

            <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6">
                <h3 class="text-lg font-semibold mb-3">Catatan Jadwal</h3>
                <p class="mb-0 text-[#7b8681]">
                    Slot merah sudah dipakai, slot kuning masih dalam proses persetujuan, slot hijau bisa diajukan.
                </p>
            </div>
        </main>
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
                            bookingLabel.className = 'text-[11px] text-center text-secondary truncate w-full max-w-[120px] mb-1 mt-1';
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
