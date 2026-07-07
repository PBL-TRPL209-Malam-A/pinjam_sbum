@extends('layout.app_tailwind')




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
    .banner-card {
        background-color: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.5rem;
    }
    .slot-block {
        transition: all 0.2s ease-in-out;
    }
    .slot-block:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="fs-5 fw-semibold mb-2 text-main">Kelola jadwal agar tidak bentrok</h2>
        <p class="mb-0 text-secondary text-wrap" style="max-width: 650px;">
            Admin mengatur slot penggunaan fasilitas dan memmemvalidasi konflik jadwal sebelum menyimpan.
        </p>
    </div>
</div>

<!-- Selector and Controls Card -->
<div class="card border-0 rounded-4 p-3 mb-4" style="background: #fffdfa; border: 1px solid var(--line) !important;">
    <div class="row align-items-end g-3">
        <div class="col-md-4">
            <label class="form-label text-secondary small fw-bold">Fasilitas</label>
            <select id="ruanganSelect" class="form-select border-0 bg-light rounded-3" style="height: 44px;">
                @foreach($ruangan as $room)
                    <option value="{{ $room->id_ruangan }}" {{ $room->id_ruangan == $selectedRuanganId ? 'selected' : '' }}>
                        {{ $room->nama_ruangan }}
                    </option>
                @endforeach
                @if(count($ruangan) == 0)
                    <option value="1">Aula Utama</option>
                    <option value="2">Lab Komputer 1</option>
                @endif
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label text-secondary small fw-bold">Tanggal</label>
            <input type="date" id="tanggalInput" class="form-control border-0 bg-light rounded-3" style="height: 44px;" value="{{ $selectedDate }}">
        </div>
        <div class="col-md-4 text-md-end">
            <button type="button" class="btn btn-main w-100 w-md-auto" style="height: 44px;" data-bs-toggle="modal" data-bs-target="#tambahSlotModal">
                Tambah Slot
            </button>
        </div>
    </div>
</div>

<!-- Calendar Slot Area -->
<div class="mb-3 fw-semibold text-secondary">Calendar Slot</div>
<div class="card border-0 rounded-4 p-4 mb-4" style="background: #fffdfa; border: 1px solid var(--line) !important;">
    <!-- Horizontal Slot Row -->
    <div id="calendarSlotsContainer" class="flex flex-col gap-4">
        <!-- Loader -->
        <div id="calendarLoader" class="text-center py-5">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted text-sm">Memuat data jadwal...</p>
        </div>

        <!-- Rendered Slots Container -->
        <div id="slotsGrid" class="d-none">
            <!-- Headers and Blocks will be rendered here dynamically -->
        </div>
    </div>
</div>

<!-- Validasi Konflik Warning Banner & Save Action -->
<div class="mb-3 fw-semibold text-secondary">Validasi Konflik</div>
<div class="card border-0 rounded-4 p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4" style="background: #fffdfa; border: 1px solid var(--line) !important;">
    <div class="d-flex align-items-center gap-3">
        <div id="conflictAlert" class="alert border-0 rounded-3 p-2 d-flex align-items-center mb-0 d-none" style="background-color: #fcebeb; color: #8a3c3c;">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <span class="fw-semibold small" id="conflictText">Warning: Jadwal Bentrok</span>
        </div>
        <div id="noConflictAlert" class="alert border-0 rounded-3 p-2 d-flex align-items-center mb-0" style="background-color: #dcebd7; color: #557b58;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <span class="fw-semibold small">Jadwal Aman</span>
        </div>
        <div class="text-muted small">Jika ada slot bentrok, sistem menampilkan warning sebelum admin menyimpan jadwal.</div>
    </div>
    <button type="button" id="btnSaveSchedule" class="btn-save-schedule px-5 py-2.5 bg-[#5d7d6b] hover:bg-[#496454] text-white font-semibold rounded-xl border-0 transition duration-200 cursor-pointer disabled:bg-gray-300 disabled:cursor-not-allowed">
        Simpan Jadwal
    </button>
</div>

<!-- Modal Tambah Slot -->
<div class="modal fade" id="tambahSlotModal" tabindex="-1" aria-labelledby="tambahSlotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg" style="background-color: #fffdfa;">
            <div class="modal-header border-0 pb-0" style="background-color: #f7f3eb; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <h5 class="modal-title fw-bold text-main" id="tambahSlotModalLabel">Tambah Slot Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label text-secondary fw-semibold">Jam Mulai</label>
                    <input type="time" id="inputJamMulai" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-secondary fw-semibold">Jam Selesai</label>
                    <input type="time" id="inputJamSelesai" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-secondary fw-semibold">Status Slot</label>
                    <select id="inputStatus" class="form-select rounded-3">
                        <option value="tersedia">Tersedia</option>
                        <option value="dipinjam">Dipinjam</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label text-secondary fw-semibold">Hubungkan dengan Peminjaman (Opsional)</label>
                    <select id="inputPeminjaman" class="form-select rounded-3">
                        <option value="">-- Tidak Ada Peminjaman --</option>
                        <!-- Dynamic list of peminjamans will be injected here -->
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn action-btn-outline" data-bs-dismiss="modal" style="border-radius:0.75rem;">Batal</button>
                <button type="button" id="btnAddSlotConfirm" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block" style="border-radius:0.75rem;">Tambah Slot</button>
            </div>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ruanganSelect = document.getElementById('ruanganSelect');
    const tanggalInput = document.getElementById('tanggalInput');
    const calendarLoader = document.getElementById('calendarLoader');
    const slotsGrid = document.getElementById('slotsGrid');
    const btnSaveSchedule = document.getElementById('btnSaveSchedule');
    const conflictAlert = document.getElementById('conflictAlert');
    const noConflictAlert = document.getElementById('noConflictAlert');
    const conflictText = document.getElementById('conflictText');
    const btnAddSlotConfirm = document.getElementById('btnAddSlotConfirm');
    const inputPeminjaman = document.getElementById('inputPeminjaman');

    // Local State
    let activeSlots = [];
    let todayBookings = [];
    let hasConflict = false;

    // Fetch and load slots
    function loadSlots() {
        const ruanganId = ruanganSelect.value;
        const tanggal = tanggalInput.value;

        if (!ruanganId || !tanggal) return;

        // Show loading state
        calendarLoader.classList.remove('d-none');
        slotsGrid.classList.add('d-none');

        fetch(`/admin/jadwal/api-slots?ruangan_id=${ruanganId}&tanggal=${tanggal}`)
            .then(response => {
                if (!response.ok) throw new Error('Gagal mengambil data jadwal');
                return response.json();
            })
            .then(res => {
                if (res.success) {
                    activeSlots = res.slots;
                    todayBookings = res.peminjamans;
                    
                    // Populate modal booking dropdown
                    populateBookingsDropdown();

                    // Render slots UI
                    renderSlotsGrid();

                    // Run validation checks
                    validateConflicts();
                } else {
                    alert('Gagal mengambil data jadwal.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan koneksi server.');
            })
            .finally(() => {
                calendarLoader.classList.add('d-none');
            });
    }

    function populateBookingsDropdown() {
        inputPeminjaman.innerHTML = '<option value="">-- Tidak Ada Peminjaman --</option>';
        todayBookings.forEach(p => {
            const option = document.createElement('option');
            option.value = p.id_peminjaman;
            option.textContent = `#${p.id_peminjaman} - ${p.nama_kegiatan} (${p.status})`;
            inputPeminjaman.appendChild(option);
        });
    }

    function renderSlotsGrid() {
        slotsGrid.innerHTML = '';
        slotsGrid.classList.remove('d-none');

        if (activeSlots.length === 0) {
            slotsGrid.innerHTML = '<div class="text-center text-muted py-4">Belum ada slot waktu terdaftar. Silakan tambahkan slot baru.</div>';
            return;
        }

        // Layout: Horizontal scrollable container for desktop, responsive grid for mobile
        const container = document.createElement('div');
        container.className = 'grid grid-cols-2 md:grid-cols-7 gap-4 w-full';

        activeSlots.forEach((slot, index) => {
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
            } else if (statusLower === 'dipinjam') {
                statusCard.classList.add('bg-[#fcebeb]', 'text-[#8b3c3c]', 'border', 'border-solid', 'border-[#f7d1d1]');
                statusCard.textContent = 'Dipinjam';
            } else {
                statusCard.classList.add('bg-[#fcf1d3]', 'text-[#7d6006]', 'border', 'border-solid', 'border-[#f9e2ae]');
                statusCard.textContent = 'Pending';
            }
            slotCol.appendChild(statusCard);

            // Associated Booking Name if any
            if (slot.peminjaman_id) {
                const booking = todayBookings.find(b => b.id_peminjaman == slot.peminjaman_id);
                if (booking) {
                    const bookingLabel = document.createElement('div');
                    bookingLabel.className = 'text-[11px] text-center text-secondary truncate w-full max-w-[120px] mb-1';
                    bookingLabel.title = booking.nama_kegiatan;
                    bookingLabel.innerHTML = `<i class="bi bi-bookmark-fill text-muted"></i> ${booking.nama_kegiatan}`;
                    slotCol.appendChild(bookingLabel);
 
                    if (slot.status.toLowerCase() === 'dipinjam' || slot.status.toLowerCase() === 'pending') {
                        const detailBtn = document.createElement('button');
                        detailBtn.type = 'button';
                        detailBtn.className = 'mt-1 text-[10px] text-blue-600 hover:text-blue-800 underline border-0 bg-transparent cursor-pointer font-medium';
                        detailBtn.textContent = 'Detail Acara';
                        detailBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            showBookingDetailModal(booking);
                        });
                        slotCol.appendChild(detailBtn);
                    }
                }
            }

            // Quick Delete Button (Visible on Hover / Group Hover)
            const deleteBtn = document.createElement('button');
            deleteBtn.type = 'button';
            deleteBtn.className = 'absolute -top-1.5 -right-1.5 hidden group-hover:flex items-center justify-center w-6 h-6 rounded-full bg-[#c95b50] hover:bg-[#b34e44] text-white border-0 cursor-pointer shadow-md transition duration-200';
            deleteBtn.innerHTML = '<i class="bi bi-x text-sm leading-none font-bold"></i>';
            deleteBtn.title = 'Hapus Slot';
            deleteBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                activeSlots.splice(index, 1);
                // Re-render
                renderSlotsGrid();
                validateConflicts();
            });
            slotCol.appendChild(deleteBtn);

            container.appendChild(slotCol);
        });

        slotsGrid.appendChild(container);
    }

    // Local overlap conflict validation engine
    function validateConflicts() {
        const conflicts = [];
        const count = activeSlots.length;

        for (let i = 0; i < count; i++) {
            for (let j = i + 1; j < count; j++) {
                const s1 = activeSlots[i];
                const s2 = activeSlots[j];

                // Convert times to relative minutes to compare overlap easily
                const s1Start = parseTimeToMinutes(s1.jam_mulai);
                const s1End = parseTimeToMinutes(s1.jam_selesai);
                const s2Start = parseTimeToMinutes(s2.jam_mulai);
                const s2End = parseTimeToMinutes(s2.jam_selesai);

                // Overlap condition
                if (s1Start < s2End && s1End > s2Start) {
                    // Overlap is conflicted if at least one is occupied (booked/pending)
                    if (s1.status !== 'tersedia' || s2.status !== 'tersedia') {
                        conflicts.push(`Warning: Jadwal Bentrok pada jam ${s1.jam_mulai.substring(0, 5)}-${s1.jam_selesai.substring(0, 5)} dan ${s2.jam_mulai.substring(0, 5)}-${s2.jam_selesai.substring(0, 5)}.`);
                    }
                }
            }
        }

        if (conflicts.length > 0) {
            conflictText.textContent = conflicts.join(' | ');
            conflictAlert.classList.remove('d-none');
            noConflictAlert.classList.add('d-none');
            btnSaveSchedule.disabled = true;
            hasConflict = true;
        } else {
            conflictAlert.classList.add('d-none');
            noConflictAlert.classList.remove('d-none');
            btnSaveSchedule.disabled = false;
            hasConflict = false;
        }
    }

    function parseTimeToMinutes(timeStr) {
        const parts = timeStr.split(':');
        return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
    }

    // Add slot to local state
    btnAddSlotConfirm.addEventListener('click', function () {
        const start = document.getElementById('inputJamMulai').value;
        const end = document.getElementById('inputJamSelesai').value;
        const status = document.getElementById('inputStatus').value;
        const peminjamanId = document.getElementById('inputPeminjaman').value;

        if (!start || !end) {
            alert('Jam mulai dan selesai harus diisi.');
            return;
        }

        if (start >= end) {
            alert('Jam mulai harus lebih awal dari jam selesai.');
            return;
        }

        // Add to activeSlots list
        const label = start.replace(':', '.');
        
        activeSlots.push({
            id: null,
            jam_mulai: start + ':00',
            jam_selesai: end + ':00',
            label: label,
            status: status,
            peminjaman_id: peminjamanId ? parseInt(peminjamanId) : null
        });

        // Sort slots by start time
        activeSlots.sort((a, b) => a.jam_mulai.localeCompare(b.jam_mulai));

        // Re-render
        renderSlotsGrid();
        validateConflicts();

        // Close Modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('tambahSlotModal'));
        modal.hide();

        // Reset form inputs
        document.getElementById('inputJamMulai').value = '';
        document.getElementById('inputJamSelesai').value = '';
        document.getElementById('inputStatus').value = 'tersedia';
        document.getElementById('inputPeminjaman').value = '';
    });

    // Save schedule via AJAX
    btnSaveSchedule.addEventListener('click', function () {
        if (hasConflict) return;

        const ruanganId = ruanganSelect.value;
        const tanggal = tanggalInput.value;

        fetch('/admin/jadwal/api-save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                ruangan_id: ruanganId,
                tanggal: tanggal,
                slots: activeSlots
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Gagal menyimpan jadwal');
            return response.json();
        })
        .then(res => {
            if (res.success) {
                alert(res.message);
                loadSlots();
            } else {
                alert('Gagal menyimpan jadwal.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat menyimpan jadwal.');
        });
    });

    
    ruanganSelect.addEventListener('change', loadSlots);
    tanggalInput.addEventListener('change', loadSlots);

    window.showBookingDetailModal = function(booking) {
        const modal = document.getElementById('eventDetailModal');
        const modalContent = document.getElementById('eventDetailModalContent');
        
        document.getElementById('modalEventTitle').textContent = booking.nama_kegiatan || 'Tidak ada judul';
        document.getElementById('modalEventDescription').textContent = booking.keterangan || 'Tidak ada deskripsi/keterangan.';
        
        const statusEl = document.getElementById('modalEventStatus');
        statusEl.textContent = booking.status.toUpperCase();
        
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

    // Initial Load
    loadSlots();
});
</script>
@endsection
