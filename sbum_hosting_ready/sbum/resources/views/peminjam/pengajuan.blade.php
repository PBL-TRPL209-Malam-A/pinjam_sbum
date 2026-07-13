@extends('layout.app_tailwind')



@section('content')
            <div class="flex flex-col md:flex-row justify-between md:items-start gap-4 mb-6">
                <div>
                    <div class="text-[#7b8681] text-[20px] mb-1">Peminjam</div>
                    <h1 class="text-[24px] font-medium m-0">Peminjam · Ajukan Peminjaman</h1>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <input type="text" class="h-12 px-4 rounded-2xl border border-[#ddd2c5] bg-[#fffdfa] focus:outline-none focus:border-[#466454] w-full md:w-64 transition" placeholder="Cari data">
                    <div class="w-12 h-12 bg-[#cfdacd] rounded-full flex shrink-0"></div>
                </div>
            </div>

            <div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[28px] p-6 lg:p-8 mb-6 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-xl font-semibold mb-2">Ajukan peminjaman fasilitas</h2>
                    <p class="mb-0 text-[#5f6963]">
                        Peminjam memilih fasilitas, mengisi data peminjaman, lalu submit agar diproses pihak berwenang.
                    </p>
                </div>
                <!-- Decorative shapes -->
                <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/40 rounded-full blur-2xl"></div>
                <div class="absolute right-20 -bottom-10 w-32 h-32 bg-[#d6e5d6]/60 rounded-full blur-xl"></div>
            </div>

            @if ($errors->any())
                <div class="bg-[#fcebeb] text-[#8a3c3c] border border-[#f7d1d1] rounded-2xl mb-4 p-4">
                    <ul class="mb-0 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-[#fcebeb] text-[#8a3c3c] border border-[#f7d1d1] rounded-2xl mb-4 p-4">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="bg-[#edf7ed] text-[#2e7d32] border border-[#c8e6c9] rounded-2xl mb-4 p-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('peminjam.pengajuan.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    <div class="xl:col-span-2">
                        <div class="text-[16px] font-semibold text-[#5c6761] mb-4">Form Peminjaman</div>

                        <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6 flex flex-col gap-5">
                            <div class="flex flex-wrap gap-6 mb-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="jenis_pengajuan" id="jenisRuangan" value="ruangan" checked onchange="toggleJenisPengajuan()" class="w-4 h-4 text-[#5d7d6b] focus:ring-[#5d7d6b]">
                                    <span class="font-semibold text-[#33403b]">Peminjaman Ruangan</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="jenis_pengajuan" id="jenisBarang" value="barang" onchange="toggleJenisPengajuan()" class="w-4 h-4 text-[#5d7d6b] focus:ring-[#5d7d6b]">
                                    <span class="font-semibold text-[#33403b]">Peminjaman Barang Inventaris</span>
                                </label>
                            </div>

                            <div id="container_fasilitas_ruangan">
                                <label class="block text-[#5c6761] font-semibold mb-2">Pilih Ruangan</label>
                                <select id="facilitySelectRuangan" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition soft-input-ts">
                                    <option value="">-- Pilih Ruangan --</option>
                                    @foreach($rooms as $room)
                                        <option value="Ruangan-{{ $room->id_ruangan }}" data-pic="{{ $room->pic->nama_lengkap ?? 'Belum diatur' }}" {{ (old('facility_id') ?? $selectedFacilityId) == "Ruangan-{$room->id_ruangan}" ? 'selected' : '' }}>
                                            {{ $room->nama_ruangan }} ({{ $room->kode_ruangan }} - {{ $room->nama_gedung }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="container_fasilitas_barang" style="display: none;">
                                <label class="block text-[#5c6761] font-semibold mb-2">Pilih Barang</label>
                                <select id="facilitySelectBarang" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition soft-input-ts">
                                    <option value="">-- Pilih Barang Inventaris --</option>
                                    @foreach($items as $item)
                                        <option value="Inventaris-{{ $item->id_barang }}" data-pic="{{ $item->pic->nama_lengkap ?? 'Belum diatur' }}" {{ (old('facility_id') ?? $selectedFacilityId) == "Inventaris-{$item->id_barang}" ? 'selected' : '' }}>
                                            {{ $item->nama_barang }} ({{ $item->kode_barang }} - Stok: {{ $item->stok_tersedia }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <input type="hidden" name="facility_id" id="real_facility_id" value="{{ old('facility_id') ?? $selectedFacilityId }}">

                            <div>
                                <label class="block text-[#5c6761] font-semibold mb-2">Nama Kegiatan</label>
                                <input type="text" name="nama_kegiatan" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition" value="{{ old('nama_kegiatan') ?? 'Seminar Peminjam Baru' }}" required>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[#5c6761] font-semibold mb-2">Tanggal</label>
                                    <input type="date" name="tanggal" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition" min="{{ date('Y-m-d') }}" value="{{ old('tanggal') ?? date('Y-m-d') }}" required>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[#5c6761] font-semibold mb-2">Jam Mulai</label>
                                        <select name="jam_mulai" id="jamMulai" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition cursor-pointer" required>
                                            @for($h = 8; $h <= 17; $h++)
                                                @php $time = sprintf('%02d:00', $h); @endphp
                                                <option value="{{ $time }}" {{ old('jam_mulai', '08:00') == $time ? 'selected' : '' }}>{{ $time }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[#5c6761] font-semibold mb-2">Jam Selesai</label>
                                        <select name="jam_selesai" id="jamSelesai" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition cursor-pointer" required>
                                            @for($h = 9; $h <= 18; $h++)
                                                @php $time = sprintf('%02d:00', $h); @endphp
                                                <option value="{{ $time }}" {{ old('jam_selesai', '12:00') == $time ? 'selected' : '' }}>{{ $time }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                                <div id="container_jumlah_peserta">
                                    <label class="block text-[#5c6761] font-semibold mb-2">Jumlah Peserta</label>
                                    <input type="number" name="jumlah_peserta" id="input_jumlah_peserta" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition" min="1" value="{{ old('jumlah_peserta') ?? '180' }}" required>
                                </div>
                                <div id="container_jumlah_barang" style="display: none;">
                                    <label class="block text-[#5c6761] font-semibold mb-2">Jumlah Barang</label>
                                    <input type="number" name="jumlah_barang" id="input_jumlah_barang" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition" min="1" value="{{ old('jumlah_barang') ?? '1' }}">
                                </div>
                                <div class="lg:col-span-1">
                                    <label class="block text-[#5c6761] font-semibold mb-2">Penanggung Jawab</label>
                                    <select name="dosen_id" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition" required>
                                        <option value="">-- Pilih Dosen PJ --</option>
                                        @foreach($staff as $member)
                                            <option value="{{ $member->id_user }}" {{ old('dosen_id') == $member->id_user ? 'selected' : '' }}>
                                                {{ $member->nama_lengkap }} ({{ $member->roles->pluck('nama_role')->first() ?? 'Staf' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="lg:col-span-1">
                                    <label class="block text-[#5c6761] font-semibold mb-2">PIC Fasilitas</label>
                                    <input type="text" id="pic_display" class="h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#f5f2ec] text-[#7b8681] focus:outline-none transition cursor-not-allowed" value="-- Otomatis --" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[#5c6761] font-semibold mb-2">Catatan</label>
                                <textarea name="keterangan" class="w-full px-4 py-3 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition min-h-[100px]">{{ old('keterangan') ?? 'Tambahkan kebutuhan tambahan atau informasi kegiatan.' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="xl:col-span-1">
                        <div class="text-[16px] font-semibold text-[#5c6761] mb-4">Ringkasan Pengajuan</div>

                        <div class="bg-[#fffdfa] border border-[#e0d7cb] rounded-[24px] p-6 mb-5 shadow-sm">
                            <div class="font-semibold text-lg text-[#33403b] mb-2" id="summaryFacility">-- Pilih Fasilitas --</div>
                            <div class="text-[#7b8681] mb-3 pb-3 border-b border-[#e7ddd1]" id="summaryTime">-- Tanggal & Waktu --</div>
                            <div class="text-[#5f6963] text-sm flex items-start gap-2">
                                <i class="bi bi-shield-check text-[#5d7d6b] mt-0.5"></i>
                                Perlu verifikasi dosen dan admin
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 mb-5">
                            <button type="submit" class="h-12 bg-[#5d7d6b] hover:bg-[#496454] text-white font-semibold rounded-2xl transition border-0 cursor-pointer shadow-sm w-full">Submit Pengajuan</button>
                            <button type="button" class="h-12 bg-[#fffdfa] hover:bg-[#f5f2ec] text-[#5f6963] border border-[#dfd4c8] font-semibold rounded-2xl transition cursor-pointer w-full" onclick="alert('Draft berhasil disimpan (Mocked).')">Simpan Draft</button>
                        </div>

                        <div class="bg-[#f3e7c9] border border-[#e3c98b] rounded-[24px] p-5 shadow-sm">
                            <div class="font-semibold text-[#8a6b32] mb-1 flex items-center gap-2">
                                <i class="bi bi-info-circle-fill"></i> Info Validasi
                            </div>
                            <div class="text-[#92723c] text-sm">Jika data tidak lengkap, sistem menolak submit.</div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
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

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
let tomSelectRuangan = null;
let tomSelectBarang = null;

function toggleJenisPengajuan() {
    const jenis = document.querySelector('input[name="jenis_pengajuan"]:checked').value;
    const containerRuangan = document.getElementById('container_fasilitas_ruangan');
    const containerBarang = document.getElementById('container_fasilitas_barang');
    const containerPeserta = document.getElementById('container_jumlah_peserta');
    const containerJBarang = document.getElementById('container_jumlah_barang');
    
    const inputPeserta = document.getElementById('input_jumlah_peserta');
    const inputJBarang = document.getElementById('input_jumlah_barang');

    if (jenis === 'ruangan') {
        containerRuangan.style.display = 'block';
        containerBarang.style.display = 'none';
        containerPeserta.style.display = 'block';
        containerJBarang.style.display = 'none';
        
        inputPeserta.required = true;
        inputJBarang.required = false;
        
        // Reset the barang value
        if (tomSelectBarang) {
            tomSelectBarang.clear();
        }
    } else {
        containerRuangan.style.display = 'none';
        containerBarang.style.display = 'block';
        containerPeserta.style.display = 'none';
        containerJBarang.style.display = 'block';
        
        inputPeserta.required = false;
        inputJBarang.required = true;
        
        // Reset the ruangan value
        if (tomSelectRuangan) {
            tomSelectRuangan.clear();
        }
    }
    
    // trigger summary update
    if (typeof updateSummary === 'function') {
        updateSummary();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const facilitySelectRuangan = document.getElementById('facilitySelectRuangan');
    const facilitySelectBarang = document.getElementById('facilitySelectBarang');
    const realFacilityId = document.getElementById('real_facility_id');
    const tanggalInput = document.getElementsByName('tanggal')[0];
    const jamMulaiSelect = document.getElementById('jamMulai');
    const jamSelesaiSelect = document.getElementById('jamSelesai');
    
    const summaryFacility = document.getElementById('summaryFacility');
    const summaryTime = document.getElementById('summaryTime');

    // Initialize Tom Select
    if (facilitySelectRuangan) {
        facilitySelectRuangan.className = "h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition soft-input-ts";
        tomSelectRuangan = new TomSelect(facilitySelectRuangan, {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
    }
    if (facilitySelectBarang) {
        facilitySelectBarang.className = "h-12 w-full px-4 rounded-2xl border border-[#dfd4c8] bg-[#fffdfa] focus:outline-none focus:border-[#466454] transition soft-input-ts";
        tomSelectBarang = new TomSelect(facilitySelectBarang, {
            create: false,
            sortField: { field: "text", direction: "asc" }
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
    
    window.updateSummary = function updateSummary() {
        const jenis = document.querySelector('input[name="jenis_pengajuan"]:checked').value;
        let selectedOpt = null;
        
        if (jenis === 'ruangan' && facilitySelectRuangan && facilitySelectRuangan.selectedIndex > 0) {
            selectedOpt = facilitySelectRuangan.options[facilitySelectRuangan.selectedIndex];
        } else if (jenis === 'barang' && facilitySelectBarang && facilitySelectBarang.selectedIndex > 0) {
            selectedOpt = facilitySelectBarang.options[facilitySelectBarang.selectedIndex];
        }

        if (selectedOpt && selectedOpt.value) {
            summaryFacility.textContent = selectedOpt.text.split('(')[0].trim();
            if (realFacilityId) realFacilityId.value = selectedOpt.value;
            
            // Update PIC display
            const picDisplay = document.getElementById('pic_display');
            if (picDisplay && selectedOpt.dataset.pic) {
                picDisplay.value = selectedOpt.dataset.pic;
            }
        } else {
            summaryFacility.textContent = '-- Pilih Fasilitas --';
            if (realFacilityId) realFacilityId.value = '';
            
            const picDisplay = document.getElementById('pic_display');
            if (picDisplay) picDisplay.value = '-- Otomatis --';
        }
        
        if (tanggalInput && summaryTime) {
            const dateVal = tanggalInput.value;
            const startVal = jamMulaiSelect ? jamMulaiSelect.value : '';
            const endVal = jamSelesaiSelect ? jamSelesaiSelect.value : '';
            if (dateVal) {
                summaryTime.textContent = `${dateVal} | ${startVal.replace(':00', '.00')} - ${endVal.replace(':00', '.00')}`;
            } else {
                summaryTime.textContent = '-- Tanggal & Waktu --';
            }
        }
    }
    
    if (tomSelectRuangan) tomSelectRuangan.on('change', window.updateSummary);
    if (tomSelectBarang) tomSelectBarang.on('change', window.updateSummary);
    if (tanggalInput) tanggalInput.addEventListener('change', window.updateSummary);
    
    if (jamMulaiSelect) {
        jamMulaiSelect.addEventListener('change', function() {
            validateHours();
            window.updateSummary();
        });
    }
    if (jamSelesaiSelect) {
        jamSelesaiSelect.addEventListener('change', function() {
            if (jamMulaiSelect && jamMulaiSelect.value >= jamSelesaiSelect.value) {
                alert('Jam selesai harus lebih besar dari jam mulai.');
                validateHours();
            }
            window.updateSummary();
        });
    }
    
    // Initial run
    toggleJenisPengajuan();
});
</script>
@endsection
