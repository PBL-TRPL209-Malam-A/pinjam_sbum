@extends('layout.app_tailwind')




@section('content')
<style>
    .banner-card {
        background-color: #edf2ea;
        border: 1px solid #dfe7dc;
        border-radius: 1.5rem;
    }
    .schedule-item-card {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.25rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: 0.2s;
    }
    .schedule-item-card:hover {
        transform: translateY(-2px);
    }
    .status-badge-controlled {
        background-color: #e2f0d9;
        color: #385723;
        font-weight: 600;
        padding: 0.5rem 1.5rem;
        border-radius: 2rem;
        display: inline-block;
        border: 1px solid #c5e1b5;
        font-size: 0.85rem;
    }
    .checklist-box {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 1.5rem;
    }
    .notes-box {
        background: #fffdfa;
        border: 1px solid var(--line);
        border-radius: 1.5rem;
        padding: 2rem 1.5rem;
        height: 100%;
    }
    .checklist-bullet {
        list-style-type: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    .checklist-bullet li {
        margin-bottom: 0.75rem;
        font-weight: 500;
        color: var(--text-main);
        font-size: 0.9rem;
    }
    .checklist-bullet li::before {
        content: "• ";
        color: var(--primary-main);
        font-size: 1.25rem;
        font-weight: bold;
        display: inline-block;
        width: 1em;
    }
</style>

<!-- Banner Card -->
<div class="card banner-card shadow-none mb-4">
    <div class="p-6 lg:p-8">
        <h2 class="text-xl font-semibold text-[#466454] mb-2">Pantau kegiatan peminjaman fasilitas</h2>
        <p class="text-[#7d8781] max-w-2xl">
            Pamdal melihat jadwal kegiatan hari ini, mengawasi pelaksanaan, lalu mencatat temuan lapangan.
        </p>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Schedules list -->
    <div class="col-lg-6">
        <div class="mb-3 fw-semibold text-secondary">Jadwal Hari Ini</div>

        @if($peminjaman->isEmpty())
            <div class="text-center p-4" style="background:#fffdfa; border: 1px solid var(--line); border-radius:1.25rem;">
                <p class="text-[#7d8781]">Tidak ada jadwal kegiatan yang perlu diawasi hari ini.</p>
            </div>
        @else
            @foreach($peminjaman as $item)
            <div class="schedule-item-card" style="cursor: pointer;" onclick="selectSchedule({{ $item->id_peminjaman }}, '{{ $item->nama_kegiatan }}')">
                <h4 class="fs-5 fw-bold text-main mb-1">{{ $item->nama_kegiatan }}</h4>
                <div class="text-secondary small mb-1">{{ $item->nama_fasilitas_with_type }}</div>
                <div class="text-muted small">{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d M Y') : '-' }} - {{ $item->jam_mulai ? substr($item->jam_mulai, 0, 5) : '08:00' }} - {{ $item->jam_selesai ? substr($item->jam_selesai, 0, 5) : '12:00' }}</div>
            </div>
            @endforeach
        @endif

        <div class="mb-4 mt-8 font-semibold text-[#7d8781]">Panduan Pengawasan</div>
        <div class="checklist-box">
            <div class="mb-3">
                <span class="status-badge-controlled">Parameter Aman</span>
            </div>
            <ul class="checklist-bullet">
                <li>Peserta masuk sesuai kapasitas</li>
                <li>Kegiatan sesuai aturan kampus</li>
                <li>Area sekitar aman dan tertib</li>
            </ul>
        </div>
    </div>

    <!-- Right Column: Monitoring Notes -->
    <div class="col-lg-6">
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
