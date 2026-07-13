@extends('layout.app_tailwind')




@section('content')


<!-- Banner Card -->
<div class="bg-[#edf2ea] border border-[#dfe7dc] rounded-[24px] p-6 lg:p-8 mb-6 shadow-sm">
    <div class="p-6 lg:p-8">
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Pantau kegiatan peminjaman fasilitas</h2>
        <p class="text-[#7d8781] max-w-2xl">
            Pamdal melihat jadwal kegiatan hari ini, mengawasi pelaksanaan, lalu mencatat temuan lapangan.
        </p>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-6 mb-6">
    <!-- Left Column: Schedules list -->
    <div class="w-full lg:w-1/2">
        <div class="font-semibold text-[#7d8781] mb-4">Jadwal Hari Ini</div>

        @if($peminjaman->isEmpty())
            <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] p-10 text-center">
                <p class="text-[#7d8781]">Tidak ada jadwal kegiatan yang perlu diawasi hari ini.</p>
            </div>
        @else
            @foreach($peminjaman as $item)
            <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[20px] p-6 mb-4 cursor-pointer transition hover:-translate-y-1" onclick="selectSchedule({{ $item->id_peminjaman }}, '{{ $item->nama_kegiatan }}')">
                <h4 class="text-lg font-bold text-[#466454] mb-1">{{ $item->nama_kegiatan }}</h4>
                <div class="text-[#54615b] text-sm mb-1">{{ $item->nama_fasilitas_with_type }}</div>
                <div class="text-[#7b8681] text-xs">{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d M Y') : '-' }} - {{ $item->jam_mulai ? substr($item->jam_mulai, 0, 5) : '08:00' }} - {{ $item->jam_selesai ? substr($item->jam_selesai, 0, 5) : '12:00' }}</div>
            </div>
            @endforeach
        @endif

        <div class="mb-4 mt-8 font-semibold text-[#7d8781]">Panduan Pengawasan</div>
        <div class="bg-[#fffdfa] border border-[#e6ddd2] rounded-[24px] p-6">
            <div class="mb-3">
                <span class="bg-[#e2f0d9] text-[#385723] border border-[#c5e1b5] px-6 py-2 rounded-full text-sm font-semibold inline-block">Parameter Aman</span>
            </div>
            <ul class="list-none pl-0 mb-0 space-y-3">
                <li>Peserta masuk sesuai kapasitas</li>
                <li>Kegiatan sesuai aturan kampus</li>
                <li>Area sekitar aman dan tertib</li>
            </ul>
        </div>
    </div>

    <!-- Right Column: Monitoring Notes -->
    <div class="w-full lg:w-1/2">
        <div class="mb-3 fw-semibold text-secondary">Catatan Pengawasan</div>
        <div class="notes-box d-flex flex-column justify-content-between">
            <form action="{{ route('pamdal.monitoring.store') }}" method="POST" id="monitoringForm">
                @csrf
                <input type="hidden" name="peminjaman_id" id="peminjaman_id" value="">
                
                <div class="mb-3">
                    <label class="form-label text-main fw-semibold">Pilih Kegiatan dari Jadwal di Kiri</label>
                    <input type="text" id="selected_kegiatan" class="form-control" readonly placeholder="Belum ada kegiatan yang dipilih" style="background-color: #f7f3eb; border-color: #dfd4c8; border-radius: 0.75rem;">
                </div>

                <div class="mb-3">
                    <label class="form-label text-main fw-semibold">Status Pengawasan</label>
                    <select name="status_pengawasan" class="form-select" required style="border-radius: 0.75rem; border-color: #dfd4c8;">
                        <option value="Aman Terkendali">Aman Terkendali</option>
                        <option value="Ada Kendala">Ada Kendala</option>
                    </select>
                </div>
                
                <label class="form-label text-main fw-semibold">Catatan Lapangan</label>
                <textarea name="catatan" class="form-control mb-4" rows="6" required style="border-radius: 0.75rem; border-color: #dfd4c8; font-size: 0.95rem; line-height: 1.6; resize: none;" placeholder="Tuliskan temuan lapangan..."></textarea>
                <button type="submit" class="bg-[#466454] hover:bg-[#395244] text-white px-4 py-2 rounded-xl font-semibold transition inline-block" style="height: 48px; border-radius: 0.75rem;" id="submitBtn" disabled>Simpan Catatan</button>
            </form>
        </div>
    </div>
</div>

<script>
    function selectSchedule(id, name) {
        document.getElementById('peminjaman_id').value = id;
        document.getElementById('selected_kegiatan').value = name;
        document.getElementById('submitBtn').disabled = false;
        
        // Visual cue (optional)
        document.querySelectorAll('.schedule-item-card').forEach(card => card.style.borderColor = 'var(--line)');
        event.currentTarget.style.borderColor = 'var(--primary-main)';
    }
</script>
@endsection
